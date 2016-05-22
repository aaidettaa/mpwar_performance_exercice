<?php

namespace Performance\Domain\UseCase;


use Performance\Domain\Article;
use Redis;

class AddVisitArticle
{

    /**
     * @var $redis Redis
     **/
    private $redis;

    public function __construct(Redis $aRedis)
    {
        $this->redis = $aRedis;
    }

    public function execute(Article $an_article, $a_userName)
    {
        $this->redis->zIncrBy('RankingGlobalArticles', 1, $an_article->getId());
        $this->redis->zIncrBy('RankingArticlesByUser_' . $a_userName, 1, $an_article->getId());
        $this->redis->zIncrBy('RankingArticlesByAuthor_' . $an_article->getAuthor()->getId(), 1, $an_article->getId());
    }
}