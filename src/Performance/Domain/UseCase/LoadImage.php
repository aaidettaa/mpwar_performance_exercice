<?php

namespace Performance\Domain\UseCase;


use League\Flysystem\Filesystem;

class LoadImage
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $a_filesystem)
    {
        $this->filesystem = $a_filesystem;
    }

    public function execute($username, $img)
    {
        $this->filesystem->write('uploads/img_' . $username . '.jpg', file_get_contents($img));

        $this->filesystem->writeStream('uploads/img_' . $username . '.jpg',
            fopen('uploads/img_' . $username . '.jpg', 'r'));
    }
}