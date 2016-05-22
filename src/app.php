<?php

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Performance\Domain\Event\ArticleEventSubscriber;
use Performance\Infrastructure\ServiceProvider\ArticlesInRedisServiceProvider;
use Performance\Infrastructure\ServiceProvider\RedisHttpCacheServiceProvider;
use Performance\Infrastructure\ServiceProvider\RedisServiceProvider;
use Performance\Infrastructure\Session\NativeRedisSessionHandler;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();

$app->register(new Performance\DomainServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new DoctrineServiceProvider);
$app->register(new DoctrineOrmServiceProvider);
$app->register(new RedisServiceProvider());
$app->register(new ArticlesInRedisServiceProvider());
$app->register(new RedisHttpCacheServiceProvider());

$app['session.storage.handler'] = function ($app) {
    return new NativeRedisSessionHandler($app['session.storage.save_path']);
};

$app['twig'] = $app->extend('twig', function (\Twig_Environment $twig) use ($app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
        return sprintf('http://dafyuik0phxjj.cloudfront.net/assets/%s', ltrim($asset, '/'));
    }));
    return $twig;
});

$app->before(function (Request $request) use ($app) {
    $app['dispatcher']->addSubscriber(new ArticleEventSubscriber());
});

return $app;
