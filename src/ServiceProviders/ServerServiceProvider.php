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
