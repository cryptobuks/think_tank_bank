<?
include_once( __DIR__ . '/../ini.php');

echo "<ul >";

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

    ?>
    <blockquote class="twitter-tweet"><p>Search API will now always return "real" Twitter user IDs. The with_twitter_user_id parameter is no longer necessary. An era has ended. ^TS</p>&mdash; Twitter API (@twitterapi) <a href="https://twitter.com/twitterapi/status/<?= $tweet[0]['tweet_id'] ?>" data-datetime="2011-11-07T20:21:07+00:00">November 7, 2011</a></blockquote>
<?    
}

echo "</ul><br/>";
?>
<script>
     twttr.widgets.load();
</script>


