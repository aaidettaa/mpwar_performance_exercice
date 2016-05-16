<?php

namespace Performance\Domain\Event;


use Redis;
use Symfony\Component\EventDispatcher\Event;

class ArticleEvent extends Event
{

    private $redis;

    public function __construct(Redis $a_redis)
    {
        $this->redis = $a_redis;
    }

    public function getRedis(){
        return $this->redis;
    }


}