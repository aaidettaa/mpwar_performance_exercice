<?php

namespace Performance\Controller;

use Performance\Domain\UseCase\GetTopFive;
use Performance\Domain\UseCase\ListArticles;
use Redis;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @var ListArticles
     */
    private $useCase;

    /**
     * @var GetTopFive
     */
    private $rankingUseCase;

    /**
     * @var Redis
     */
    private $redis;

    public function __construct(\Twig_Environment $templating,
                                ListArticles $useCase,
                                GetTopFive $rankingUseCase,
                                SessionInterface $session,
                                Redis $redis)
    {
        $this->template = $templating;
        $this->useCase = $useCase;
        $this->rankingUseCase = $rankingUseCase;
        $this->session = $session;
        $this->redis = $redis;
    }

    public function get()
    {
        $logged = true;
        if (!$this->session->get('author_id')) {
            $logged = false;
        }

        $user_id = $this->session->get('author_id');

        $articles = $this->useCase->execute();

        $rankingGlobal = $this->rankingUseCase->getGlobally();

        $page = 'home';

        if ($logged) {

            $rankingUser = $this->rankingUseCase->getByUser($user_id);
            $rankingAuthor = $this->rankingUseCase->getByAuthor($user_id);

            return new Response($this->template->render('home.twig', [
                'articles' => $articles,
                'rankingGlobal' => $rankingGlobal,
                'rankingUser' => $rankingUser,
                'rankingAuthor' => $rankingAuthor,
                'logged' => $logged,
                'page' => $page,
                'user_id' => $user_id
            ]));

        } else {

            return new Response($this->template->render('home.twig', [
                'articles' => $articles,
                'rankingGlobal' => $rankingGlobal,
                'logged' => $logged,
                'page' => $page,
                'user_id' => $user_id
            ]));
        }
    }
}