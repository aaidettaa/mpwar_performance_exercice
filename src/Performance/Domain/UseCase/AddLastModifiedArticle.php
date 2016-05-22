<?php


namespace Performance\Domain\UseCase;

use DateTime;
use Performance\Infrastructure\HttpCache\HttpCache;

class AddLastModifiedArticle
{
    /**
     * @var $redis HttpCache
     **/
    private $httpCache;

    public function __construct(HttpCache $httpCache)
    {
        $this->httpCache = $httpCache;
    }

    public function execute($key, DateTime $date = null)
    {
        if (is_null($date)) {
            $date = new DateTime();
        }

        $this->httpCache->setLastModified($key, $date);
    }
}