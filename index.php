<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    
    include('twitter_scripts/twitter_connect.php');

?>
    
            <div class='row'>
            <div class='span3 module'>
                <h2>Keywords</h2>
                <? include('fragments/keywords.php') ?>
            </div>
            
            <div class='span3 module'>
                <h2>Conversations</h2>
                <? include('fragments/influencers.php') ?>
            </div>
            
            <div class='span3 module'>
                <h2>Most Retweeted</h2>
                <? include('fragments/most_retweeted.php') ?>
            </div>
            
            <div class='span3 module'>
                <h2>Most Retweeted</h2>
                <?  //include('fragments/most_retweeted.php') ?>
            </div>
            
            
             <!--
            


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

            </div> -->
            

        
        <?  
        include('fragments/footer.php');
        ?>
        

