<?
include_once( __DIR__ . '/../ini.php');

@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$thinktank_name = @urldecode($_GET['thinktank_name']);

$horizon = time() - (60 * 60 * 24 * 2);

$tweets_query = "SELECT *  FROM `tweets`
    JOIN people ON people.twitter_id = tweets.user_id
    WHERE people.thinktank_name = '$thinktank_name'
    && time > $horizon
    ORDER BY time DESC";
    

$tweets = $db->fetch($tweets_query);

$twitter_id = $tweets[0]['user_id'];

$interactions_query = "SELECT *  FROM `people_interactions`
    JOIN people ON people.twitter_id = people_interactions.originator_id
    WHERE people.thinktank_name  = '$thinktank_name'
    && time > $horizon
    ORDER BY time DESC
    ";

$interactions = $db->fetch($interactions_query);

$publications_query = "SELECT *  FROM `people`
    JOIN people_publications ON people.person_id = people_publications.person_id
    JOIN publications ON people_publications.publication_id = publications.publication_id
    WHERE people.thinktank_name='$thinktank_name'
    ORDER BY publication_date DESC 
    LIMIT 20";


$publications = $db->fetch($publications_query);

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


$timeline = array_merge($interactions, $tweets);
usort($timeline, 'sortTimeline');
    
function sortTimeline($a, $b) {
    return ($b['time']) - ($a['time']);
}


?>  
<div id='inspector'>
    <div class='row-fluid' >    
        <div class='span12' >
            <h3 class='inspector_title'><?= stripslashes($thinktank_name) ?></h3>    
        </div>
    </div>
        
    <div class='row-fluid' >        
        <div class='span3'>
            <ul class='content_filters'>
                <li><a class='content_filter' data-filter='tweets'>Tweets <i class="icon-chevron-right"></i></a></li>
                <li><a class='content_filter' data-filter='followers'>Followers <i class="icon-chevron-right"></i></a></li>
                 <? if (count($publications) > 0) {  ?>
                <li><a class='content_filter' data-filter='publications'>Publications <i class="icon-chevron-right"></i></a></li>            
                <? } ?>
            </ul>
        </div>

        <div class='span9'>

            <div id='tweets' class='infosection'>    
                <?
                echo "<ul>";
                foreach($timeline  as $person) {
                    echo "<li class='tweet_listing'>
                         <img class='twitter_image' src='" . $person['twitter_image'] ."'/>
                         <a data-id='" . $person['person_id'] ."' class='person_link'><strong>".$person['name_primary']. "</strong></a>
                         <p>" . date("F j, Y, g:i a",$person['time']) . "</p>
                         <p>" . $person['text'] . "</p>
                           </li>\n";
                }

                echo "</ul>";?>   
            </div>
    
            <div id='followers' class='infosection'>    
            
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
                    var followees_json = '';
                </script> 
                <div id='followers-donut'></div>    
                <?
                foreach($followers_list as $follower) {
                    
                     echo "<li class='tweet_listing'>
                                <a data-id='" . $follower['follower_person_thinktank_name'] ."' class='person_link'><strong>".$follower['follower_person_name_primary']. "</strong></a> - " . $follower['follower_person_thinktank_name']
                           ."</li>\n";
                } 
                
                
                
                ?>   
            </div>

            <div id='publications' class='infosection'>    
            
                <?
                echo "<ul>";
                foreach($publications as $publication) {
                    echo "<li>
                            <a href='".$publication['url']."' >" .$publication['title']. "</a> (" . 
                            date('F Y', $publication['publication_date'] ) . 
                         ")</li>\n";
                }

                echo "</ul>";

                ?>
            </div>
        </div> 
    </div>
</div>
