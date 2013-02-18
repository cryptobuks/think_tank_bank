<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once( $root .'/twitter_scripts/twitteroauth/twitteroauth.php');
require_once( $root .'/ini.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_KEY, OAUTH_SECRET);
?>