<?php


namespace Performance\Infrastructure\HttpCache;

use Redis;
use \DateTime;

class HttpCacheWithRedis implements HttpCache
{
    private $redis;
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }


    public function getLastModified($key)
    {
       $lastModified =  $this->redis->get($key);
       return new DateTime($lastModified);
    }

    public function setLastModified($key, \DateTime $dateTime){
        $dateFormated = $dateTime->format(self::FORMAT_DATE);
        $this->redis->set($key,$dateFormated);
    }
}