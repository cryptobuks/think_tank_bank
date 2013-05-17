<?

$old = time() - (60 * 60 * 24 * 1);
$new = time() - (60 * 60 * 24 * 0);

$top_thinktanks_query = "SELECT *  FROM `tweets`
    JOIN people ON people.twitter_id = tweets.user_id
    WHERE thinktank_name='demos' && role!='' && role!='report_author_only'
    
   
    ORDER BY time DESC LIMIT 1000";

$top_thinktanks = $db->fetch($top_thinktanks_query);

echo "<ol>";
foreach($top_thinktanks as $top_thinktank) {
    //print_r($top_influencer);
    echo "<li><strong>" .$top_thinktank['name_primary']. "</strong> ".$top_thinktank['text']. "(" .")</li>";
}
echo "</ol>";

?>

