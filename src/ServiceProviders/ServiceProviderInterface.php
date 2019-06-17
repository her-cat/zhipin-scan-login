<?php

namespace HerCat\ZhipinScanLogin\ServiceProviders;


use HerCat\ZhipinScanLogin\Application;

interface ServiceProviderInterface
{
    public function register(Application $app);
}