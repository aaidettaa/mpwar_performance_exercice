<?php
namespace Performance\Infrastructure\Session;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeSessionHandler;

class NativeRedisSessionHandler extends NativeSessionHandler
{
    /**
     * Constructor.
     *
     * @param string $savePath Path of redis server.
     */
    public function __construct($savePath = "")
    {
        if (!extension_loaded('redis')) {
            throw new \RuntimeException('PHP does not have "redis" session module registered');
        }

        if ("" === $savePath) {
            $savePath = ini_get('session.save_path');
        }

        if ("" === $savePath) {
            $savePath = "tcp://localhost:6379"; // guess path
        }

        ini_set('session.save_handler', 'redis');
        ini_set('session.save_path', $savePath);
    }
}