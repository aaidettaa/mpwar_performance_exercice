<?php

namespace Performance\Domain\UseCase;

use Performance\Domain\Article;
use Performance\Domain\ArticleRepository;
use Redis;

class GetTopFive
{
    const RANKING_GLOBALLY = "RankingGlobalArticles";
    const RANKING_BY_USER = "RankingArticlesByUser_";
    private $redis;
    private $articleRepository;

    public function __construct(Redis $redis, ArticleRepository $articleRepository)
    {
        $this->redis = $redis;
        $this->articleRepository = $articleRepository;
    }

    public function getGlobally(){
        $ranking = self::RANKING_GLOBALLY;
        return $this->getArticles($ranking);
    }

    public function getByUser($user_id){
        $ranking = self::RANKING_BY_USER . $user_id;
        return $this->getArticles($ranking);
    }

    private function getArticles($ranking){
        $topFiveIDs = $this->redis->zRange($ranking, -5, -1);
        $topFiveIDs = array_reverse($topFiveIDs);
        $articles = [];
        foreach ($topFiveIDs as $article_id){
            $article = $this->articleRepository->findOneById($article_id);
            array_push($articles, $article);
        }
        return $articles;
    }
}