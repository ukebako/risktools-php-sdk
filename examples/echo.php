<?php
require_once __DIR__.'/../vendor/autoload.php';

use RiskTools\Client;
use RiskTools\ServerException;
use RiskTools\ClientException;

// Read API credentials from config file
$config = include(__DIR__.'/../config.php');

// Initialize RiskTools client
$client = new Client($config['key'] ?? '', $config['url'] ?? '');

try {
    $result = $client->exec('/system/echo', [
        'foo' => 'bar',
        'some_timestamp' => '2019-09-01T12:00:00+03',
    ]);
}
catch (ServerException $e) {
    echo $e->getMessage();
}
catch (ClientException $e) {
    echo "Something went wrong...\n";
    throw ($e);
}

echo "*** API method response:\n";
print_r($result);

echo "*** RAW server response:\n";
echo $client->rawResponse;
echo "\n";
