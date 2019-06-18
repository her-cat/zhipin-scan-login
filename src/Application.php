<?php

/*
 * This file is part of the her-cat/zhipin-scan-login.
 *
 * (c) her-cat <hxhsoft@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\ZhipinScanLogin;

use Dotenv\Dotenv;
use HerCat\ZhipinScanLogin\ServiceProviders\ServiceProviderInterface;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;

/**
 * Class Application.
 *
 * @property \Illuminate\Config\Repository $config
 * @property \HerCat\ZhipinScanLogin\Support\Log $log
 * @property \HerCat\ZhipinScanLogin\Observers\Observer $observer
 * @property \HerCat\ZhipinScanLogin\Observers\ExitObserver $exitObserver
 * @property \HerCat\ZhipinScanLogin\ExceptionHandler $exception
 */
class Application extends Container
{
    /**
     * Service Providers.
     *
     * @var array
     */
    protected $providers = [
        ServiceProviders\LogServiceProvider::class,
        ServiceProviders\ObserverServiceProvider::class,
        ServiceProviders\ExceptionServiceProvider::class,
    ];

    public function __construct()
    {
        $this->initializeConfig();

        (new Kernel($this))->bootstrap();

        self::setInstance($this);
    }

    /**
     * Initialize config.
     */
    private function initializeConfig()
    {
        $path = realpath(__DIR__.'/../');

        $dotenv = Dotenv::create($path);

        $this->config = new Repository($dotenv->load());

        $this->config->set('path', $path);
    }

    /**
     * Register service providers.
     */
    public function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    /**
     * @param ServiceProviderInterface $instance
     */
    private function register(ServiceProviderInterface $instance)
    {
        $instance->register($this);
    }
}
