<?
include_once( __DIR__ . '/../ini.php');

@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$hashtag = @addslashes(urldecode($_GET['thinktank_name']));

$horizon = time() - (60 * 60 * 24 * 2);

$hashtag_query = "SELECT *  FROM `tweets`
    JOIN people ON people.twitter_id = tweets.user_id
    WHERE tweets.text = '%$hashtag'
    && time > $horizon
    ORDER BY time DESC";

$hashtags = $db->fetch($hashtag_query);

$twitter_id = $hashtags[0]['user_id'];

?>  
<div id='inspector'>
    <div class='row-fluid' >    
        <div class='span12' >
            <h3 class='inspector_title'><?= $thinktank_name ?></h3>    
        </div>
    </div>
        
    <div class='row-fluid' >        


        <div class='span12'>

            <div id='tweets' class='infosection'>    
                <?
                echo "<ul>";
                foreach($hashtags  as $hashtag) {
                    echo "<li class='tweet_listing'>
                         <img class='twitter_image' src='" . $hashtag['twitter_image'] ."'/>
                         <a data-id='" . $hashtag['person_id'] ."' class='person_link'><strong>".$hashtag['name_primary']. "</strong></a>
                         <p>" . date("F j, Y, g:i a",$hashtag['time']) . "</p>
                         <p>" . $hashtag['text'] . "</p>
                           </li>\n";
                }

                echo "</ul>";?>   
            </div>
    
    
        </div> 
    </div>
</div>
