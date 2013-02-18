<?php

require_once( __DIR__ .'/twitteroauth/twitteroauth.php');
require_once( __DIR__ .'/ini.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_KEY, OAUTH_SECRET);
?>