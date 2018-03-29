<?php

require __DIR__.'/vendor/autoload.php';

/**
 * Initialize Program
 */
class index
{
    /**
     * Construtor for class index
     * 
     */
    public function __construct()
    {
    	$this->run();   
    }

    public function run()
    {
    	new BotCore\Core\Core();
    }
}

/**
 * Up we go || Running the program
 */
$run = new index();
?>