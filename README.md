PHP Yandex.XML search API client
=====================================

[![Latest Stable Version](https://poser.pugx.org/sokolnikov911/yandex-xml/v/stable)](https://packagist.org/packages/sokolnikov911/yandex-xml)
[![Total Downloads](https://poser.pugx.org/sokolnikov911/yandex-xml/downloads)](https://packagist.org/packages/sokolnikov911/yandex-xml)
[![Latest Unstable Version](https://poser.pugx.org/sokolnikov911/yandex-xml/v/unstable)](https://packagist.org/packages/sokolnikov911/yandex-xml)
[![License](https://poser.pugx.org/sokolnikov911/yandex-xml/license)](https://packagist.org/packages/sokolnikov911/yandex-xml)
[![composer.lock](https://poser.pugx.org/sokolnikov911/yandex-xml/composerlock)](https://packagist.org/packages/sokolnikov911/yandex-xml)


Russian version of README you can find here: [README_RU.md](https://github.com/sokolnikov911/yandex-xml/blob/master/README_RU.md).

PHP Yandex.XML search API client


## Examples

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

## Installing

Install composer. Follow instructions on download page: https://getcomposer.org/download/

Next, run the Composer command to install the latest stable version of **yandex-xml**

```bash
php composer.phar require sokolnikov911/yandex-xml
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

You can then later update **yandex-xml** using composer:

 ```bash
composer.phar update
 ```
 
 
## Requirements

This client requires at least PHP7 (yeahh, type hinting!) and [Guzzle](https://github.com/guzzle/guzzle) 7.


## License

This library has licensed under the MIT License.