<?php

$router->options('{path:.+}', function () {
    $method = [
        'method' => 'OPTIONS',
    ];
    $headers = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Max-Age' => '86400',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Token, Secret',
    ];

    return response()->json(
        $method,
        200,
        $headers
    );
});

$router->get('/', function () use ($router) {
    return view('index');
});
