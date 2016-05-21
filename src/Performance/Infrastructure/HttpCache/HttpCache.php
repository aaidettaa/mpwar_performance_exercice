<?php


namespace Performance\Infrastructure\HttpCache;
use \DateTime;

interface HttpCache
{
    const FORMAT_DATE =  'D, j M Y H:i:s T';
    const KEY_FOR_LAST_MODIFIED = "cache:last:modified:article:";

    public function getLastModified($key);
    public function setLastModified($key, DateTime $date);
}