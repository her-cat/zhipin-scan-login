<?php

use \HerCat\ZhipinScanLogin\Application;

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param null $abstract
     * @param array $parameters
     *
     * @return mixed
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Application::getInstance();
        }

        return empty($parameters) ?
            Application::getInstance()->make($abstract):
            Application::getInstance()->makeWith($abstract, $parameters);
    }
}