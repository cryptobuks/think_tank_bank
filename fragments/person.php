<?
include_once( __DIR__ . '/../ini.php');

@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$user_id = @$_GET['person_id'];
if(empty($user_id)) { 
    $user_id=$DEFAULT_ID;
}


$tweets_query = "SELECT *  FROM `tweets`
    JOIN people ON people.twitter_id = tweets.user_id
    WHERE people.person_id='$user_id'
    ORDER BY time DESC
    LIMIT 10";

$tweets = $db->fetch($tweets_query);

$twitter_id = $tweets[0]['user_id'];

$interactions_query = "SELECT *  FROM `people_interactions`
    JOIN people ON people.twitter_id = people_interactions.originator_id
    WHERE people_interactions.target_id='$twitter_id'
    ORDER BY time DESC
    LIMIT 5";

$interactions = $db->fetch($interactions_query);

$publications_query = "SELECT *  FROM `people`
    JOIN people_publications ON people.person_id = people_publications.person_id
    JOIN publications ON people_publications.publication_id = publications.publication_id
    WHERE people.person_id='$user_id'
    ORDER BY publication_date DESC LIMIT 10";

$publications = $db->fetch($publications_query);


$followers_query = "SELECT *, COUNT(*) FROM `people_followees` 
JOIN people ON people_followees.follower_id = people.twitter_id
WHERE `followee_id` = $twitter_id 
GROUP BY thinktank_name
ORDER BY COUNT(*) DESC"; 

$followers = $db->fetch($followers_query);

$followee_query = "SELECT *, COUNT(*) FROM `people_followees` 
JOIN people ON people_followees.followee_id = people.twitter_id
WHERE `follower_id` = $twitter_id 
GROUP BY thinktank_name
ORDER BY COUNT(*) DESC";

$followees = $db->fetch($followee_query);


$timeline = array_merge($interactions, $tweets);
usort($timeline, 'sortTimeline');
    
function sortTimeline($a, $b) {
    return ($b['time']) - ($a['time']);
}


?>  
    <div class='row' id='inspector'>
    <div class='span2'>
        <ul class="vertical_tabs">
            <li><a href=''>Tweets <i class="icon-chevron-right"></i></a></li>
            <li><a href=''>Network <i class="icon-chevron-right"></i></a></li>
            <li><a href=''>Publications <i class="icon-chevron-right"></i></a></li>            
        </ul>
    </div>
    
    <div class='span4'>

    <div id='tweets'>    
        <h3>Tweets</h3><?
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
    
    <div id='network'>    
        <h3>Followers</h3>
        <?
       
        $followers_json = array();
        $followees_json = array();
        $followers_colors = array();
        $followees_colors = array();
        
        foreach($followers  as $follower) {
            
            $data_elem = array();
            $data_elem['label'] = $follower['thinktank_name'];
            $data_elem['value'] = $follower['COUNT(*)'];            
            $followers_json[] =$data_elem;
            
            if($follower['thinktank_name'] == 'Con') {
                $followers_colors[] = '#0000ff';
            }
            else if($follower['thinktank_name'] == 'Lab') {
                $followers_colors[] = '#ff0000';
            }
            
            else if($follower['thinktank_name'] == 'LibDem') {
                $followers_colors[] = '#FFBB22';
            }
            
            else if($follower['thinktank_name'] == 'Journalist') {
                $followers_colors[] = '#9059ff';
            }
            else { 
                $followers_colors[] = '#14a2fc';
            } 
              
        }
        ?>

      
        <script>
            var followers_json = <?=json_encode($followers_json) ?>;
            var followers_colors = <?=json_encode($followers_colors) ?>;
        </script> 
        <div id='followers-donut'></div>
           
        
        <h3>Following</h3>
        <?
        foreach($followees  as $followee) {
            $data_elem = array();
            $data_elem['label'] = $followee['thinktank_name'];
            $data_elem['value'] = $followee['COUNT(*)']; 
            $followees_json[] =$data_elem; 
            
            if($followee['thinktank_name'] == 'Con') {
                $followee_colors[] = '#0000ff';
            }
            else if($followee['thinktank_name'] == 'Lab') {
                $followee_colors[] = '#ff0000';
            }
            
            else if($followee['thinktank_name'] == 'LibDem') {
                $followee_colors[] = '#FFBB22';
            }
            
            else if($followee['thinktank_name'] == 'Journalist') {
                $followee_colors[] = '#9059ff';
            }
            else { 
                $followee_colors[] = '#14a2fc';
            }
        }
        ?>
        <script>
            var followees_json = <?=json_encode($followees_json) ?>;
            var followees_colors = <?=json_encode($followee_colors) ?>;
        </script> 
        <div id='followees-donut'></div>

   
    </div>
    


    <div id='publications'>    
        ?><h3>Publicaitons</h3><?
        echo "<ul>";
        foreach($publications as $publication) {
            echo "<li>" .$publication['title']." " . date('F Y', $publication['publication_date'] ) . "</li>\n";
        }

        echo "</ul>";?>
    </div>    
    


?>
</div></div>
