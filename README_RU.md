PHP клиент для API поииска Yandex.XML
=====================================

|  |  |
|----------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
|  | [![Latest Stable Version](https://poser.pugx.org/sokolnikov911/yandex-xml/v/stable)](https://packagist.org/packages/sokolnikov911/yandex-xml) [![Total Downloads](https://poser.pugx.org/sokolnikov911/yandex-xml/downloads)](https://packagist.org/packages/sokolnikov911/yandex-xml) [![Latest Unstable Version](https://poser.pugx.org/sokolnikov911/yandex-xml/v/unstable)](https://packagist.org/packages/sokolnikov911/yandex-xml) [![composer.lock](https://poser.pugx.org/sokolnikov911/yandex-xml/composerlock)](https://packagist.org/packages/sokolnikov911/yandex-xml) [![PHPPackages Rank](http://phppackages.org/p/sokolnikov911/yandex-xml/badge/rank.svg)](http://phppackages.org/p/sokolnikov911/yandex-xml) [![PHPPackages Referenced By](http://phppackages.org/p/sokolnikov911/yandex-xml/badge/referenced-by.svg)](http://phppackages.org/p/sokolnikov911/yandex-xml) |
| Travis CI | [![Build Status](https://travis-ci.org/sokolnikov911/yandex-xml.svg?branch=master)](https://travis-ci.org/sokolnikov911/yandex-xml) |
| Scrutinizer CI | [![Build Status](https://scrutinizer-ci.com/g/sokolnikov911/yandex-xml/badges/build.png?b=master)](https://scrutinizer-ci.com/g/sokolnikov911/yandex-xml/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sokolnikov911/yandex-xml/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sokolnikov911/yandex-xml/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/sokolnikov911/yandex-xml/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sokolnikov911/yandex-xml/?branch=master) |


PHP клиент для API поииска Yandex.XML.


## Примеры использования

```php
$yandexXmlUser = 'xmluser';
$yandexXmlKey = '12.12344:123443543534';

$client = new Client($yandexXmlUser, $yandexXmlKey);

$result = $client
    ->query('search query')
    ->page(2)
    ->l10n(Client::L10N_UKRAINIAN)
    ->get();
```

## Установка

Устанавливаем Composer. Свежая инструкция тут: https://getcomposer.org/download/


Потом, запускаем команду композера для установки последней стабильной версии **yandex-xml**

```bash
php composer.phar require sokolnikov911/yandex-xml
```

После установки подключаем автолоадер композера:

```php
require 'vendor/autoload.php';
```

Позже вы можете обновлять **yandex-xml** используя композер:

 ```bash
composer.phar update
 ```
 
 
## Требования

Этот API-клиент разработан для PHP7 (используется строгая типизация) и [Guzzle](https://github.com/guzzle/guzzle) 7.


## Лицензия

This library has licensed under the MIT License.