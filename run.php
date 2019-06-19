<?php

/*
 * This file is part of the her-cat/zhipin-scan-login.
 *
 * (c) her-cat <hxhsoft@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once './vendor/autoload.php';

use HerCat\ZhipinScanLogin\Application;

$app = new Application();

$app->observer->setQrCodeObserver(function ($qrUuid) {
    app('console')->log('qr_uuid: '.$qrUuid);
});

$app->observer->setExitObserver(function () {
    app('console')->log('Bye bye.');
});

$app->observer->setLoginSuccessObserver(function ($user) {
    app('console')->log('name:'.$user['name']);
});

$app->server->run();
