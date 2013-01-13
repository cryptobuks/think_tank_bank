<? 

include('../twitter/twitter_connect.php');

include('../header.php');

echo "<h1>Search Mentions...</h1>";

$people = $db->fetch("SELECT * FROM aliens WHERE twitter_id!='' LIMIT 700,100 " );


foreach($people as $person) { 
    
    echo "<h3>" . $person['name'] . "</h3>";
    $tweets = $connection->get('statuses/user_timeline', array('user_id' =>  $person['twitter_id'], 'include_rts'=>'true'));
    
    if(count($tweets) == 0) { 
        echo "no tweets for this user";
    }
    foreach ($tweets as $tweet){ 
        
        
        $tweet_id = $tweet->id_str;
        $text = addslashes($tweet->text);
        $rts = $tweet->retweet_count;
        $user_id = $tweet->user->id;
        

        //add to the mentions table 
        if (isset($tweet->text)) {
            $words = explode(" ", $tweet->text);
            
            $users = array();
            $i=0;
            
            if(is_numeric(strpos($tweet->text, 'RT '))) { 
                $retweet_status=1;
                echo "RT detected";
                
            }  
            else { 
                $retweet_status=0;
            }
            
            foreach($words as $word) {

            
                if(preg_match("/@/", $word)){    
                  $user=str_replace("@", "",$word);
                  $user = explode("'", $user);
                  if(!empty($user)){
                      $users[] = $user[0];
                  }
                }
                $i++;
            }
            
        
            foreach ($users as $user) {
                $match_query = "SELECT * FROM people WHERE twitter_handle='$user'";
                
                $matches = $db->fetch($match_query);
                
                if (count($matches) > 0 ) { 
                    $target_id = $matches[0]['twitter_id']; 
                    $target_name = $matches[0]['name_primary'];
                    $tweet_id =  $tweet->id_str;
                    $originator_id = $person['twitter_id'];
                
                    $existing_query = "SELECT * FROM people_interactions WHERE tweet_id ='$tweet_id' && target_id='$target_id'";
                    echo $existing_query;
                    $existing = $db->fetch($existing_query);
                    
                    if (count($existing) == 0 && $target_id!=$originator_id ) {
                        echo "<p>New mention: $tweet->text for $target_name </p>";
                    
                        $text = addslashes($tweet->text); 
                        $insert_query = "INSERT INTO people_interactions (tweet_id, originator_id, target_id, `text`, rt) VALUES ($tweet_id, $originator_id, $target_id, '$text', '$retweet_status')";
                        $db->query($insert_query);
                    }
                    else {
                        echo "<p>Allready in: $tweet->text for $target_name </p>";
                    }
                }    
            }
        }    
    }
}


?>



<? include('../footer.php'); ?>