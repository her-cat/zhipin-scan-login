<?php

namespace HerCat\ZhipinScanLogin\ServiceProviders;


use HerCat\ZhipinScanLogin\Application;
use HerCat\ZhipinScanLogin\ExceptionHandler;

class ExceptionServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->singleton('exception', function () use ($app) {
            return new ExceptionHandler($app);
        });
    }
}