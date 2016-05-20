<?php


namespace Performance\Infrastructure\HttpCache;


interface HttpCache
{
    public function setEtag($key, $value);
    public function getEtag($key);
    public function setResponseToCache($key, $value);
    public function generateEtag($value);
}