<?php

namespace Performance\Domain\UseCase;

use Performance\Domain\ArticleRepository;
use Performance\Domain\UseCase\GetTopFive;
use Redis;

class ListArticles
{
    /**
     * @var ArticleRepository
     */
	private $articleRepository;

    public function __construct(ArticleRepository $articleRepository) {
        $this->articleRepository = $articleRepository;
    }

    public function execute(Redis $redis, $userId) {
        $getTopFive = new GetTopFive($redis, $this->articleRepository);
        if($userId == null){
            return $getTopFive->getGlobally();
        }

        return $getTopFive->getByUser($userId);

    }
}