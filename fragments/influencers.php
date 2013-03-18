<?

$old = time() - (60 * 60 * 24 * 2);
$new = time() - (60 * 60 * 24 * 0);

$top_influencers_query = "SELECT *, COUNT(target_id), COUNT(DISTINCT tweet_id), target_id
FROM `people_interactions`
JOIN people ON people.twitter_id = people_interactions.target_id
JOIN people_thinktank ON people_thinktank.person_id = people.person_id
JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id
WHERE target_id != 0 && time > $old && time < $new
GROUP BY target_id
ORDER BY COUNT(DISTINCT tweet_id) DESC LIMIT 8";

$top_influencers = $db->fetch($top_influencers_query);
foreach($top_influencers as $top_influencer) {
    $target_id = $top_influencer['target_id'];
    
    echo "<p><strong>". $top_influencer['twitter_handle'] ."</strong></p>";
    
    $tweets_query = "SELECT *
    FROM `people_interactions`
    WHERE target_id = $target_id && time > $old && time < $new";
    
    
    
    $tweets = $db->fetch($tweets_query);
    
    foreach($tweets as $tweet){
        echo '<p>' . $tweet['text'] . '</p>';
        
    }
    echo "<hr/>";
    
}
      
?>