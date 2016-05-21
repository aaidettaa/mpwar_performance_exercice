<?php


namespace Performance\Domain\ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Performance\Infrastructure\HttpCache\HttpCacheWithRedis;
class RedisHttpCacheServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
       $pimple['redisCache'] = function () use ($pimple) {
           $redisCache = new HttpCacheWithRedis($pimple['redis']);

           return $redisCache;
       };
    }
}