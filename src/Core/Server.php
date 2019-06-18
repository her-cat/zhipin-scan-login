<?php

namespace HerCat\ZhipinScanLogin\Core;


use HerCat\ZhipinScanLogin\Application;

class Server
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Server constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function run()
    {

    }

    public function getQrId()
    {}
}