<?

$first_day  = time() - (24 * 60 * 60 * (2));


$query = "SELECT * FROM word_frequency_analysis WHERE (source ='entity' || term LIKE '#%') && date >= $first_day ORDER BY frequency DESC LIMIT 30";
echo $query;
$results = $db->fetch($query);



foreach ($results as $key => $value) {
    
    if (is_numeric(strpos($value['term'], '#'))) { 
         $score = $results[$key]['frequency'] * 30; 
        
    }
    else { 
         $score = $results[$key]['frequency'] *50; 
    }
    
    $results[$key]['score'] = $score;
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
            echo "<p class='btn keyword_search'>" . $result['term'] ."</p>";
        }
        $i++;
    }
}
?>

<div id='keyword_search_results'>
    
</div>    