<?php

namespace Performance\Controller;

use Performance\Domain\UseCase\EditArticle;
use Performance\Domain\UseCase\ReadArticle;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EditArticleController
{
    /**
     * @var \Twig_Environment
     */
    private $template;

    /**
     * @var UrlGeneratorInterface
     */
    private $url_generator;

    /**
     * @var ReadArticle
     */
    private $readArticle;

    /**
     * @var EditArticle
     */
    private $useCase;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(\Twig_Environment $templating,
                                UrlGeneratorInterface $url_generator,
                                EditArticle $useCase,
                                ReadArticle $readArticle,
                                SessionInterface $session)
    {
        $this->template = $templating;
        $this->url_generator = $url_generator;
        $this->readArticle = $readArticle;
        $this->useCase = $useCase;
        $this->session = $session;
    }

    public function get($article_id)
    {
        $logged = true;
        if (!$this->session->get('author_id')) {
            $logged = false;
            return new RedirectResponse($this->url_generator->generate('login'));
        }

        $article = $this->readArticle->execute($article_id, $this->session->get('author_id'));

        $page = 'editArticle';

        return new Response($this->template->render('editArticle.twig', [
            'article' => $article,
            'logged' => $logged,
            'page' => $page
        ]));
    }

    public function post(Request $request)
    {
        $article = $request->request->get('article_id');
        $title = $request->request->get('title');
        $content = $request->request->get('content');

        $this->useCase->execute($article, $title, $content);

        return new RedirectResponse($this->url_generator->generate('article', [
            'article_id' => $request->get('article_id')
        ]));
    }
}