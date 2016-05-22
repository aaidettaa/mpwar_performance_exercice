<?php


namespace Performance\Infrastructure\HttpCache;
use \DateTime;
use Performance\Domain\Article;

interface HttpCache
{
    const FORMAT_DATE =  'D, j M Y H:i:s T';
    const KEY_FOR_LAST_MODIFIED = "cache:last:modified:article:";
    const RESPONSE_ARTICLE = "cache:response:article:";

    public function getLastModified($key);
    public function setLastModified($key, DateTime $date);
    public function setResponse(Article $article);
    public function getArticle($articleId);
}