<?php

set_include_path('/home/96975/domains/think-tanks.jimmytidey.co.uk/html/final_scripts');
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_KEY, OAUTH_SECRET);

?>