<? 
    $old = time() - (60 * 60 * 24 * 2);  
    $new = time() - (60 * 60 * 24 * 0);  
    
    $top_tweets = $db->fetch("SELECT * FROM tweets
    JOIN people ON people.twitter_id = tweets.user_id 
    JOIN people_thinktank ON people_thinktank.person_id = people.person_id 
    JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id  
    WHERE exclude != '1' && time > $old && people_thinktank.role != 'report_author_only' 
    ORDER BY rts 
    DESC LIMIT 0");
    
    //Building an array of interactions 
    $top_influencers_query = "SELECT COUNT(target_id), COUNT(DISTINCT originator_id), target_id
    FROM `people_interactions`
    WHERE target_id != 0 && time > $old && time < $new 
    GROUP BY target_id
    ORDER BY COUNT(target_id) DESC  LIMIT 8";
    
    $top_influencers = $db->fetch($top_influencers_query);            
    
    $used_tweets = array();
    $used_authors = array(); 
    
    
    foreach($top_influencers as $top_influencer) { 
        
        
        if(!array_search($top_influencer['target_id'], $used_authors ))  {
             
            $target_id = $top_influencer['target_id'];
        
            $center_name = twitter_id_to_name($target_id, $db);
            echo "<div class='exchange'>";
            echo "<h3 class='exchange_header'>$center_name</h3>";            
            echo "<div class='exchange_body'>";
            //find all tweets which target this person
            $interactions_toward_target_query = "SELECT * FROM people_interactions 
            WHERE target_id= '$target_id' && time > $old && time < $new "; 
       
        
            $interactions_toward_target = $db->fetch($interactions_toward_target_query);

            //find all tweets from the target 
            $interactions_from_target_query = "SELECT * FROM people_interactions 
            WHERE originator_id= '$target_id' && time > $old && time < $new "; 
        
            $interactions_from_target = $db->fetch($interactions_from_target_query);
        
            $interactions = array_merge($interactions_toward_target,$interactions_from_target);
        
            $used_authors[] = $target_id;
        
            foreach($interactions as $key=>$value) {
                $origin_name = twitter_id_to_name($value['originator_id'], $db);
                if ($origin_name == "") { 
                    //echo "_______" . $value['originator_id'] . "____";
                }
                $interactions[$key]['twitter_handle'] = $origin_name;
                $used_authors[] = $value['originator_id'];
            }
        
         
         
        
            usort($interactions, 'tweets_by_date');
        
            foreach($interactions as $interaction) {
                if(!array_search($interaction['tweet_id'],$used_tweets ))  { 
                    $used_tweets[] = $interaction['tweet_id'];
                    $date_string = date("F j, Y, g:i a", $interaction['time']);
                    $author = $interaction['twitter_handle'];
                    $tweet  = $interaction['text'];
                    echo "<p><strong>$author:</strong> $tweet ($date_string)</p>";
                }    
            }
        
            echo "</div>";
            echo "</div>";
        }    
    }
    
    function tweets_by_date($a, $b) {
        if ($a['time'] == $b['time']) return 0;
        return ($a['time'] > $b['time']) ? -1 : 1;
    }
    
                
    
    
    ?>