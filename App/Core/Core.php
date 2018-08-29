<?php

namespace BotCore\Core;

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;

/**
 * summary
 */
class Core
{
    /**
     * signature for LINE API required
     *
     * @param true || false
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
     * Inject simsimi key
     *
     * @param your_unique_secret
     *
     */
    protected $simsimi_key = "#your_simsimi_api_key";

    /**
     * Inject simsimi language
     *
     * @param your language desire
     * 
     * @default "id"
     *
     * @reff http://developer.simsimi.com/lclist
     */
    protected $lc = "id";    

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
    	if ( $app == NULL || $bot == NULL) :
    		throw new \Exception("Module not loaded."); 	
    	endif;

    	new \BotCore\Controllers\Homepage($app);
    	new \BotCore\Controllers\Content($app, $bot);
    	new \BotCore\Controllers\Profile($app, $bot);
    	new \BotCore\Controllers\Webhook($app, $bot, $this->pass_signature);
    	new \BotCore\Controllers\PushMessage($app, $bot);
    }
}
?>