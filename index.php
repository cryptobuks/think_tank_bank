<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    
?>
    

<div class='row-fluid'>
    
    
    <div class='span8'>
        <table id="myTable" class="tablesorter">
            <?
                
              
                $query_tweets = "SELECT *, COUNT(*) as no_of_tweets, SUM(tweets.rts) as rt_count
                    FROM `tweets`
                    JOIN people ON people.twitter_id = tweets.user_id
                    WHERE  time > $old
                    && role!='report_author_only' && role!='official twitter acc' && organisation_type = 'thinktank'  && is_rt=0 	  
                    
                    GROUP BY people.twitter_id
                    HAVING rt_count > 1
                    ORDER BY no_of_tweets DESC ";
                    
                
                    
                $query_retweets = "SELECT twitter_id, 
                    FROM `tweets`
                    JOIN people ON people.twitter_id = tweets.user_id
                    WHERE  time > $old
                    && role!='report_author_only' && role!='official twitter acc' 
                    GROUP BY people.twitter_id";
                    
                $query_interactions = "SELECT COUNT(*) as no_of_interactions, target_id
                    FROM `people_interactions`
                    JOIN people ON people.twitter_id = people_interactions.originator_id
                    WHERE  time > $old
                    GROUP BY people.twitter_id";                    
                
                $query_followers = "SELECT COUNT(*) as follower_count, twitter_id FROM `people_followees` 
                    JOIN people ON people_followees.follower_id = people.twitter_id
                     
                    GROUP BY twitter_id
                    ORDER BY COUNT(*) DESC";
                
                $tweet_results          = $db->fetch($query_tweets);
                
                //$retweet_results        = $db->fetch($query_retweets);
                // $interactions_results   = $db->fetch($query_interactions);
                $followers_results      = $db->fetch($query_followers);
                
                $merged_results = array();
                
                //merge all these queries into one
                foreach($tweet_results as $tweet_result) { 
                    $tmp_array = array();
                    $tmp_array[] = $tweet_result;
                    
                    $tmp_array[1] = array();
                    $tmp_array[2] = array();
                    $tmp_array[3] = array();                    
                    
                    /*
                    foreach($interactions_results as $interactions_result) {
                        if($interactions_result['target_id'] == $tweet_result['twitter_id']) { 
                           $tmp_array[1] = $interactions_result;
                        }
                    }

                    foreach($retweet_results as $retweet_result) { 
                         if($retweet_result['twitter_id'] == $tweet_result['twitter_id']) { 
                            $tmp_array[3] = $retweet_result;
                        }
                    }                    
                    
                    */
                    
                    foreach($followers_results as $followers_result) { 
                         if($followers_result['twitter_id'] == $tweet_result['twitter_id']) { 
                            $tmp_array[2] = $followers_result;
                        }
                    }
                     
                    $merged_results[] = array_merge($tmp_array[0], $tmp_array[1], $tmp_array[2], $tmp_array[3]);   
                }
                
                
                echo "<thead><tr>";
                    echo "<th id='column_name' >Name</th>";
                    echo "<th id='column_thinktank'>Thinktank</th>";
                    echo "<th id='column_retweets'>Retweets</th>";
                    echo "<th id='column_tweets'>Tweets</th>";
                    
                    
     
                   
                    echo "<th id='column_followers'>Total <br/> Followers</th>";
                    echo "<th id='column_mp_followers'>MP <br/>Followers </th>";
                    echo "<th id='column_thinktank_followers'>Thinktank <br />Followers </th>";
                   
                echo "</tr></thead><tbody>";
                
         
                foreach($merged_results as $result) {
                    
                    echo "<!--"; 
                    print_r($result);
                    echo "-->";
                     
                    $twitter_id = $result['user_id'];
                    
                    $mp_follower_query = 
                        "SELECT COUNT(*) as mp_followers FROM people_followees 
                        JOIN people on people.twitter_id = people_followees.follower_id
                        WHERE followee_id ='$twitter_id' && organisation_type='MP' ";
                        
                    $thinktank_follower_query = 
                        "SELECT COUNT(*) as thinktank_followers FROM people_followees 
                        JOIN people on people.twitter_id = people_followees.follower_id
                        WHERE followee_id ='$twitter_id' && organisation_type='thinktank' ";                        
                    
                    $mp_count   = $db->fetch($mp_follower_query);
                    $wonk_count = $db->fetch($thinktank_follower_query);
                    
                    echo "<tr >";
                        echo "<td>
                            <a class='person_link' data-id=".$result['person_id']."  >" 
                               . "<img alt='".$result['name_primary']."' src='".$result['twitter_image']."'><br/>" .
                                $result['name_primary'] . 
                            "</a>
                        </td>";
                        echo "<td>" . $result['thinktank_name']. "</td>";
                        
                        
                        if(!empty($result['rt_count'])) {
                            echo "<td>" . $result['rt_count'] . "</td>";
                        }    
                        else { 
                            echo "<td></td>";
                        }                        
                        
                        echo "<td>" . $result['no_of_tweets']. "</td>";
                        /* echo "<td>" . $result['ave_rts']  . "</td>"; */
                        
                        /*
                        if(!empty($result['follower_count'])) {
                            echo "<td>" . $result['follower_count'] . "</td>";
                        }
                        else { 
                             echo "<td></td>";
                         }                       
                        */
                        
                        /*
                        if(!empty($result['no_of_interactions'])) {
                            echo "<td>" . $result['no_of_interactions'] . "</td>";
                        }
                        else { 
                            echo "<td></td>";
                        }
                        */
                        
                        echo "<td>" . $result['total_twitter_followers'] . "</td>";
                        
                        echo "<td>" . $mp_count[0]['mp_followers'] . "</td>";
                        
                        echo "<td>" . $wonk_count[0]['thinktank_followers'] . "</td>";
                        
                        /*echo "<td>" . $result['user_id'] . "</td>"; */
                    echo "</tr>";
                    
       
                }
            
            ?>
            </tbody>
        </table>
    </div>
    
    <div class='span4' >
        <div id='content_target'>
            
  
        </div>
    </div>
    
</div> 

<?  include('fragments/footer.php'); ?>
        

