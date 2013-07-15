<?php
ini_set('display_errors', 1);
require_once( __DIR__ . '/TwitterAPIExchange.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => OAUTH_KEY,
    'oauth_access_token_secret' => OAUTH_SECRET,
    'consumer_key' => CONSUMER_KEY,
    'consumer_secret' => CONSUMER_SECRET
);



/** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
$url = 'https://api.twitter.com/1.1/blocks/create.json';
             
class twitterInterface { 
    public function __construct($settings) {
        $this->settings = $settings; 
    }
    
    
        
    public function get($resource, $getfield){ 
        print_r($this->settings);
        $twitter = new TwitterAPIExchange($this->settings);
        $url = 'https://api.twitter.com/1.1/'.$resource.'.json';
        
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
