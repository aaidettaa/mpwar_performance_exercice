<?php

namespace Performance\Domain\UseCase;


use Performance\Domain\Article;
use Redis;

class AddVisitArticle {

    /**
     * @var $redis Redis
     **/
    private $redis;

    public function __construct($aRedis){
        $this->redis = $aRedis;
    }

    public function execute(Article $an_article){
        $this->redis->set('teeeest_'.$an_article->getId(),$an_article->getId());
    }
}