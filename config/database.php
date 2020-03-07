<?php

return [
    'fetch' => PDO::FETCH_CLASS,
    'default' => env('DB_QUEUE_CONNECTION', 'default_mssql'),
    'connections' => [
        'default_mssql' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => env('DB_PREFIX', ''),
        ],
    ],
    'migrations' => 'migrations',
];
