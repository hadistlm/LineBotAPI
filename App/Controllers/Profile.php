<?php

namespace BotCore\controllers;

use BotCore\Core\Core;

/**
 * summary
 */
class Profile extends Core
{
    /**
     * summary
     */
    public function __construct($app, $bot)
    {
        $this->Profile($app, $bot);
    }

    public function Profile( $app = "", $bot = "", $userId = ""){
    	$app->get('/profile', function($req, $res) use ($bot){
		    // get user profile
		    $fetch  = $userId;
		    $result = $bot->getProfile($fetch);
		   
		    return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
		});
    }
}
?>