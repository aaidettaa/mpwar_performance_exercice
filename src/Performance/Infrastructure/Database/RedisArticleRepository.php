<?php

namespace Performance\Infrastructure\Database;

use Performance\Domain\Article;
use Performance\Domain\ArticleRepository;
use Performance\Infrastructure\HttpCache\HttpCache;


class RedisArticleRepository implements ArticleRepository
{
    private $articleRepository;
    private $httpCache;
    public function __construct(ArticleRepository $articleRepository, HttpCache $httpCache)
    {
        $this->articleRepository = $articleRepository;
        $this->httpCache = $httpCache;
    }

    public function save(Article $article)
    {
        $this->articleRepository->save($article);
    }


    public function findOneById($article_id)
    {
        $article = $this->httpCache->getArticle($article_id);
        if($article != null){
            return $article;
        }
        return $this->articleRepository->findOneById($article_id);
    }

    public function findAll()
    {
        return $this->findAll();
    }
}