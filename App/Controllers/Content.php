<?php

namespace BotCore\controllers;

use BotCore\Core\Core;

/**
 * summary
 */
class Content extends Core
{
    /**
     * summary
     */
    public function __construct($app, $bot)
    {
        $this->getContent($app, $bot);
    }

    public function getContent($app, $bot)
    {
    	$app->get('/content/{messageId}', function($req, $res) use ($bot){
			$route     = $req->getAttribute('route');
		    $messageId = $route->getArgument('messageId');
		    $result    = $bot->getMessageContent($messageId);
		 
		    // set response
		    $res->write($result->getRawBody());
		 
		    return $res->withHeader('Content-Type', $result->getHeader('Content-Type'));
		});
    }
}
?>