<?


include('../../ini.php');
include('tweets_by_time.php');
include('entity_extraction.php'); 
include('word_frequency.php'); 

$content = tweets_by_time(4);

$word_frequency_array = word_frequency($content);
$entity_array         = entity_extraction($content);

$text_analysis = array_merge($word_frequency_array, $entity_array);


?>