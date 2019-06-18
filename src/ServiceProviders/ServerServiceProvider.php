<?php

namespace HerCat\ZhipinScanLogin\ServiceProviders;


use HerCat\ZhipinScanLogin\Application;
use HerCat\ZhipinScanLogin\Core\Server;

class ServerServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->singleton('server', function () use ($app) {
            return new Server($app);
        });
    }
}