<?php

require_once(__DIR__ . '/_bootstrap.php');

use RingCentral\SDK\SDK;

/*$credentials = require(__DIR__ . '/_credentials.php');*/
$credentials = array(
				'appKey'=>'J8CSRYUcSguMt2c0mEWPpg',
				'appSecret'=>'ytLCkQwpQ_O9TYqkq3glWAfsDQ-zIMT4mMZIH_PN15Ow',
				'server'=>'https://platform.devtest.ringcentral.com',
				'username'=>'+17322764469',
				'extension'=>'101',
				'password'=>'!Q2w3e4r',
				'mobileNumber'=>'+14159025901'
				);

// Create SDK instance 

$rcsdk = new SDK($credentials['appKey'], $credentials['appSecret'], $credentials['server'], 'Demo', '1.0.0');

$platform = $rcsdk->getPlatform();

// Authorize

$platform->authorize($credentials['username'], $credentials['extension'], $credentials['password'], true);

// Make a call
$from = "+14158058813";
if(isset($_GET['f']) && !empty($_GET['f'])){
	$from = $_GET['f'];
}
$response = $platform->post('/account/~/extension/~/ringout', null, array(
    'from' => array('phoneNumber' => $from),
    'to'   => array('phoneNumber' => $_GET['p'])
));

$json = $response->getJson();

$lastStatus = $json->status->callStatus;

// Poll for call status updates

while ($lastStatus == 'InProgress') {

    $current = $platform->get($json->uri);
    $currentJson = $current->getJson();
    $lastStatus = $currentJson->status->callStatus;
    print 'Status: ' . json_encode($currentJson->status) . PHP_EOL;

    sleep(2);

}

print 'Done.' . PHP_EOL;
