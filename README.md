<h1 align="center"> Boss 直聘扫码登录 </h1>

<p align="center"> 🔓 PHP 实现 Boss 直聘扫码登录.</p>

[![StyleCI build status](https://github.styleci.io/repos/192315655/shield)](https://github.styleci.io/repos/192315655)
![GitHub](https://img.shields.io/github/license/her-cat/zhipin-scan-login.svg)


## 安装

```shell
$ git clone https://github.com/her-cat/zhipin-scan-login.git
$ cd zhipin-scan-login
$ composer install
```

## 使用

运行 `php run.php` 查看效果。 

run.php：

```php
use HerCat\ZhipinScanLogin\Application;

$app = new Application();

$app->observer->setQrCodeObserver(function ($qrUuid) {
    // 获取到二维码 uuid 后
    app('console')->log('qr_uuid: '.$qrUuid);
});

$app->observer->setLoginSuccessObserver(function ($user) {
    // 扫码登录成功后 print_r($user)
    app('console')->log('name:'.$user['name']);
});

$app->observer->setExitObserver(function () {
    // 程序结束运行前
    app('console')->log('Bye bye.');
});

$app->server->run();
```

## License

MIT