<?php

/*
 * This file is part of the her-cat/zhipin-scan-login.
 *
 * (c) her-cat <hxhsoft@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\ZhipinScanLogin\Console;

use Carbon\Carbon;
use HerCat\ZhipinScanLogin\Application;
use Monolog\Logger;

class Console
{
    /**
     * @var Application
     */
    protected $app;

    const INFO = 'INFO';

    const WARRING = 'WARRING';

    const ERROR = 'ERROR';

    const MESSAGE = 'MESSAGE';

    /**
     * Console constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * determine the console is windows or linux.
     *
     * @return bool
     */
    public static function isWin()
    {
        return 'WIN' === strtoupper(substr(PHP_OS, 0, 3));
    }

    /**
     * Print in terminal.
     *
     * @param $message
     * @param string $level
     * @param bool   $log
     */
    public function log($message, $level = self::INFO, $log = false)
    {
        if ($this->isOutput()) {
            if ($log && in_array($level, array_keys(Logger::getLevels()))) {
                $this->app->log->log($level, $message);
            }

            echo '['.Carbon::now()->toDateTimeString().']'."[{$level}] ".$message.PHP_EOL;
        }
    }

    /**
     * Is output message in terminal.
     *
     * @return bool|mixed
     */
    public function isOutput()
    {
        return $this->app->config['show_console_log'] ?? true;
    }
}
