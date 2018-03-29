<?php

namespace BotCore\Controllers;

use BotCore\Core\Core;

/**
 * summary
 */
class Webhook extends Core
{
    /**
     * summary
     */
    public function __construct($app, $bot)
    {
    	$this->webhook($app, $bot);
    }

    public function webhook($app, $bot)
    {
    	// get request body and line signature header
        $body        = file_get_contents('php://input');
        $signature = isset($_SERVER['HTTP_X_LINE_SIGNATURE']) ? $_SERVER['HTTP_X_LINE_SIGNATURE'] : '';
     
        // log body and signature
        file_put_contents('php://stderr', 'Body: '.$body);
     
        if($this->pass_signature === false)
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

        return $this->events($data);
    }

    public function events($data)
    {
        if (is_array($data['events'])) {
            foreach ($data['events'] as $event)
            {
                if ($event['type'] == 'message')
                {
                    if ($event['source']['type'] == "user") {
                        if($event['message']['type'] == 'text'){
                            // Send balik
                            $user = $bot->getProfile($event['source']['userId']);
                            $result = $bot->replyText($event['replyToken'], $event['message']['text']);
             
                            return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
                        }else if( ($event['message']['type'] == 'image' || $event['message']['type'] == 'video') or
                                ($event['message']['type'] == 'audio' || $event['message']['type'] == 'file')){
                            $basePath  = $request->getUri()->getBaseUrl();
                            $contentURL  = $basePath."/content/".$event['message']['id'];
                            $contentType = ucfirst($event['message']['type']);
                            $result = $bot->replyText($event['replyToken'], $contentType. " yang Anda kirim bisa diakses dari link:\n " . $contentURL);
                         
                            return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
                        }   
                    } else {
                        if(strpos($event['message']['text'], 'Halo') !== false && !empty($event['source']['userId'])){
                            $userId     = $event['source']['userId'];
                            $getprofile = $bot->getProfile($userId);
                            $profile    = $getprofile->getJSONDecodedBody();
                            $greetings  = new TextMessageBuilder("Halo, ".$profile['displayName']);
                     
                            $result = $bot->replyMessage($event['replyToken'], $greetings);
                            return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
                        } else {
                            //$pesan     = str_replace(" ", "%20", $event['message']['text']);
                            //$key       = '2f8549cb-49b3-4089-9339-eecaf2fe92e6';
                            //$url       = 'http://sandbox.api.simsimi.com/request.p?key='.$key.'&lc=id&ft=1.0&text='.$pesan;
                            //$json_data = file_get_contents($url);
                            //$url       = json_decode($json_data,true);

                            //if ((strpos($url['response'], 'simi') !== false)) {
                            //  $fetch = str_replace("simi", "AusBOT", $url['response']);   
                            //}else {
                            //  $fetch = $url['response'];
                            //}

                            $groupId    = $event['source']['groupId'];
                            $userId     = $event['source']['userId'];
                            $getprofile = $bot->getProfile($userId);
                            $profile    = $getprofile->getJSONDecodedBody();
                            $name       = !empty($profile['displayName']) ? $profile['displayName'] : 'Unidentified';
                            $filename   = $_SERVER['DOCUMENT_ROOT']."/database/database_{$groupId}.txt";

                            if (strpos($event['message']['text'], "!l") !== FALSE) {
                                $getuser   = explode(" ", $event['message']['text']);
                                $storeData = fopen($filename, "r");
                                $data      = fread($storeData,filesize($filename));
                                fclose($storeData);
                                $explo = explode("\n", $data);

                                if (empty($getuser[1])):
                                    $sending = "Nama User Kosong.";
                                else:
                                    foreach ($explo as $value){
                                        $msg = explode(" : ", $value);

                                        if(strpos($value, strtolower($getuser[1]) !== FALSE)):
                                            $sending .= '['.date_format($msg[0],"h:i").']'. $msg[1] .' : '. $msg[2] ."\n";
                                        endif;
                                    }
                                endif;
                                
                            }else {
                                $lines      = file($filename);
                                $last_line  = $lines[count($lines)-1];
                                $fetch_date = explode(" : ", $last_line); 

                                //Delete data when last stored data is yesterday
                                if((time()-(60*60*24)) < strtotime($fetch_date[0])){
                                    unlink($filename);
                                }else {
                                    $storeData = fopen($filename, "a");
                                    $textStore = date("Y-m-d h:i:s").' : '.strtolower($name).' : '.$event['message']['text']."\n";
                                    $status = fwrite($storeData, $textStore);
                                    fclose($storeData);   
                                }
                            }

                            $result = $bot->replyText($event['replyToken'], $sending);
                            return $res->withJson($result->getJSONDecodedBody(), $result->getHTTPStatus());
                        }
                    }
                }
            }
        }
    }
}
?>