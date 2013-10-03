<?
include_once( __DIR__ . '/../ini.php');


$expanded_url = urldecode($_GET['expanded_url']);



$person_query = "SELECT * FROM links 
JOIN people ON links.twitter_id = people.twitter_id 
WHERE `expanded_url` = '$expanded_url'
ORDER BY time ASC";

$people = $db->fetch($person_query);

foreach($people as $person) { 

    $tweet_query = "SELECT * FROM tweets 
    WHERE `time`  = '" . $person['time'] . "'
    LIMIT 1" ;

    
    $tweet = $db->fetch($tweet_query);
    if (isset($tweet[0]['tweet_id'])) {
    ?>
    <blockquote class="twitter-tweet"> <a href="https://twitter.com/twitterapi/status/<?= $tweet[0]['tweet_id'] ?>" data-datetime="2011-11-07T20:21:07+00:00">November 7, 2011</a></blockquote>
<?    
}}
?>
<script>
     twttr.widgets.load();
</script>




