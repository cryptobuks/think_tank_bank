<?

$old = time() - (60 * 60 * 24 * 2);
$new = time() - (60 * 60 * 24 * 0);

$top_influencers_query = "SELECT *, COUNT(target_id), COUNT(DISTINCT tweet_id), target_id
FROM `people_interactions`
JOIN people ON people.twitter_id = people_interactions.target_id
WHERE target_id != 0 && time > $old && time < $new
GROUP BY target_id
ORDER BY COUNT(DISTINCT tweet_id) DESC LIMIT 15";

$top_influencers = $db->fetch($top_influencers_query);
echo "<ol>";
foreach($top_influencers as $top_influencer) {
    $target_id = $top_influencer['target_id'];
    

    
        echo "<li><strong><a href='/final/single.php?person_id='>".$top_influencer['name_primary']. "</a></strong></li>";
    

    /*
    $tweets = $db->fetch($tweets_query);
    
    foreach($tweets as $tweet){
        echo '<p>' . $tweet['text'] . '</p>';
        
    }
    echo "<hr/>";
    */
}
echo "</ol>";
      
?>