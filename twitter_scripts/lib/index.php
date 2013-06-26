<?php
ini_set('display_errors', 1);
require_once( __DIR__ . '/TwitterAPIExchange.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "794979-IbAoLmMW5H0VONVInzocsG1vEyzCozofT3ZeZP4ke0",
    'oauth_access_token_secret' => "8yZNzpTR7CQyeSCkyQC2envvgXVirRb6Kaz6exs3s",
    'consumer_key' => "um0vAgcBaeGx2t317GUddw",
    'consumer_secret' => "m8luqTMNoWYPp86AbrY6mxFI9gLYTcks6mAcdCV0"
);

/** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
$url = 'https://api.twitter.com/1.1/blocks/create.json';
             
class twitterInterface { 
    public function __construct($settings) {
        $this->settings = $settings; 
    }
        
    public function get($resource, $getfield){ 
        $twitter = new TwitterAPIExchange($this->settings);
        $url = 'https://api.twitter.com/1.1/'.$resource.'.json';
        echo $getfield;
        $getfield = '?' . http_build_query($getfield);;
        $requestMethod = 'GET';    
        
        $json = $twitter->setGetfield($getfield)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();
        return json_decode($json);
    }
}             

//init twitter client
$connection = new twitterInterface($settings);
