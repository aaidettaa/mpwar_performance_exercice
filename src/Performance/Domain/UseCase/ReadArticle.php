<?php

namespace Performance\Domain\UseCase;

use Performance\Domain\ArticleRepository;
use Performance\Domain\Event\ArticleEvent;
use Performance\Domain\Event\ArticleEvents;
use Redis;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReadArticle
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $event_dispatcher;

    /**
     * @var Redis
     */
    private $redis;

    public function __construct(ArticleRepository $articleRepository,
                                EventDispatcherInterface $an_event_dispatcher,
                                Redis $a_redis)
    {
        $this->articleRepository = $articleRepository;
        $this->event_dispatcher = $an_event_dispatcher;
        $this->redis = $a_redis;
    }

    public function execute($article_id, $user_name)
    {
        $the_article = $this->articleRepository->findOneById($article_id);

        $article_readed_event = new ArticleEvent($this->redis, $the_article, $user_name);
        $this->event_dispatcher->dispatch(ArticleEvents::READED, $article_readed_event);

        return $the_article;
    }
}