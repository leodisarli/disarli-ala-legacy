<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services
     * @return void
     */
    public function register()
    {
        $this->app->bind('Aws\S3\S3Client', function () {
            $config = [
                'credentials' => [
                    'key' => env('AWS_S3_ACCESS_KEY_ID', ''),
                    'secret' => env('AWS_S3_SECRET_ACCESS_KEY', ''),
                ],
                'version' => 'latest',
                'region' => 'us-east-1',
            ];
            return new \Aws\S3\S3Client($config);
        });
    }
}
