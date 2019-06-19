<?php

/*
 * This file is part of the her-cat/zhipin-scan-login.
 *
 * (c) her-cat <hxhsoft@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\ZhipinScanLogin\Support;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use HerCat\ZhipinScanLogin\Application;

class Http
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var FileCookieJar
     */
    protected $cookieJar;

    /**
     * Http constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->cookieJar = new FileCookieJar($app->config['cookie_file'], true);
        $this->client = new HttpClient(['cookies' => $this->cookieJar]);
    }

    /**
     * @param HttpClient $client
     *
     * @return $this
     */
    public function setClient(HttpClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return HttpClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $url
     * @param array $options
     * @param bool  $format
     *
     * @return bool|string
     */
    public function get($url, array $options = [], $format = true)
    {
        return $this->request($url, 'GET', $options, $format);
    }

    /**
     * @param $url
     * @param array $query
     * @param bool  $format
     *
     * @return bool|string
     */
    public function post($url, array $query = [], $format = true)
    {
        $key = is_array($query) ? 'form_params' : 'body';

        return $this->request($url, 'POST', [$key => $query], $format);
    }

    /**
     * @param $url
     * @param string $method
     * @param array  $options
     * @param bool   $format
     * @param bool   $retry
     *
     * @return bool|mixed|string
     *
     * @throws GuzzleException
     */
    public function request($url, $method = 'GET', $options = [], $format = true, $retry = false)
    {
        try {
            $options = array_merge(['timeout' => 30, 'verify' => false], $options);

            $response = $this->getClient()
                ->request($method, $url, $options)
                ->getBody()
                ->getContents();

            $this->cookieJar->save($this->app->config['cookie_file']);

            return $format ? \json_decode($response, true) : $response;
        } catch (\Exception $e) {
            if ($retry) {
                return $this->request($url, $method, $options, $format, false);
            }

            return false;
        }
    }
}
