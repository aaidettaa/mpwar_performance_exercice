<?php


namespace Performance\Infrastructure\HttpCache;

use Performance\Domain\Article;
use Performance\Domain\Author;
use Redis;
use \DateTime;

class HttpCacheWithRedis implements HttpCache
{
    const INDEX_ID              = 0;
    const INDEX_TITLE           = 1;
    const INDEX_CONTENT         = 2;
    const INDEX_DATE            = 3;
    const INDEX_TAGS            = 4;
    const INDEX_AUTHOR_ID       = 5;
    const INDEX_AUTHOR_USERNAME = 6;


    private $redis;
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }


    public function getLastModified($key)
    {
       $lastModified =  $this->redis->get($key);
       return new DateTime($lastModified);
    }

    public function setLastModified($key, \DateTime $dateTime){
        $dateFormated = $dateTime->format(self::FORMAT_DATE);
        $this->redis->set($key,$dateFormated);
    }

    public function setResponse(Article $article)
    {
        $key = self::RESPONSE_ARTICLE . $article->getId();
        $this->redis->del($key);
        $this->redis->rPush( $key, $article->getId(), $article->getTitle(), $article->getContent(),
                            $article->getDate(), $article->getTags(), $article->getAuthor()->getId(),
                            $article->getAuthor()->getUsername()
                            );
    }

    public function getArticle($articleId)
    {

        $key = self::RESPONSE_ARTICLE . $articleId;
        $redisArticle = $this->redis->lRange($key,0,-1);
        if($redisArticle == null){
            return null;
        }

        $authorArray['id'] = $redisArticle[self::INDEX_AUTHOR_ID];
        $authorArray['username'] = $redisArticle[self::INDEX_AUTHOR_USERNAME];
        $authorArray['password'] = "";

        $author  = Author::fromArray($authorArray);

        $article = Article::create($redisArticle[self::INDEX_ID],
            $redisArticle[self::INDEX_TITLE],
            $redisArticle[self::INDEX_CONTENT],
            $author,
            $redisArticle[self::INDEX_DATE]
        );
        return $article;
    }
}