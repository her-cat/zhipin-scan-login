<?php

/*
 * This file is part of the her-cat/zhipin-scan-login.
 *
 * (c) her-cat <hxhsoft@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\ZhipinScanLogin\Observers;

use HerCat\ZhipinScanLogin\Exceptions\InvalidArgumentException;
use HerCat\ZhipinScanLogin\Exceptions\ObserverNotFoundException;
use HerCat\ZhipinScanLogin\Application;

/**
 * Class Observer.
 *
 * @method setExitObserver($callback)
 * @method setQrCodeObserver($callback)
 * @method setLoginSuccessObserver($callback)
 */
class Observer
{
    /**
     * @var Application
     */
    protected $app;

    protected $callback;

    /**
     * Observer constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Trigger the observer.
     */
    public function trigger()
    {
        $args = func_get_args();

        if (is_callable($this->getCallaback())) {
            call_user_func_array($this->getCallaback(), $args);
        }
    }

    /**
     * The observer set a callback.
     *
     * @param $callback
     *
     * @throws InvalidArgumentException
     */
    protected function setCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Argument #1 must be a callback in:'.get_class($this));
        }

        $this->callback = $callback;
    }

    /**
     * Get observer callback.
     *
     * @return mixed
     */
    protected function getCallaback()
    {
        return $this->callback;
    }

    public function __call($name, $arguments)
    {
        $observerClass = lcfirst(str_replace('set', '', $name));

        if (!$observer = $this->app->{$observerClass}) {
            throw new ObserverNotFoundException("Observer: {$observerClass} not found.");
        }

        return $observer->setCallback($arguments[0]);
    }
}
