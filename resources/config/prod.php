<?php

date_default_timezone_set('Europe/Madrid');

$app['twig.path'] = array(__DIR__ . '/../templates');
$app['twig.options'] = [];

$app['db.options'] = [
    "driver"    => "pdo_mysql",
    "host"      => 'localhost',
    "user"      => 'your-user',
    "password"  => 'your-password',
    "dbname"    => 'mpwar_performance_blog',
    "charset"   => "utf8"
];

$app['redis.options'] = [
    'host'     => 'localhost',
    'port'     => 6379,
    'timeout'  => 0,
    'dbname'   => 'mpwar_performance_blog',
    'password' => ''
];

$app['imageAwsS3.options'] = [
    'key'     => 'your-key',
    'secret'     => 'your-secret',
    'region'  => 'eu-west-1',
    'version'   => 'latest',
    'bucketName' => 'your-bucket-name',
    'prefix' => 'optional-prefix'
];

$app['orm.proxies_dir'] = '/tmp/proxies';
$app['orm.auto_generate_proxies'] = true;
$app['orm.em.options'] = [
    "mappings" => [
        [
            "type" => "simple_yml",
            "namespace" => "Performance",
            "path" => __DIR__ . "/../../src/Performance/Infrastructure/Database/mappings",
        ],
    ]
];