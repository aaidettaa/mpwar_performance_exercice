<?php

namespace Performance\Domain\UseCase;


use League\Flysystem\Filesystem;

class LoadImage {

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $a_filesystem) {
        $this->filesystem = $a_filesystem;
    }

    public function execute($username, $img) {
        // Write to file
        $this->filesystem->write('path/to/file.txt', 'contents');

        // Write to image
        $this->filesystem->write('path/to/image1.png', file_get_contents('local_path/to/image.png'));
        $this->filesystem->writeStream('path/to/image1.png', fopen('local_path/to/image.png', 'r'));

    }
}