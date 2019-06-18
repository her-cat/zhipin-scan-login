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

/**
 * Class Kernel.
 */
class Kernel
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Kernel constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bootstrap()
    {
        $this->checkEnvironment();
        $this->registerProviders();
        $this->bootstrapException();
        $this->prepareSession();
        $this->initializeConfig();
    }

    /**
     * Check the running environment.
     */
    private function checkEnvironment()
    {
        if ('cli' !== PHP_SAPI) {
            die('Please execute script in terminal!');
        }

        if (version_compare(PHP_VERSION, '7.0.0', '<')) {
            die('Application have to run under php 7! Current version is :'.PHP_VERSION);
        }

        $mustExtensions = ['gd', 'fileinfo', 'SimpleXML'];

        $diff = array_diff($mustExtensions, get_loaded_extensions());

        if ($diff) {
            die('Running script failed! please install extensions: '.PHP_EOL.implode("\n", $diff).PHP_EOL);
        }
    }

    /**
     * Register service providers.
     */
    private function registerProviders()
    {
        $this->app->registerProviders();
    }

    private function prepareSession()
    {
        $this->app->config['session'] = bin2hex(random_bytes(3));
    }

    /**
     * Bootstrap exception.
     */
    private function bootstrapException()
    {
        error_reporting(-1);
        set_error_handler([$this->app->exception, 'handleError']);
        set_exception_handler([$this->app->exception, 'handleException']);
        register_shutdown_function([$this->app->exception, 'handleShutdown']);
    }

    private function initializeConfig()
    {
        $this->app->config['storage_path'] = $this->app->config['path'].'/storage';

        if (!is_dir($this->app->config['storage_path'].'/cookies')) {
            mkdir($this->app->config['storage_path'].'/cookies', 0755, true);
        }

        $file = sprintf('%s/cookies/%s', $this->app->config['storage_path'], $this->app->config['session']);

        $this->app->config['cookie_file'] = $file;
    }
}
