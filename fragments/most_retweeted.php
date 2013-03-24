<ol>
<?


$old = time() - (60 * 60 * 24 * 2);
$new = time() - (60 * 60 * 24 * 0);

$query= "SELECT * FROM tweets
    JOIN people ON people.twitter_id = tweets.user_id
    JOIN people_thinktank ON people_thinktank.person_id = people.person_id
    JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id
    WHERE exclude != '1' 
    && time > $old 
    && people_thinktank.role != 'report_author_only'
    && is_rt='0'
    ORDER BY rts DESC LIMIT 5";

$top_tweets = $db->fetch($query);

foreach($top_tweets as $top_tweet) { 
    echo "<li><strong><a href='/final/single.php?person_id=".$top_tweet['person_id'] ."'>".$top_tweet['name_primary']. "</a> (" .$top_tweet['name'].")</strong> ". $top_tweet['text']. "<br/><em class='highlight'>Retweeted ".$top_tweet['rts']." times</em></li>";
    $test = $connection->get('statuses/show', array(
        'id' => $top_tweet['tweet_id']
    ));

}
?>
</ol>