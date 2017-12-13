<?php
require __DIR__.'/vendor/autoload.php';

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;
 
	// set false for production
	$pass_signature = true;
	 
	// set LINE channel_access_token and channel_secret
	$channel_access_token = "ZaZLTEK1MTqDnpcTAMvtw9WlFdSoh5GgrHbXGR/2odKDVORCU/WHLu25dwsOOTJ+oBmusbPuAQ+CHbq9NJLbjZDUrbt8gpOea2KuNBdt6+m6XaYb1RZLLOQFWQ9DESoeW6GvkSh1M8e2Y41sCbIYJAdB04t89/1O/w1cDnyilFU=";
	$channel_secret = "afe04fa15dd4ae5b1fbb74948fb22cd8";
	 
	// inisiasi objek bot
	$httpClient = new CurlHTTPClient($channel_access_token);
	$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);
	 
	$configs =  [
	    'settings' => ['displayErrorDetails' => true],
	];
	$app = new Slim\App($configs);
	 
	// buat route untuk url homepage
	$app->get('/', function($req, $res)
	{
	  echo "Welcome  at Slim Framework";
	});
 
	// buat route untuk webhook
	$app->post('/webhook', function ($request, $response) use ($bot, $pass_signature)
	{
	    // get request body and line signature header
	    $body        = file_get_contents('php://input');
	    $signature = isset($_SERVER['HTTP_X_LINE_SIGNATURE']) ? $_SERVER['HTTP_X_LINE_SIGNATURE'] : '';
	 
	    // log body and signature
	    file_put_contents('php://stderr', 'Body: '.$body);
	 
	    if($pass_signature === false)
	    {
	        // is LINE_SIGNATURE exists in request header?
	        if(empty($signature)){
	            return $response->withStatus(400, 'Signature not set');
	        }
	 
	        // is this request comes from LINE?
	        if(! SignatureValidator::validateSignature($body, $channel_secret, $signature)){
	            return $response->withStatus(400, 'Invalid signature');
	        }
	    }
	 
	    $data = json_decode($body, true);
	    if (is_array($data['events'])) {
	    	foreach ($data['events'] as $event)
		    {
		        if ($event['type'] == 'message')
		        {
		            if($event['message']['type'] == 'text')
		            {
		                // Send balik
		                $result = $bot->replyText($event['replyToken'], $event['message']['text']);
		 
		                return $response->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
		            }
		        }
		    }
	    }
	 
	});
 
$app->run();

?>