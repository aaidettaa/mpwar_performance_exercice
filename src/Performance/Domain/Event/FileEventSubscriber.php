<?php

namespace Performance\Domain\Event;

use Performance\Domain\UseCase\LoadImage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FileEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(FileEvents::UPLOADED_IMAGE => 'onUploadedImage');
    }

    public function onUploadedImage(FileEvent $event){
        $uploadImage = new LoadImage($event->getFileSystem());
        $uploadImage->execute($event->getUsername(), $event->getFile());
    }
}