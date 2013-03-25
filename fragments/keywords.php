<?

$first_day  = time() - (24 * 60 * 60 * (3));


$query = "SELECT * FROM word_frequency_analysis WHERE date > '$first_day'  ORDER BY frequency DESC LIMIT 30";

$results = $db->fetch($query);



foreach ($results as $key => $value) {
    $query = "SELECT * FROM word_frequency_analysis WHERE date < '$first_day' && term = '" . addslashes($value['term']) . "' ";
    $historical_terms = $db->fetch($query);
    
    $results[$key]['staleness'] = 0;
    foreach ($historical_terms as $historical_term) {
        $historical_delta = $value['date'] - $historical_term['date'];
        
        //if the term has been popular in the last 48 hours this should not count against it
        //if the term was popular a long time ago, this also does not contribute to staleness
        
        if ($historical_delta > (60 * 60 * 48)) {
            $previous_weighted_frequency = $historical_term['frequency'] * (($historical_delta) / (60 * 60 * 48));
            
        } else {
            $previous_weighted_frequency = 0;
        }
        
        
        $results[$key]['staleness'] += $previous_weighted_frequency;
    }
    
    $results[$key]['score'] = $results[$key]['frequency'] - ($results[$key]['staleness'] /5);
}



//sort into order of occurence
usort($results, 'sortByFresh');
    
function sortByFresh($a, $b) {
    return ($b['score']) - ($a['score']);
}

$i = 0; 
foreach($results as $result) {
    if(!is_numeric(strpos($result['term'], 'co/'))){
        if($i<10) {
            echo "<p class='btn keyword_search'>" . $result['term'] . "</p>";
        }
        $i++;
    }
}
?>

<div id='keyword_search_results'>
    
</div>    