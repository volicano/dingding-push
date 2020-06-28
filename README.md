<p align="center">
    <h1 align="center">dingding-push</h1>
</p>

## 介绍

dingding-push封装了钉钉发送群组消息验证、可以在群中@某个人或者是@所有人，提供简洁的 API 以供方便快速地调用钉钉发送消息提醒。

## 环境要求

- PHP 7.0+
- [Composer](https://getcomposer.org/)

## 安装

推荐的方式是通过composer 进行下载安装[composer](http://getcomposer.org/download/)。

在命令行执行
```
composer require volicano/dingding-push
```

或加入

```
"volicano/dingding-push":"dev-master"
```

到你的`composer.json`文件中的require段。

然后再.env文件里配置DINGDING_TOKEN和DINGDING_SECRET
```
DINGDING_TOKEN=xxxxxxxxxxxxxxxxxxxxxxxx
DINGDING_SECRET=xxxxxxxxxxxxxxxxxxxxxxxx
```
## 使用

第一种情况：只发消息
```php
use SendMessage\DingDing;

$dingding = new DingDing\DingDing();
$dingding->pushText('this is test！');
```
第二种情况：发消息并且指定群组中某个人(@某个人时候必须是@人的手机号)
```php
use SendMessage\DingDing;

$dingding = new DingDing\DingDing();
$dingding->pushText('this is test！','130********');
```
第三种情况：@所有人 调用方法
```php
use SendMessage\DingDing;

$dingding = new DingDing\DingDing();
$dingding->pushText('this is test！','',true);
```
## License

MIT
