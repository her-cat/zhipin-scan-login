<?php

/*
 * This file is part of the her-cat/zhipin-scan-login.
 *
 * (c) her-cat <hxhsoft@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\ZhipinScanLogin\ServiceProviders;

use HerCat\ZhipinScanLogin\Application;
use HerCat\ZhipinScanLogin\Support\Log;
use Monolog\Handler\RotatingFileHandler;

class LogServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->singleton('log', function () use ($app) {
            $log = new Log('app');

            $handler = new RotatingFileHandler(
                $app->config['storage_path'].'/logs/app.log',
                7
            );

            return $log->pushHandler($handler);
        });
    }
}
