<?

$old = time() - (60 * 60 * 24 * 6);
$new = time() - (60 * 60 * 24 * 4);

$top_thinktanks_query = "SELECT * , COUNT( * )
    FROM `tweets`
    JOIN people ON people.twitter_id = tweets.user_id
    WHERE  time > $old && time < $new
    && people.thinktank_name !='' && role!='' && role!='report_author_only'
    GROUP BY people.thinktank_name
    ORDER BY COUNT(*) DESC LIMIT 28";

$top_thinktanks = $db->fetch($top_thinktanks_query);

$horizon = time() - (60 * 60 * 24 * 20);
$top_thinktanks_compare_query = "SELECT * , COUNT( * )
    FROM `tweets`
    JOIN people ON people.twitter_id = tweets.user_id
    
    WHERE  time > $horizon && people.thinktank_name !='' && people.role!='' && people.role!='report_author_only'
    GROUP BY people.thinktank_name
    ORDER BY COUNT(*) DESC LIMIT 28";

$top_thinktanks_compare = $db->fetch($top_thinktanks_compare_query);



$ordered_array = array();

foreach($top_thinktanks as $key => $val) {
    $today_rank = intval($key);
    foreach($top_thinktanks_compare as $compare_key => $compare_val) {
        if ($compare_val['thinktank_name'] ==  $val['thinktank_name'] ) {
            $average_rank = intval($compare_key);
           
        }
    }
   
    $movement = intval($today_rank) - intval($average_rank);
    $top_thinktanks[$key]['movement'] = $movement;
    
}

usort($top_thinktanks, 'sortByMovement');
    
function sortByMovement($a, $b) {
    return ($b['movement']) - ($a['movement']);
}

echo "<ol>";
foreach($top_thinktanks as $top_thinktank) {
    echo "<li><strong><a href='/final/single.php?person_id='>".$top_thinktank['thinktank_name']. "</a> ".$top_thinktank['COUNT( * )']." </strong></li>";
}
echo "</ol>";

?>