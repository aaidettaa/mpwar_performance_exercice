<?php

namespace Performance\Domain\UseCase;


use League\Flysystem\Filesystem;

class ReadImage
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $a_filesystem)
    {
        $this->filesystem = $a_filesystem;
    }

    public function execute($username)
    {

        $exists = $this->filesystem->has('uploads/img_' . $username . '.jpg');

        $contents = $this->filesystem->read('uploads/img_' . $username . '.jpg');

        return $contents;

    }
}