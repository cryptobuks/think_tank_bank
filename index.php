<?
    include('fragments/header.php');
?>

        <? 
            $time = time() - (60 * 60 * 24 * 20);  
            
            
            $top_tweets         = $db->fetch("SELECT * FROM tweets
            JOIN people ON people.twitter_id = tweets.user_id 
            JOIN people_thinktank ON people_thinktank.person_id = people.person_id 
            JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id  
            WHERE exclude != '1' && time > $time && people_thinktank.role != 'report_author_only'
            ORDER BY rts 
            DESC LIMIT 5");
            
            //Building an array of interactions 
            $top_influencers_query = "SELECT COUNT(*), target_id 
            FROM `people_interactions`  
            WHERE target_id != 0 && time > $time
            GROUP BY tweet_id 
            ORDER BY COUNT(*) DESC LIMIT 30";
            
            echo $top_influencers_query;
            
            $top_influencers = $db->fetch($top_influencers_query);
            
            
            
            foreach($top_influencers as $top_influencer) { 
                $target_id = $top_influencer['target_id']; 
                
                $target_name = $db->fetch("SELECT name from aliens WHERE twitter_id = '$target_id'");
               
                if(!isset($target_name[0])) { 
                    $target_name = $db->fetch("SELECT name_primary from people WHERE twitter_id = '$target_id'");
                    $target_name = $target_name[0]['name_primary'];
                }
                else { 
                     $target_name = $target_name[0]['name'];
                     
                }
                
                
                $interactions_query = "SELECT * FROM people_interactions 
                WHERE target_id= '$target_id' &&  time > $time "; 
            
                $interactions = $db->fetch($interactions_query);
                
                foreach($interactions as $interaction) { 
                    $originator_id = $interaction['originator_id'];
                    
                    
                    
                    $originator_name = $db->fetch("SELECT name from aliens WHERE twitter_id = '$originator_id'");
                    
                    if(!isset($originator_name[0])) { 
                        $originator_name = $db->fetch("SELECT name_primary from people WHERE twitter_id = '$originator_id'");
                        $originator_name = $originator_name[0]['name_primary'];
                    }
                    else { 
                         $originator_name = $originator_name[0]['name'];
                    }
                    
                    
                
                    $tweet = $interaction['text']; 
                    
                    echo "<p>$originator_name mentioned $target_name</p>";
                    echo "<p>$tweet</p>";
                    echo "<hr/>";
                }
                
                
                
                echo "--------------------\n\n\n";
            }
            
            
            
                        
            
            
            ?>
            
            
            
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
            
            
            <!--
            <div class='span6'>
                <h1>Most Retweeted</h1>
                <ol>
                <?
                foreach($top_tweets as $top_tweet) { 
                    echo "<li><strong><a href='/final/single.php?person_id=".$top_tweet['person_id'] ."'>".$top_tweet['name_primary']. "</a> (" .$top_tweet['name'].")</strong> ". $top_tweet['text']. "<br/><em class='highlight'>Retweeted ".$top_tweet['rts']." times</em></li>";
                               
                }
                ?>
                </ol>
            </div>
            -->

        
        <?  
        include('fragments/footer.php');
        ?>
        

