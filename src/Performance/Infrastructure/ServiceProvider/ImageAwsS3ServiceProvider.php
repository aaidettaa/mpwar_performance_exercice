<?php

namespace Performance\Infrastructure\ServiceProvider;


use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Config;
use League\Flysystem\Filesystem;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ImageAwsS3ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['imageAwsS3'] = function () use ($pimple) {

            $options = $pimple['imageAwsS3.options'];

            $client = new S3Client([
                'credentials' => [
                    'key'    => $options['key'],
                    'secret' => $options['secret'],
                ],
                'region' => $options['region'],
                'version' => $options['version'],
            ]);

            $aws3adapter = new AwsS3Adapter($client, $options['bucketName'], $options['prefix']);


            $filesystem = new Filesystem($aws3adapter);

            return $filesystem;
        };
    }
}