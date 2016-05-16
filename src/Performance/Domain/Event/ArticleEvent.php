<?php

namespace Performance\Domain\Event;


use Performance\Domain\Article;
use Redis;
use Symfony\Component\EventDispatcher\Event;

class ArticleEvent extends Event
{

    private $redis;
    private $article;

    public function __construct(Redis $a_redis, Article $an_article)
    {
        $this->redis = $a_redis;
        $this->article = $an_article;
    }

    public function getRedis(){
        return $this->redis;
    }

    public  function getArticle(){
        return $this->article;
    }
}