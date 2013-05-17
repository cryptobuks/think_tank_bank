<?  
$old = time() - (60 * 60 * 24 * 2);
$new = time() - (60 * 60 * 24 * 0);

$top_influencers_query = "SELECT * , COUNT( * )
    FROM `people_interactions`
    JOIN people ON people.twitter_id = people_interactions.target_id
    WHERE target_id != 0 && time > $old && time < $new
    && people.thinktank_name !=''
    GROUP BY people.thinktank_name
    ORDER BY COUNT(*) DESC LIMIT 10";

$top_influencers = $db->fetch($top_influencers_query);

echo "<ol>";
foreach($top_influencers as $top_influencer) {
    //print_r($top_influencer);
    echo "<li><strong><a href='/final/single.php?person_id='>".$top_influencer['thinktank_name']. "</a> (" .$top_influencer['COUNT( * )'].")</strong></li>";
}
echo "</ol>";

?>