<?php

/*
 * This file is part of the her-cat/zhipin-scan-login.
 *
 * (c) her-cat <hxhsoft@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use HerCat\ZhipinScanLogin\Application;
use Illuminate\Contracts\Container\BindingResolutionException;

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param null $abstract
     * @param array $parameters
     *
     * @return mixed
     * @throws BindingResolutionException
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Application::getInstance();
        }

        return empty($parameters) ?
            Application::getInstance()->make($abstract) :
            Application::getInstance()->makeWith($abstract, $parameters);
    }
}
