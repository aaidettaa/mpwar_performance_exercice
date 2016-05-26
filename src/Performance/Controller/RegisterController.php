<?php

namespace Performance\Controller;

use Performance\Domain\UseCase\SignUp;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegisterController
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
     * @var SignUp
     */
    private $useCase;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(\Twig_Environment $templating,
                                UrlGeneratorInterface $url_generator,
                                SignUp $useCase,
                                SessionInterface $session)
    {
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
        }
        $page = 'register';
        return new Response($this->template->render('register.twig', [
            'logged' => $logged,
            'page' => $page
        ]));
    }

    public function post(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $uploadedFile = $request->files->get('imageInputFile');

        if(!is_null($uploadedFile) && $uploadedFile->getClientMimeType() != image_type_to_mime_type(IMAGETYPE_JPEG)){
            return new Response($this->template->render('register.twig', [
                'logged' => false,
                'page' => 'register',
            ]));
        }

        $this->useCase->execute($username, $password,  $uploadedFile);

        return new RedirectResponse($this->url_generator->generate('login'));
    }
}