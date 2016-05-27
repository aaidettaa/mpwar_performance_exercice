<?php

namespace Performance\Domain\UseCase;

use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Performance\Domain\Author;
use Performance\Domain\AuthorRepository;
use Performance\Domain\Event\FileEvent;
use Performance\Domain\Event\FileEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use League\Flysystem\Filesystem;

class SignUp
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    private $eventDispatcher;

    private $filesystem;

    public function __construct(AuthorRepository $authorRepository, EventDispatcherInterface $eventDispatcher,
                                Filesystem $filesystem)
    {
        $this->authorRepository = $authorRepository;
        $this->eventDispatcher  = $eventDispatcher;
        $this->filesystem       = $filesystem;
    }

    public function execute($username, $password,UploadedFile $uploadedFile)
    {
        $author = Author::register($username, $password);
        $this->authorRepository->save($author);
        $uploadedFileEvent = new FileEvent($this->filesystem,$uploadedFile,$username);
        $this->eventDispatcher->dispatch(FileEvents::UPLOADED_IMAGE, $uploadedFileEvent);
    }
}