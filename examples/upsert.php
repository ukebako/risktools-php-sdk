<?php
require_once __DIR__.'/../vendor/autoload.php';

use Risktools\Client;
use Risktools\ServerException;
use Risktools\ClientException;

function render_apps()
{
    $apps = [];
    for ($i=0; $i<10000; $i++) {
        $apps[] = [
            'id' => uniqid(),
            'user_id' => '9876',
            'social_number' => '8889899899',
            'phone' => '380501112233',
            'passport_number' => 'AA76545',
            'passport_date' => '2003-01-02',
            'last_name' => 'Мартынчук',
            'first_name' => 'Виктор',
            'other_name' => 'Петрович',
            'birth_date' => '1990-01-01',
            'gender_id' => 1,
            'loan_amount' => 1200,
            'loan_days' => 14,
            'applied_at' => '2018-03-12T00:00:00+03:00',
            'ip' => '195.12.22.23',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
        ];
    }
    return $apps;
}

$config = include(__DIR__.'/../config.php');
$client = new Client($config['key'] ?? '', $config['url'] ?? '');

try {
    $result = $client->exec('/data/upsert', [
        'index_group' => 'Application',
        'index' => 'apps',
        'documents' => render_apps(),
    ]);
}
catch (ServerException $e) {
    echo $e->getMessage();
}
catch (ClientException $e) {
    echo "Something went wrong...\n";
    throw ($e);
}

print_r($result);
