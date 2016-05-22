<?php

namespace Performance\Domain\Event;


use Performance\Domain\UseCase\AddLastModifiedArticle;
use Performance\Domain\UseCase\AddVisitArticle;
use Performance\Infrastructure\HttpCache\HttpCache;
use Performance\Infrastructure\HttpCache\HttpCacheWithRedis;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ArticleEventSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(ArticleEvents::READED => 'onReadedArticle',
            ArticleEvents::MODIFIED => 'onModifiedArticle',
            ArticleEvents::CREATED => 'onCreatedArticle');
    }

    public function onReadedArticle(ArticleEvent $event)
    {
        $theAddVisitArticleService = new AddVisitArticle($event->getRedis());
        $theAddVisitArticleService->execute($event->getArticle(), $event->getUserName());
    }

    public function onModifiedArticle(ArticleEvent $event)
    {
        $this->addLastModified($event);
    }

    public function onCreatedArticle(ArticleEvent $event)
    {
        $this->addLastModified($event);
    }

    private function addLastModified(ArticleEvent $event)
    {
        $redisHttpCache = new HttpCacheWithRedis($event->getRedis());
        $addLastModifiedService = new AddLastModifiedArticle($redisHttpCache);
        $key = HttpCache::KEY_FOR_LAST_MODIFIED . $event->getArticle()->getId();
        $addLastModifiedService->execute($key);
    }

}