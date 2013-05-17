<?

include_once(__DIR__ . '/tweets_by_time.php');
include_once(__DIR__ . '/entity_extraction.php'); 
include_once(__DIR__ . '/word_frequency.php'); 

function text_analysis($days_ago) {
    
    $target_timestamp = time() - (24 * 60 * 60 * ($days_ago)); 
    $timestamp = strtotime(date('F j, Y',$target_timestamp));

    $content = tweets_by_time($days_ago);
    
    $db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

    $word_frequency_array = word_frequency($content, '0.3'); //Last arg downweights results from wordcount to balance it with enities
    $entity_array         = entity_extraction($content);
   
    //combine both techniques for finding interesting words
    $text_analysis = array_merge($word_frequency_array, $entity_array);
    
    //remove duplicates
    foreach ($text_analysis as $key => $val) {     
        $duplicate_key = array_search( strtolower($key['term']), array_map('convert_to_lower', $text_analysis) );
    
        if (is_numeric($duplicate_key)) { 
            $text_analysis[$key]['frequency'] += $text_analysis[$duplicate_key]['frequency'];
            $text_analysis[$key]['source'] = 'combined';
            unset($text_analysis[$duplicate_key]);
        }
    }

    //sort into order of occurence
    usort($text_analysis, 'sortByFreq');
    

    
    //add to DB
    foreach($text_analysis as $value) { 
        $term       = addslashes($value['term']);
        $type       = addslashes($value['type']);    
        $source     = addslashes($value['source']);
        $frequency  = addslashes($value['frequency']); 
    
        //test: is the term already in the DB for this date? 
        $query = "SELECT * FROM word_frequency_analysis WHERE term='".$term."' && `date`='$timestamp'";

        $extant = $db->fetch($query);     

        if (!isset($extant[0])) { 
            $query      = "INSERT INTO word_frequency_analysis (term, type, source, frequency, `date`) 
            VALUES ('$term', '$type', '$source', '$frequency', '$timestamp')"; 
            echo "Adding New Word Freq: $term -- $frequency from $source\n\n"; 
            $db->query($query);
        }
    
        else { 
            $query =    "UPDATE word_frequency_analysis SET type='$type', source='$source', frequency='$frequency' 
                        WHERE  `date`='$timestamp' && term='$term'";
            echo "Updating old record: $term -- $frequency from $source\n\n"; 
            $db->query($query);
        }    
    }
    
}


//some functions called from within the function 
function convert_to_lower($a) { 
    return strtolower($a['term']);
}

function sortByFreq($a, $b) {
   return $b['frequency'] - $a['frequency'];
}


?>