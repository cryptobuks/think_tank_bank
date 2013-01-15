<? 
include('twitter_connect.php');
include('../header.php');
echo "<h1>Search Mentions...</h1>";

$people = $db->fetch("SELECT * FROM alien_cache LIMIT 700, 100" );

foreach($people as $person) { 
    
    echo "<h3>" . $person['name'] . "</h3>";
    $tweets = $connection->get('statuses/user_timeline', array('user_id' =>  $person['twitter_id'], 'include_rts'=>'true'));

    if(count($tweets) == 0) { 
        echo "no tweets for this user";
    }
    foreach ($tweets as $tweet){ 
       
        if(isset($tweet->retweeted_status)) { 
            
            echo $tweet->text . "IS AN RT" . "<br/>";
            $target_id = $tweet->retweeted_status->user->id; 
            $originator_id = $tweet->user->id; 
            $text = addslashes($tweet->text);
            $tweet_id = $tweet->id; 
            $retweet_status =1; 
            
            $existing_query = "SELECT * FROM people_interactions WHERE tweet_id ='$tweet_id' && target_id='$target_id'";
            echo $existing_query;
            $existing = $db->fetch($existing_query);
        
            $text = addslashes($tweet->text); 
            $insert_query = "INSERT INTO people_interactions (tweet_id, originator_id, target_id, `text`, rt) VALUES ($tweet_id, $originator_id, $target_id, '$text', '$retweet_status')";
            $db->query($insert_query);
            
        }
        
        else { 
            echo 'not a retweet <br/>';
        }    
    }
}


?>



<? include('../footer.php'); ?>