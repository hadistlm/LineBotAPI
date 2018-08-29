<?php

namespace BotCore\Library;

use BotCore\Core\Core;

/**
 * Class Simsimi API
 */
class SimSimiLib extends Core
{
    /** @var string */
    protected $sending;

	/**
	 * Class Constructor for simsimi Class
	 */
    public function __construct($words = null){
    	$this->simsimiIntegration($words);
    }

    /**
     * Function to retrieve data from simsimi sanbox api
     *
     * @param String $message
     *
     * @return String return value from simsimi sandbox API
     */
    public function requestData( $message = "" ){
        $url       = 'http://sandbox.api.simsimi.com/request.p?key='.$this->$simsimi_key.'&lc='.$this->lc.'&ft=1.0&text='.$message;
        $json_data = file_get_contents($url);
        $data_msg  = json_decode($json_data,true);
        
        return $data_msg;
    }

    /**
     * Function to send data from controller to request Function
     *
     * @param String $event
     *
     * @return String value from function retrieve
     */
    public function simsimiIntegration( $event = "" ){
        $pesan  = str_replace(" ", "%20", $event['message']['text']);
        $simi   = $this->requestData( $pesan ); 

        if ((strpos($simi['response'], 'simi') !== false)) :
          $this->sending = str_replace("simi", "AusBOT", $simi['response']);   
        else:
          $this->sending = $simi['response'];
        endif;

        return $this->sending;
    }
}

?>