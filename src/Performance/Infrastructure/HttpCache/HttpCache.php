<?php


namespace Performance\Infrastructure\HttpCache;


interface HttpCache
{
    public function setEtag($key, $value);
    public function getEtag($key);
}