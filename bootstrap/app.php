<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
}

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();
$app->withEloquent();

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
    'authJob' => App\Http\Middleware\AuthenticateJob::class,
]);

$app->middleware([
    App\Http\Middleware\CorsMiddleware::class
]);

$app->register(App\Providers\AppServiceProvider::class);

require __DIR__.'/list_routes.php';

foreach ($listRoutes as $namespaceRoute => $fileRoute) {
    $app->router->group([
        'namespace' => $namespaceRoute,
    ], function ($router) use ($fileRoute) {
        $file = __DIR__.'/../routes/'.$fileRoute.'_routes.php';
        if (file_exists($file)) {
            require $file;
        }
    });
}

$app->configure('amazon');
$app->configure('externalapi');
$app->configure('token');
$app->configure('version');

return $app;
