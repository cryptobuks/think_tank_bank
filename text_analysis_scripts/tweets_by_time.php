<?

/*
 * tweets_by_time
 *
 * Extracts entities from a body of text
 *
 * @content (text) Some text to extract entities from 
 * @return (array) Entities extracted 
 */

function tweets_by_time($days_ago) { 
  
  //get tweets from the DB 
  $db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);
  
  //work out time limits
  $first_day = time() - (24 * 60 * 60 * ($days_ago + 0.5));
  $last_day  = time() - (24 * 60 * 60 * ($days_ago - 0.5));
  
  $query ="SELECT * FROM `tweets` WHERE time > $first_day && time < $last_day";
  
  //echo $query;
  
  
  $db_contents = $db->fetch($query);
  

  
  //combine all the content into one giant block 
  $content = '';
  foreach ($db_contents as $db_content) {
      $content .= $db_content['text'] . " ";
       echo $db_content['text'] . "\n"; 
  }
  
  return $content;
}  

?>