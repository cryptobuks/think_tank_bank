<?php

require_once( __FILE__ .'/../twitteroauth/twitteroauth.php');
require_once( __FILE__ .'/../ini.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_KEY, OAUTH_SECRET);
?>