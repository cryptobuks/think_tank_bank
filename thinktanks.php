<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    $thinktank_id = @$_GET['id']; 
?>
    

<div class='row-fluid'>

    <div class='span6 thinktank_listing' >
        
        <ul>
        <?  
            if (empty($thinktank_id)) { 
                $query = "SELECT * from thinktanks ORDER BY name"; 
                $thinktanks = $db->fetch($query);
                foreach($thinktanks as $thinktank) { 
                    echo "<li><a href='thinktanks.php?id=" . $thinktank['thinktank_id'] . "'> " . $thinktank['name'] . "</a></li>";
                }
            } else {
                $query = "SELECT * from thinktanks WHERE thinktank_id = ". $thinktank_id; 
                
                $thinktanks = $db->fetch($query);
                
                echo "<h1>" . $thinktanks[0]['name'] . "</h1>";
                
                $people_query = "SELECT * FROM people WHERE thinktank_name ='" . $thinktanks[0]['name'] . "' && role!='report_author_only§' ";
               
                $people = $db->fetch($people_query);
                
                echo "<ul>"; 
                
                foreach($people as $person) {
                    echo "<li><img class='tt_image' src='". $person['twitter_image'] . "' />" . $person['name_primary'] . " -- " . $person['role'] . "</li><br/>";
                }
                
                echo "</ul>"; 
            }
        ?>
        </ul>
    </div>    
    

    <div class='span6' id='thinktank_inspector'>
        
        <? if (!empty($thinktank_id)) { 

            $thinktank_name = $thinktanks[0]['name'];

            $horizon = time() - (60 * 60 * 24 * 5);

            $tweets_query = "SELECT *  FROM `tweets`
                JOIN people ON people.twitter_id = tweets.user_id
                WHERE people.thinktank_name = '$thinktank_name'
                && time > $horizon
                ORDER BY time DESC LIMIT 30";




            $followee_query = "SELECT *,
            COUNT(DISTINCT follower_person.person_id) as counter,
            followee_person.thinktank_name AS followee_person_thinktank_name,
            followee_person.person_id AS followee_person_person_id
            FROM `people_followees`
            JOIN people AS follower_person ON people_followees.follower_id = follower_person.twitter_id
            JOIN people AS followee_person ON people_followees.followee_id = followee_person.twitter_id
            WHERE follower_person.thinktank_name = '$thinktank_name' 
            && follower_person.role != 'report_author_only'
            GROUP BY follower_person.thinktank_name
            ORDER BY counter DESC
            LIMIT 20";

            $followees_grouped = $db->fetch($followee_query);
            
            
            $followers_query = "SELECT *,
            COUNT(DISTINCT follower_person.person_id) as counter,
            
            follower_person.thinktank_name AS follower_person_thinktank_name,
            follower_person.person_id AS follower_person_person_id
            FROM `people_followees`
            JOIN people AS follower_person ON people_followees.follower_id = follower_person.twitter_id
            JOIN people AS followee_person ON people_followees.followee_id = followee_person.twitter_id
            WHERE followee_person.thinktank_name = '$thinktank_name' 
            && follower_person.role != 'report_author_only'
            && follower_person.thinktank_name != '$thinktank_name' 
            GROUP BY follower_person_thinktank_name
            ORDER BY counter DESC
            LIMIT 10"; 


            $followers_grouped = $db->fetch($followers_query);


            $followers_list =  "SELECT *,
            follower_person.thinktank_name AS follower_person_thinktank_name,
            follower_person.person_id AS follower_person_person_id,
            follower_person.name_primary AS follower_person_name_primary
            FROM `people_followees`
            JOIN people AS follower_person ON people_followees.follower_id = follower_person.twitter_id
            JOIN people AS followee_person ON people_followees.followee_id = followee_person.twitter_id
            WHERE followee_person.thinktank_name = '$thinktank_name' 
            && follower_person.thinktank_name != '$thinktank_name' 
            && follower_person.role != 'report_author_only'
            GROUP BY follower_person_name_primary
            ORDER BY follower_person.name_primary DESC
            ";


            $followers_list = $db->fetch($followers_list);





            
            
        ?>
        <div class='row-fluid' >        

            <div id='followers' class='infosection'>    
                <h3>Followers</h3>
                <?

                $followers_json = array();
                $followees_json = array();
                $followers_colors = array();
                $followees_colors = array();

                foreach($followers_grouped  as $follower) {

                    $data_elem = array();
                    $data_elem['label'] = $follower['follower_person_thinktank_name'];
                    $data_elem['value'] = $follower['counter'];            
                    $followers_json[] =$data_elem;

                    $followers_colors[] = getColour($follower['follower_person_thinktank_name']);

                }
                ?>


                <script>
                    var followers_json = <?=json_encode($followers_json) ?>;
                    var followers_colors = <?=json_encode($followers_colors) ?>;
                </script> 
                <div id='followers-donut'></div>



            </div>
            <div id='following' class='infosection'  >

               
                <?
                
                foreach($followees_grouped  as $followee) {
                    $data_elem = array();
                    $data_elem['label'] = $followee['followee_person_thinktank_name'];
                    $data_elem['value'] = $followee['counter']; 
                    $followees_json[] =$data_elem; 


                    $followee_colors[] = getColour($followee['followee_person_thinktank_name']);


                }
                
                ?>
                <script>
                    var followees_json = <?=json_encode($followees_json) ?>;
                    var followees_colors = <?=json_encode($followee_colors) ?>;
                    $(document).ready(function(){thinktanks.updateGraph()});
                </script> 
                <div id='followees-donut'></div>

                <h3>[Form to add and remove staff]</h3>

            </div>

   
              </div> 
          </div>
        
        <? } ?>
    </div>
    
</div> 

<?  include('fragments/footer.php'); ?>
        

