<?php


namespace Performance\Infrastructure\HttpCache;
use \DateTime;

interface HttpCache
{
    const FORMAT_DATE =  'D, j M Y H:i:s T';

    public function getLastModified($key);
    public function setLastModified($key, DateTime $date);
}