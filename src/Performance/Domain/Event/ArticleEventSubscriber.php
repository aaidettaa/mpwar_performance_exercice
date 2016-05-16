<?php

namespace Performance\Domain\Event;


use Performance\Domain\UseCase\AddVisitArticle;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ArticleEventSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents() {
        return array(ArticleEvents::READED => 'onReadedArticle');
    }

    public function onReadedArticle(ArticleEvent $event) {
        $theAddVisitArticleService = new AddVisitArticle();
        $theAddVisitArticleService->execute();
    }
}