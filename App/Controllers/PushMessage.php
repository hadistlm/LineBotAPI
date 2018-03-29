<?php

namespace BotCore\Controllers;

use BotCore\Core\Core;

/**
 * summary
 */
class PushMessage extends Core
{
    /**
     * summary
     */
    public function __construct($app, $bot)
    {
        $this->pushmessage($app, $bot);
    }

    public function pushmessage( $app = "", $bot = "" ){
    	$app->get('/pushmessage', function($req, $res) use ($bot){
			$userid = '';
			$textMessageBuilder = new TextMessageBuilder('Halo, ini test doang');
			$result = $bot->pushMessage($userid, $textMessageBuilder);

			return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
		});
    }
}
?>