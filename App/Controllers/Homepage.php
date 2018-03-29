<?php

namespace BotCore\Controllers;

use BotCore\Core\Core;

/**
 * summary
 */
class Homepage extends Core
{
    /**
     * summary
     */
    public function __construct($app){
        $this->run($app);
    }

    public function run($app)
    {
    	$app->get('/', function($req, $res){
			//$filename = $_SERVER['DOCUMENT_ROOT']."/database/database.txt";

			//$file = fopen($filename, "a+") or die("Unable to open file!");
			//echo fwrite($file,"Hello World. Testing!");
			//echo fgets($file);
			//fclose($file);

    		echo 'Sedang Tidak Menerima Tamu';
		});
    }
}
?>