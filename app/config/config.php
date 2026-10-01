<?php
return [
    'db' => [
        'host' => getenv('DB_HOST'),
        'port' => getenv('DB_PORT'),
        'name' => getenv('DB_DATABASE'),
        'user' => getenv('DB_USERNAME'),
        'pass' => getenv('DB_PASSWORD'),
    ],
    'templates_dir' => __DIR__ . '/../templates',
    'compile_dir'   => __DIR__ . '/../var/smarty/compile',
    'cache_dir'     => __DIR__ . '/../var/smarty/cache',
];
