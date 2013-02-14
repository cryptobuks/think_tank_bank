<?
include('../../ini.php');
include('tweets_by_time.php');
include('entity_extraction.php'); 
include('word_frequency.php'); 

$days_ago = 10;
$timestamp = time() - (24 * 60 * 60 * ($days_ago));

$content = tweets_by_time($days_ago);


$word_frequency_array = word_frequency($content, '0.3'); //Last arg downweights results from wordcount to balance it with enities
$entity_array         = entity_extraction($content);

//combine both techniques for finding interesting words
$text_analysis = array_merge($word_frequency_array, $entity_array);

function convert_to_lower($a) { 
    return strtolower($a['name']);
}

//remove dups
foreach ($text_analysis as $key => $val) {     
    $duplicate_key = array_search( strtolower($key['name']), array_map('convert_to_lower', $text_analysis) );
    
    if (is_numeric($duplicate_key)) { 
        $text_analysis[$key]['frequency'] += $text_analysis[$duplicate_key]['frequency'];
        $text_analysis[$key]['source'] = 'combined';
        unset($text_analysis[$duplicate_key]);
    }
}

usort($text_analysis, 'sortByFreq');

function sortByFreq($a, $b) {
   return $b['frequency'] - $a['frequency'];
}

foreach($text_analysis as $term) { 
    $name       = addslashes($term['name']);
    $type       = addslashes($term['type']);    
    $source     = addslashes($term['source']);
    $frequency  = addslashes($term['frequency']); 
}


?>