<?php


namespace Performance\Infrastructure\HttpCache;

use Redis;

class HttpCacheWithRedis implements HttpCache
{
    private $redis;
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function setEtag($key, $value)
    {
        $this->redis->set($key, $value);
    }

    public function getEtag($key)
    {
        // TODO: Implement getEtag() method.
        throw new \Exception("Method not implemented");
    }
}