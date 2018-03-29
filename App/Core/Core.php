<?php

namespace BotCore\Core;

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;

/**
 * summary
 */
class Core
{
    /**
     * signature for LINE API required
     *
     *
     */
    protected $pass_signature = true;

    /**
     * Inject channel access token from Messaging API
     *
     * @param your_unique_token
     *
     */
    private $channel_access_token = "#your_channel_access_token";

    /**
     * Inject Channel Secret token from messaging API
     *
     * @param your_unique_secret
     *
     */
    private $channel_secret = "#your_channel_secret";

    /**
     * add settings for Slim Framework
     *
     * @param $configs
     *
     */
    private $configs = [
	    'settings' => ['displayErrorDetails' => true],
	];

	/**
     * Prepare construct for class
     */
    public function __construct()
    {
        $this->run();
    }

    public function run()
    {
    	$httpClient = new CurlHTTPClient($this->channel_access_token);
        $bot  		= new LINEBot($httpClient, ['channelSecret' => $this->channel_secret]);
        
		$app = new \Slim\App($this->configs);

		try {
			$this->routeList( $app, $bot );
		} catch ( \Exception $e ) {
			echo $e->getMessage();
		}

		$app->run();
    }

    /**
     * registering all the controllers
     *
     * @param object $app from Slim Framework & object $bot from LineAPI
     *
     * @return data for launch the program
     *
     */
    public function routeList( $app = NULL, $bot = NULL)
    {
    	// Check if module not loaded
    	if ( $app == NULL || $bot = NULL) :
    		throw new \Exception("Module not loaded."); 	
    	endif;

    	new \BotCore\Controllers\Homepage($app);
    	new \BotCore\Controllers\Content($app, $bot);
    	new \BotCore\Controllers\Profile($app, $bot);
    	new \BotCore\Controllers\Webhook($app, $bot);
    	new \BotCore\Controllers\PushMessage($app, $bot);
    }
}
?>