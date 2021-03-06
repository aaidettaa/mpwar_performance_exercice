<?php

namespace Performance\Controller;

use Performance\Domain\UseCase\Login;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginController
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
     * @var Login
     */
    private $useCase;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(\Twig_Environment $templating,
                                UrlGeneratorInterface $url_generator,
                                Login $useCase,
                                SessionInterface $session)
    {
        $this->template = $templating;
        $this->url_generator = $url_generator;
        $this->useCase = $useCase;
        $this->session = $session;
    }

    public function post(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        if ($this->useCase->execute($username, $password)) {
            return new RedirectResponse($this->url_generator->generate('home'));
        } else {
            return new RedirectResponse($this->url_generator->generate('login'));
        }
    }

    public function get()
    {
        $logged = true;
        if (!$this->session->get('author_id')) {
            $logged = false;
        }
        $page = 'login';
        return new Response($this->template->render('login.twig', [
            'logged' => $logged,
            'page' => $page
        ]));
    }

    public function logout()
    {
        $this->session->clear();
        return new RedirectResponse($this->url_generator->generate('login'));
    }
}