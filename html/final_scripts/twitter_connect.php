<?php

set_include_path('/home/96975/domains/think-tanks.jimmytidey.co.uk/html/');
require_once('final_scripts/twitteroauth/twitteroauth.php');
require_once('final_scripts/config.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_KEY, OAUTH_SECRET);

?>