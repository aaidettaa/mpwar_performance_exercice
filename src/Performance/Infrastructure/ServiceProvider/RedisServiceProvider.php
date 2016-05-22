<?php

namespace Performance\Infrastructure\ServiceProvider;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Redis;

class RedisServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['redis'] = function () use ($pimple) {
            $redis = new Redis();
            $options = $pimple['redis.options'];
            $redis->connect($options['host'],
                $options['port']);
                //$options['timeout']);
            //$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
            //$redis->setOption(Redis::OPT_PREFIX, $options['dbname'].':');
            //$redis->auth($options['password']);
            return $redis;
        };
    }
}