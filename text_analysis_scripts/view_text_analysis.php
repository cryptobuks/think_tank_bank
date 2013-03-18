
<?

include('../ini.php');
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

for ($day = 0; $day < 30; $day++) {
    echo "$day days ago\n";
    echo "--------------------\n\n\n";
    $days_ago = $day;
    
    $first_day = time() - (24 * 60 * 60 * ($days_ago + 0.5));
    $last_day  = time() - (24 * 60 * 60 * ($days_ago - 0.5));
    
    $query = "SELECT * FROM word_frequency_analysis WHERE date > '$first_day' && date < '$last_day' ORDER BY frequency";
    
    $results = $db->fetch($query);
    
    foreach ($results as $key => $value) {
        $query            = "SELECT * FROM word_frequency_analysis WHERE date < '$first_day' && term = '" . addslashes($value['term']) . "' ";
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
        
        $results[$key]['score'] = $results[$key]['frequency']  - ($results[$key]['staleness'] /10);
    }
    
    
    
    //sort into order of occurence
    usort($results, 'sortByFresh');
    
    
}


function sortByFresh($a, $b) {
    return ($b['score']) - ($a['score']);
}
?>

