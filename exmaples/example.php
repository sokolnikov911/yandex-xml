<?php

require_once __DIR__ . '/../vendor/autoload.php';

use sokolnikov911\YandexXml\Client;

$yandexXmlUser = 'xmlkat';
$yandexXmlKey = '03.288092510:f4b921cca1e845e8299ef753838f934d';

$client = new Client($yandexXmlUser, $yandexXmlKey);

$result = $client
    ->action()
    ->get();

print_r($result);

