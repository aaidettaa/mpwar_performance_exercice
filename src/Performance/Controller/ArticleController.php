<?php

namespace Performance\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Performance\Domain\UseCase\ReadArticle;
use Performance\Infrastructure\HttpCache\HttpCache;

class ArticleController
{
    /**
     * @var \Twig_Environment
     */
    private $template;

    /**
     * @var ReadArticle
     */
    private $useCase;

    /**
     * @var SessionInterface
     */
    private $session;

    private $httpCache;

    private $request;

    public function __construct(\Twig_Environment $templating, ReadArticle $useCase,
                                SessionInterface $session, HttpCache $httpCache, Request $request) {

        $this->template     = $templating;
        $this->useCase      = $useCase;
        $this->session      = $session;
        $this->httpCache    = $httpCache;
        $this->request      = $request;
    }

    public function get($article_id)
    {
        $logged = true;
        if (!$this->session->get('author_id')) {
            $logged = false;
        }
        $article = $this->useCase->execute($article_id, $this->session->get('author_id'));
        if (!$article) {
            throw new HttpException(404, "Article $article_id does not exist.");
        }

        $response = new Response();
        $lastModifiedArticleKey = HttpCache::KEY_FOR_LAST_MODIFIED . $article_id;
        $lastModified = $this->httpCache->getLastModified($lastModifiedArticleKey);

        $response->setLastModified($lastModified);
        if($response->isNotModified($this->request)){
            return $response;
        }
        $this->httpCache->setResponse($article);
        $page = 'article';
        $responseContent = $this->template->render('article.twig', [
            'article' => $article,
            'logged' => $logged,
            'page' => $page
        ]);
        $response->setContent($responseContent);
        return $response;
    }
}