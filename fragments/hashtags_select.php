<?

$old = time() - (60 * 60 * 24 * 2);
$new = time() - (60 * 60 * 24 * 0);

$hashtags_query = "SELECT *, SUM(frequency) AS freq_sum FROM `word_frequency_analysis` 
    WHERE term LIKE '#%' && date> $old 
    GROUP BY term
    ORDER BY freq_sum DESC
    LIMIT 8";


$hashtags = $db->fetch($hashtags_query);

echo "<ul id='hashtag_listing'>";
foreach($hashtags as $hashtag) {
    //print_r($top_influencer);
    echo "<li><a class='hashtag_link' data-hashtag='".addslashes($hashtag['term'])."'<strong>" .$hashtag['term']. "</strong> (".$hashtag['freq_sum'].")</ li>";
}
echo "</ul>";

?>

