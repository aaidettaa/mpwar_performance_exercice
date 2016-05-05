<?php

use Performance\Infrastructure\Session\NativeRedisSessionHandler;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;

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

//$app['twig'] =
//    function($app) {
//        $app->extend('twig', function($twig, $app) {
//            $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
//                // implement whatever logic you need to determine the asset path
//
//                return sprintf('/var/www/mpwar_performance_exercise/web/assets/%s', ltrim($asset, '/'));
//            }));
//
//            return $twig;
//        });
//    };

return $app;
