<?php

namespace Performance\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Performance\Domain\UseCase\WriteArticle;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WriteArticleController
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
     * @var WriteArticle
     */
    private $useCase;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(\Twig_Environment $templating, UrlGeneratorInterface $url_generator, WriteArticle $useCase, SessionInterface $session) {
        $this->template = $templating;
        $this->url_generator = $url_generator;
        $this->useCase = $useCase;
        $this->session = $session;
    }

    public function get()
    {
        $logged = true;
        if (!$this->session->get('author_id')) {
            $logged = false;
            return new RedirectResponse($this->url_generator->generate('login'));
        }
        $page = 'writeArticle';
        return new Response($this->template->render('writeArticle.twig', [
            'logged' => $logged,
            'page' => $page
        ]));
    }

    public function post(Request $request)
    {
    	$title = $request->request->get('title');
    	$content = $request->request->get('content');

    	$article = $this->useCase->execute($title, $content);

        return new RedirectResponse($this->url_generator->generate('article', ['article_id' => $article->getId()]));
    }
}