<?php

use Performance\Infrastructure\Session\NativeRedisSessionHandler;
use Performance\Domain\Event\ArticleEventSubscriber;
use Performance\Infrastructure\ServiceProvider\RedisServiceProvider;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Performance\Infrastructure\ServiceProvider\RedisHttpCacheServiceProvider;
use Performance\Infrastructure\ServiceProvider\ArticlesInRedisServiceProvider;

$app = new Application();

$app->register(new Performance\DomainServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new DoctrineServiceProvider);
$app->register(new DoctrineOrmServiceProvider);

$app['session.storage.handler'] = function ($app) {
    return new NativeRedisSessionHandler($app['session.storage.save_path']);
};

$app['twig'] = $app->extend('twig', function (\Twig_Environment $twig) use ($app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
        return sprintf('/mpwar_performance_exercise/web/assets/%s', ltrim($asset, '/'));
    }));
    return $twig;
});

$app->register(new RedisServiceProvider());
$app->register(new ArticlesInRedisServiceProvider());

$app['redis.options'] = [
    'host' => 'localhost',
    'port' => 6379,
    'timeout' => 0,
    'password' => 'qwerty'
];

$app->register(new RedisHttpCacheServiceProvider());

$app->before(function (Request $request) use ($app) {
    $app['dispatcher']->addSubscriber(new ArticleEventSubscriber());
});

return $app;
