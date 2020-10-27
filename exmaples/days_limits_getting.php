<?php

require_once __DIR__ . '/../vendor/autoload.php';

use sokolnikov911\YandexXml\Client;

$yandexXmlUser = 'xmluser';
$yandexXmlKey = '12.12344:123443543534';

$client = new Client($yandexXmlUser, $yandexXmlKey);

$result = $client
    ->action()
    ->get();

print_r($result);

