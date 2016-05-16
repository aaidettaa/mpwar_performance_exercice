<?php

namespace Performance\Domain\UseCase;


use Redis;

class AddVisitArticle {

    /**
     * @var $redis Redis
     **/
    private $redis;

    public function __construct($aRedis){
        $redis = $aRedis;
    }

    public function execute(){
        $this->redis->set('asd','asd');
    }
}