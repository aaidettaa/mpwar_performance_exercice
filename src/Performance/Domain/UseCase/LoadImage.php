<?php

namespace Performance\Domain\UseCase;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use League\Flysystem\Filesystem;

class LoadImage
{
    const LOCAL_DIR = __DIR__ . "/../../../../uploads";
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $a_filesystem)
    {
        $this->filesystem = $a_filesystem;
    }

    public function execute($username,UploadedFile $img)
    {

        $img->move(self::LOCAL_DIR,'img_' .$username . ".jpeg");

        $this->filesystem->writeStream('uploads/img_' . $username . '.jpg',
            fopen(self::LOCAL_DIR . '/img_' . $username . '.jpg', 'w+'));

    }
}