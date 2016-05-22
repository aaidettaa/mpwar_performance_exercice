<?php

namespace Performance;

use Performance\Controller\ArticleController;
use Performance\Controller\EditArticleController;
use Performance\Controller\HomeController;
use Performance\Controller\LoginController;
use Performance\Controller\RegisterController;
use Performance\Controller\WriteArticleController;
use Performance\Domain\UseCase\AddLastModifiedArticle;
use Performance\Domain\UseCase\AddVisitArticle;
use Performance\Domain\UseCase\EditArticle;
use Performance\Domain\UseCase\GetTopFive;
use Performance\Domain\UseCase\ListArticles;
use Performance\Domain\UseCase\LoadImage;
use Performance\Domain\UseCase\Login;
use Performance\Domain\UseCase\ReadArticle;
use Performance\Domain\UseCase\ReadImage;
use Performance\Domain\UseCase\SignUp;
use Performance\Domain\UseCase\WriteArticle;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DomainServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['useCases.signUp'] = function () use ($app) {
            return new SignUp($app['orm.em']->getRepository('Performance\Domain\Author'));
        };

        $app['useCases.login'] = function () use ($app) {
            return new Login($app['orm.em']->getRepository('Performance\Domain\Author'),
                $app['session']);
        };

        $app['useCases.writeArticle'] = function () use ($app) {
            return new WriteArticle($app['orm.em']->getRepository('Performance\Domain\Article'),
                $app['orm.em']->getRepository('Performance\Domain\Author'),
                $app['session'],
                $app['dispatcher'],
                $app['redis']);
        };

        $app['useCases.editArticle'] = function () use ($app) {
            return new EditArticle($app['orm.em']->getRepository('Performance\Domain\Article'),
                $app['orm.em']->getRepository('Performance\Domain\Author'),
                $app['session'],
                $app['dispatcher'],
                $app['redis']);
        };

        $app['useCases.readArticle'] = function () use ($app) {
            return new ReadArticle($app['orm.em']->getRepository('Performance\Domain\Article'),
                $app['dispatcher'],
                $app['redis']);
        };

        $app['useCases.listArticles'] = function () use ($app) {
            return new ListArticles($app['orm.em']->getRepository('Performance\Domain\Article'));
        };

        $app['useCases.rankingArticles'] = function () use ($app) {
            return new GetTopFive($app['redis'], $app['redisArticleRepository']);
        };

        $app['useCases.addVisitArticle'] = function () use ($app) {
            return new AddVisitArticle($app['redis']);
        };

        $app['useCases.addLastModifiedArticle'] = function () use ($app) {
            return new AddLastModifiedArticle($app['redisCache']);
        };

        $app['useCases.readImage'] = function () use ($app) {
            return new ReadImage($app['imageAwsS3']);
        };

        $app['useCases.loadImage'] = function () use ($app) {
            return new LoadImage($app['imageAwsS3']);
        };

        $app['controllers.readArticle'] = function () use ($app) {
            return new ArticleController($app['twig'],
                $app['useCases.readArticle'],
                $app['session'],
                $app['redisCache'],
                $app['request_stack']->getCurrentRequest()
            );
        };

        $app['controllers.writeArticle'] = function () use ($app) {
            return new WriteArticleController($app['twig'],
                $app['url_generator'],
                $app['useCases.writeArticle'],
                $app['session']);
        };

        $app['controllers.editArticle'] = function () use ($app) {
            return new EditArticleController($app['twig'],
                $app['url_generator'],
                $app['useCases.editArticle'],
                $app['useCases.readArticle'],
                $app['session']);
        };

        $app['controllers.login'] = function () use ($app) {
            return new LoginController($app['twig'],
                $app['url_generator'],
                $app['useCases.login'],
                $app['session']);
        };

        $app['controllers.signUp'] = function () use ($app) {
            return new RegisterController($app['twig'],
                $app['url_generator'],
                $app['useCases.signUp'],
                $app['session']);
        };

        $app['controllers.home'] = function () use ($app) {
            return new HomeController($app['twig'],
                $app['useCases.listArticles'],
                $app['useCases.rankingArticles'],
                $app['session'],
                $app['redis']);
        };
    }
}