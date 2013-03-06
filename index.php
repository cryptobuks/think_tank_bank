<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
?>
    
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
            
            
            
            foreach($top_influencers as $top_influencer) { 
                     
                $target_id = $top_influencer['target_id'];
                
                $center_name = twitter_id_to_name($target_id, $db);
                            
                echo "<h3>$center_name ($target_id)</h3>";            
                               
                //find all tweets which target this person
                $interactions_toward_target_query = "SELECT * FROM people_interactions 
                WHERE target_id= '$target_id' && time > $old && time < $new "; 
                
                $interactions_toward_target = $db->fetch($interactions_toward_target_query);

                //find all tweets from the target 
                $interactions_from_target_query = "SELECT * FROM people_interactions 
                WHERE originator_id= '$target_id' && time > $old && time < $new "; 
                
                $interactions_from_target = $db->fetch($interactions_from_target_query);
                
                $interactions = array_merge($interactions_toward_target,$interactions_from_target);
                
                print_r($interactions);
                
                foreach($interactions as $key=>$value) {
                    $origin_name = twitter_id_to_name($value['originator_id'], $db);
                    if ($origin_name == "") { 
                        echo "_______" . $value['originator_id'] . "____";
                    }
                    $interactions[$key]['twitter_handle'] = $origin_name;
                }
                
                usort($interactions, 'tweets_by_date');
                
                foreach($interactions as $interaction) { 
                    $date_string = date("F j, Y, g:i a", $interaction['time']);
                    $author = $interaction['twitter_handle'];
                    $tweet  = $interaction['text'];
                    echo "<p><strong>$author:</strong> $tweet ($date_string)</p>";
                }
                
                echo "<hr/>";
            }
            
            function tweets_by_date($a, $b) {
                if ($a['time'] == $b['time']) return 0;
                return ($a['time'] > $b['time']) ? -1 : 1;
            }
            
                        
            
            
            ?>
            
            
             <!--
            <div class='row'>


                <div class='span6'>
                <h1>Currently Influential</h1>
                    <ol>
                        <?
                        foreach($top_influencers as $top_influencer) { 
                            $influenced = $db->fetch("SELECT * FROM people_interactions WHERE target_id = ". $top_influencer['target_id'] . " GROUP BY originator_id");
                    
                            echo "<li><strong><a href='/final/single.php?person_id=".$top_influencer['person_id'] ."'>".$top_influencer['name_primary']. "</a> (" .$top_influencer['name'].")</strong> Mentioned by: ";
                            $mention_array = array();
                        
                            foreach  ($influenced as $influ) { 
                               $person = $db->fetch("SELECT * FROM people WHERE twitter_id = ". $influ['originator_id']); 
                                if(empty($person)) { 
                                    $alien_query = "SELECT * FROM aliens WHERE twitter_id = '".$influ['originator_id']."'";

                                    $alien_result = $db->fetch($alien_query);
                                    $mention_array[] = $alien_result[0]['name'];
                                }
                                else { 
                                    $mention_array[] = $person[0]['name_primary'];
                                }
                            }        
                    
                            echo implode($mention_array, ", ");
                    
                            echo "</li>";       
            
                        }
                        ?>
                    </ol>
                </div>
            </div>
            
           
           
            <div class='span6'>
                <h1>Most Retweeted</h1>
                <ol>
                <?
                foreach($top_tweets as $top_tweet) { 
                    echo "<li><strong><a href='/final/single.php?person_id=".$top_tweet['person_id'] ."'>".$top_tweet['name_primary']. "</a> (" .$top_tweet['name'].")</strong> ". $top_tweet['text']. "<br/><em class='highlight'>Retweeted ".$top_tweet['rts']." times</em></li>";
                               
                }
                ?>
                </ol>
            </div> -->
            

        
        <?  
        include('fragments/footer.php');
        ?>
        

