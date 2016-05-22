<?php

namespace Performance\Domain\UseCase;

use Performance\Domain\Article;
use Performance\Domain\ArticleRepository;
use Redis;

class GetTopFive
{
    private $redis;
    private $articleRepository;

    public function __construct(Redis $redis, ArticleRepository $articleRepository)
    {
        $this->redis = $redis;
        $this->articleRepository = $articleRepository;
    }

    public function getGlobally(){
        $topFiveIDs = $this->redis->zRange('RankingGlobalArticles', -5, -1);
        $topFiveIDs = array_reverse($topFiveIDs);
        $articles = [];
        foreach ($topFiveIDs as $article_id){
            $article = $this->articleRepository->findOneById($article_id);
            array_push($articles, $article);
        }
        return $articles;
    }
}