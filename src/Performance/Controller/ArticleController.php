<?php

namespace Performance\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Performance\Domain\UseCase\ReadArticle;
use Performance\Infrastructure\HttpCache\HttpCache;

class ArticleController
{
    const KEY_FOR_ETAG = "cache:etag:article:";

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

    public function __construct(\Twig_Environment $templating, ReadArticle $useCase, SessionInterface $session, HttpCache $httpCache) {
        $this->template = $templating;
        $this->useCase = $useCase;
        $this->session = $session;
        $this->httpCache = $httpCache;
    }

    public function get($article_id)
    {
        $article = $this->useCase->execute($article_id, $this->session->get('author_id'));

        if (!$article) {
            throw new HttpException(404, "Article $article_id does not exist.");
        }

        $responseContent = $this->template->render('article.twig', ['article' => $article]);
        $key = self::KEY_FOR_ETAG . $article_id;
        $etag = $this->httpCache->generateEtag($responseContent);
        $this->httpCache->setEtag($key,$etag);

        $response = new Response($responseContent);
        $response->setEtag($etag);
        return $response;
    }
}