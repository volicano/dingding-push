<p align="center">
    <h1 align="center">dingding-push</h1>
</p>

## 介绍

dingding-push封装了钉钉发送群组消息验证、可以在群中@某个人或者是@所有人，提供简洁的 API 以供方便快速地调用钉钉发送消息提醒。

## 环境要求

- PHP 7.0+
- [Composer](https://getcomposer.org/)

## 安装

```bash
composer require volicano/dingding-push
```

## 使用


```php
use SendMessage\DingDing;

$dingding = new DingDing\DingDing();
$dingding->pushText('this is test！');
```

## License

MIT
