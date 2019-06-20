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
    // 获取到二维码 uuid 后
    app('console')->log('qr_uuid: '.$qrUuid);
});

$app->observer->setLoginSuccessObserver(function ($user) {
    // 扫码登录成功后 print_r($user)
    app('console')->log('name:'.$user['name']);
});

$app->observer->setExitObserver(function () {
    // 程序结束运行前
    app('console')->log('Bye bye.');
});

$app->server->run();
