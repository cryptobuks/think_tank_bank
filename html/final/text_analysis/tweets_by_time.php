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
  $first_day = time() - (24 * 60 * 60 * ($days_ago + 1));
  $last_day  = time() - (24 * 60 * 60 * ($days_ago));
  
  $query ="SELECT * FROM `tweets` 
      JOIN people ON people.twitter_id = tweets.user_id 
      JOIN people_thinktank ON people_thinktank.person_id = people.person_id 
      JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id
      WHERE time > $first_day && time < $last_day && people_thinktank.role != 'report_author_only'
      GROUP BY text";

  $db_contents = $db->fetch($query);


  //combine all the content into one giant block 
  $content = '';
  foreach ($db_contents as $db_content) {
      $content .= $db_content['text'] . " ";
    
  }
  
  return $content;
}  

?>