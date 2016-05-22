<?php

namespace Performance\Infrastructure\Database;

use Performance\Domain\Article;
use Performance\Domain\ArticleRepository;
use Redis;

class RedisArticleRepository implements ArticleRepository
{
    private $articleRepository;
    private $redis;
    public function __construct(ArticleRepository $articleRepository, Redis $redis)
    {
        $this->articleRepository = $articleRepository;
        $this->redis = $redis;
    }

    public function save(Article $article)
    {
        $this->articleRepository->save($article);
    }


    public function findOneById($article_id)
    {
        //TODO get article for redis
        return $this->articleRepository->findOneById($article_id);
    }

    public function findAll()
    {
        return $this->findAll();
    }
}