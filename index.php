<?
    include('fragments/header.php');
?>

        <? 
            $time = time() - (60 * 60 *24 * 3);  
            
            
            $top_tweets         = $db->fetch("SELECT * FROM tweets
            JOIN people ON people.twitter_id = tweets.user_id 
            JOIN people_thinktank ON people_thinktank.person_id = people.person_id 
            JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id  
            WHERE exclude != '1' && time > $time && people_thinktank.role != 'report_author_only'
            ORDER BY rts 
            DESC LIMIT 5");
            
            $top_influencers    = $db->fetch("SELECT * , count( DISTINCT originator_id )
            FROM `people_interactions`
            JOIN people ON people.twitter_id = people_interactions.target_id
            JOIN people_thinktank ON people_thinktank.person_id = people.person_id
            JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id
            WHERE exclude !=1 && `time` > $time && people_thinktank.role != 'report_author_only'
            GROUP BY target_id
            ORDER BY count( DISTINCT originator_id ), time DESC
            LIMIT 10 ");
            
            
            
            ?>
            
            
            
            <div class='row'>
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
        

        
        <?  
        include('fragments/footer.php');
        ?>
        

