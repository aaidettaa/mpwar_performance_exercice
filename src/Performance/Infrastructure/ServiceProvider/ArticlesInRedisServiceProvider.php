<?php


namespace Performance\Infrastructure\ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Performance\Infrastructure\Database\RedisArticleRepository;

class ArticlesInRedisServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['redisArticleRepository'] = function () use ($pimple) {
            $redisArticleRepository = new RedisArticleRepository( $pimple['orm.em']->getRepository('Performance\Domain\Article'),
                                                                  $pimple['redisCache']);
            return $redisArticleRepository;
        };
    }
}