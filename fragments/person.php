<?
include_once( __DIR__ . '/../ini.php');

@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$user_id = @$_GET['person_id'];
if(empty($user_id)) { 
    $user_id=$DEFAULT_ID;
}

$old = strtotime('last friday');

$tweets_query = "SELECT *  FROM `tweets`
    JOIN people ON people.twitter_id = tweets.user_id
    WHERE people.person_id='$user_id'  && time > $old && is_rt = 0
    ORDER BY rts DESC
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
    ORDER BY publication_date DESC ";

$publications = $db->fetch($publications_query);


$followers_query = "SELECT *, COUNT(*) FROM `people_followees` 
JOIN people ON people_followees.follower_id = people.twitter_id
WHERE `followee_id` = $twitter_id 
GROUP BY thinktank_name
ORDER BY COUNT(*) DESC"; 

$followers_grouped = $db->fetch($followers_query);


$followers_query = "SELECT * FROM `people_followees` 
JOIN people ON people_followees.follower_id  = people.twitter_id
WHERE `followee_id` = $twitter_id 
ORDER BY thinktank_name DESC"; 


$followers = $db->fetch($followers_query);


$followee_query = "SELECT *, COUNT(*) FROM `people_followees` 
JOIN people ON people_followees.followee_id = people.twitter_id
WHERE `follower_id` = $twitter_id 
GROUP BY thinktank_name
ORDER BY COUNT(*) DESC";

$followees_grouped = $db->fetch($followee_query);


$followee_query = "SELECT * FROM `people_followees` 
JOIN people ON people_followees.followee_id = people.twitter_id
WHERE `follower_id` = $twitter_id 
ORDER BY thinktank_name DESC";

$followees = $db->fetch($followee_query);




$timeline = array_merge($interactions, $tweets);
usort($timeline, 'sortTimeline');
    
function sortTimeline($a, $b) {
    return ($b['time']) - ($a['time']);
}


?>  
    <div class='row-fluid' id='inspector'>
        <div class='span11' class='inspector_title'><h3><?= $tweets[0]['name_primary'] ?></h3></div>
    </div>
    <div class='row-fluid'>
        <div id='followers' class='infosection span12'>    
                
            <h4>Followers</h4>
            <?
   
            $followers_json = array();
            $followees_json = array();
            $followers_colors = array();
            $followees_colors = array();
    
            foreach($followers_grouped  as $follower) {
        
                $data_elem = array();
                $data_elem['label'] = $follower['thinktank_name'];
                $data_elem['value'] = $follower['COUNT(*)'];            
                $followers_json[] =$data_elem;
        
                $followers_colors[] = getColour($follower['thinktank_name']);

            }
            ?>

  
            <script>
                var followers_json = <?=json_encode($followers_json) ?>;
                var followers_colors = <?=json_encode($followers_colors) ?>;
            </script> 
            <div id='followers-donut'></div>

        </div>
    </div>    
    <div class='row-fluid'>
        <h4>Most Retweeted</h4>
        <div class='span11 infosection' id='tweets' >        
            <?
            echo "<ul>";
            foreach($tweets  as $tweet) {
            ?>
            <blockquote class="twitter-tweet"><p>Search API will now always return "real" Twitter user IDs. The with_twitter_user_id parameter is no longer necessary. An era has ended. ^TS</p>&mdash; Twitter API (@twitterapi) <a href="https://twitter.com/twitterapi/status/<?= $tweet['tweet_id'] ?>" data-datetime="2011-11-07T20:21:07+00:00">November 7, 2011</a></blockquote>
            
            
            
            
            <?    
                /*
                echo "<li class='tweet_listing'>
                       
                        <p>" . $tweet['text'] . "</p>
                        <p><strong>RTs: " . $tweet['rts'] . " (" . date('D, F j', $tweet['time']) . ")</strong></p>
                     </li>\n"; */
            }

            echo "</ul>";?>   
            
            <script>
                twttr.widgets.load();
            </script>
            
        </div>    
    </div>
    

    <div class='row-fluid'>
        
        <div id='publications' class='span12'>            
                <? if (count($publications) > 0 ) { ?>
                      <h3>Publications</h3>
                        <?
                        echo "<ul>";
                        foreach($publications as $publication) {
                            echo "<li>
                                    <a href='".$publication['url']."' >" .$publication['title']. "</a> (" . 
                                    date('F Y', $publication['publication_date'] ) . 
                                 ")</li>\n";
                        }

                        echo "</ul>";
                    } else { 
                        echo "<p>No Publications</p>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>
