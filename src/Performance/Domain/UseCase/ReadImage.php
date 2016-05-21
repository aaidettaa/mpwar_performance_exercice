<?php

namespace Performance\Domain\UseCase;


use League\Flysystem\Filesystem;

class ReadImage {

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $a_filesystem) {
        $this->filesystem = $a_filesystem;
    }

    public function execute($username) {

        // Check if a file exists
        $exists = $this->filesystem->has('path/to/file.txt');

        // Read file
        $contents = $this->filesystem->read('path/to/file.txt');

        return $contents;

    }
}