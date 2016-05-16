<?php

namespace Performance\Controller;

use Symfony\Component\HttpFoundation\Response;
use Performance\Domain\UseCase\ListArticles;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController
{
    /**
     * @var \Twig_Environment
     */
	private $template;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(\Twig_Environment $templating, ListArticles $useCase, SessionInterface $session) {
        $this->template = $templating;
        $this->useCase = $useCase;
        $this->session = $session;
    }

    public function get()
    {
        $logged = true;
        if (!$this->session->get('author_id')) {
            $logged = false;
        }
        $articles = $this->useCase->execute();
        $page = 'home';
        $user_id = $this->session->get('author_id');
        return new Response($this->template->render('home.twig', [
            'articles' => $articles,
            'logged' => $logged,
            'page' => $page,
            'user_id' => $user_id
        ]));
    }
}