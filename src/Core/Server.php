<?php

/*
 * This file is part of the her-cat/zhipin-scan-login.
 *
 * (c) her-cat <hxhsoft@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\ZhipinScanLogin\Core;

use HerCat\ZhipinScanLogin\Application;
use HerCat\ZhipinScanLogin\Console\Console;
use HerCat\ZhipinScanLogin\Exceptions\FetchQrUuidException;
use HerCat\ZhipinScanLogin\Exceptions\FetchUserException;
use HerCat\ZhipinScanLogin\Exceptions\LoginFailedException;

class Server
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Server constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function run()
    {
        $this->getQrUuid();
        $this->showQrCode();
        $this->waitForLogin();
        $this->getUser();
    }

    /**
     * Get QR uuid.
     *
     * @return mixed
     *
     * @throws FetchQrUuidException
     */
    public function getQrUuid()
    {
        $url = 'https://login.zhipin.com/wapi/zppassport/captcha/randkey';

        $response = $this->app->http->post($url, [
            'form_params' => ['pk' => 'cpc_user_sign_up']
        ]);

        if (!isset($response['zpData']['qrId'])) {
            throw new FetchQrUuidException('fetch QR uuid faild.');
        }

        $qrUuid = $response['zpData']['qrId'];

        $this->app->qrCodeObserver->trigger($qrUuid);

        $this->app->config['server.qr_uuid'] = $qrUuid;

        return $qrUuid;
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return string
     */
    public function getQrCodeImageUrl($qrUuid = null, $width = 200, $height = 200)
    {
        $qrUuid = !is_null($qrUuid) ?: $this->app->config['server.qr_uuid'];

        $url = "https://login.zhipin.com/wapi/zpweixin/qrcode/getqrcode?";

        $query = http_build_query([
            'content' => $qrUuid,
            'w' => $width,
            'h' => $height,
        ]);

        return $url.$query;
    }

    /**
     * Show login qrCode on Console.
     */
    public function showQrCode()
    {
        $this->app->qrCode->show($this->app->config['server.qr_uuid']);
    }

    /**
     * @throws LoginFailedException
     */
    public function waitForLogin()
    {
        $retryTime = 1;

        $this->app->console->log('please scan the qrCode with Boss zhipin App.');
        $this->app->console->log($this->getQrCodeImageUrl());

        while ($retryTime > 0) {
            $url = 'https://login.zhipin.com/scan?';

            $query = http_build_query([
                'uuid' => $this->app->config['server.qr_uuid'],
                '_' => time(),
            ]);

            $response = $this->app->http->get($url.$query);

            if (isset($response['code']) && $response['code'] === 17) {
                $this->app->console->log($response['message'], Console::ERROR);
                break;
            }

            if (isset($response['allweb'])) {
                $this->app->console->log('scan qrcode success.');
                return;
            };

            $this->app->console->log('unscanned QRcode.');

            $retryTime--;
        }

        throw new LoginFailedException('login failed.');
    }

    /**
     * Get user.
     *
     * @return array
     *
     * @throws FetchUserException
     */
    public function getUser()
    {
        $url = 'https://www.zhipin.com/wapi/zpgeek/resume/infodata.json';

        $response = $this->app->http->get($url);

        $user = $response['zpData'] ?? false;
        if (!$user) {
            throw new FetchUserException('fetch user info failed.');
        }

        $this->app->loginSuccessObserver->trigger($user);

        return $user;
    }
}
