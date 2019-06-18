<?php

namespace HerCat\ZhipinScanLogin\ServiceProviders;


use HerCat\ZhipinScanLogin\Application;
use HerCat\ZhipinScanLogin\Support\Http;

class HttpServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->singleton('http', function () use ($app) {
            return new Http($app);
        });
    }
}