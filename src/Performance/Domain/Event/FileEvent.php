<?php


namespace Performance\Domain\Event;


use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use League\Flysystem\Filesystem;

class FileEvent extends Event
{
    private $file;
    private $filesystem;
    private $username;
    public function __construct(Filesystem $filesystem, UploadedFile $file, $username)
    {
        $this->username     = $username;
        $this->file         = $file;
        $this->filesystem   = $filesystem;
    }

    public function getFile(){
        return $this->file;
    }

    public function getFileSystem(){
        return $this->filesystem;
    }

    public function getUsername(){
        return $this->username;
    }
}