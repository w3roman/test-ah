<?php
return [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'mariadb',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'blog',
        'user' => getenv('DB_USER') ?: 'blog',
        'pass' => getenv('DB_PASSWORD') ?: 'secret',
    ],
    'templates_dir' => __DIR__ . '/../templates',
    'compile_dir'   => __DIR__ . '/../var/smarty/compile',
    'cache_dir'     => __DIR__ . '/../var/smarty/cache',
];