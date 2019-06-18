<?php

namespace HerCat\ZhipinScanLogin\ServiceProviders;


use HerCat\ZhipinScanLogin\Application;
use HerCat\ZhipinScanLogin\Console\Console;
use HerCat\ZhipinScanLogin\Console\QrCode;

class ConsoleServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->singleton('console', function () use ($app) {
            return new Console($app);
        });

        $app->singleton('qrCode', function () use ($app) {
            return new QrCode($app);
        });
    }
}