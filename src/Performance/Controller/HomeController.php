<?php

namespace Performance\Controller;

use Symfony\Component\HttpFoundation\Response;
use Performance\Domain\UseCase\ListArticles;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Redis;

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

    private $redis;
    public function __construct(\Twig_Environment $templating, ListArticles $useCase, SessionInterface $session, Redis $redis) {
        $this->template = $templating;
        $this->useCase  = $useCase;
        $this->session  = $session;
        $this->redis    = $redis;
    }

    public function get()
    {
        $logged = true;
        if (!$this->session->get('author_id')) {
            $logged = false;
        }
        $user_id = $this->session->get('author_id');
        $articles = $this->useCase->execute($this->redis,$user_id);
        $page = 'home';
        return new Response($this->template->render('home.twig', [
            'articles' => $articles,
            'logged' => $logged,
            'page' => $page,
            'user_id' => $user_id
        ]));
    }
}