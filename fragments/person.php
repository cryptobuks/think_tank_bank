<?
include_once( __DIR__ . '/../ini.php');

@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$user_id = @$_GET['person_id'];
if(empty($user_id)) { 
    $user_id=170;
}


$tweets_query = "SELECT *  FROM `tweets`
    JOIN people ON people.twitter_id = tweets.user_id
    WHERE people.person_id='$user_id'
    LIMIT 5";

$tweets = $db->fetch($tweets_query);

$interactions_query = "SELECT *  FROM `people_interactions`
    JOIN people ON people.twitter_id = people_interactions.target_id
    WHERE people.person_id='$user_id'
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
WHERE `followee_id` =17921791 
GROUP BY organisation_type"; 

$followers = $db->fetch($followers_query);

?><div class='row'>
    <div class='span3'>
        
        <h3>Tweets</h3><?
        echo "<ul>";
        foreach($tweets as $tweet) {
            echo "<li>" .$tweet['text']."</li>";
        }

        echo "</ul>";?>         

    <h3>Mentions</h3><?
    echo "<ul>";
    foreach($interactions as $interaction) {
        echo "<li>" .$interaction['text']."</li>";
    }

    echo "</ul>";





?></div><div class='span3'><?

    ?><h3>Publicaitons</h3><?
    echo "<ul>";
    foreach($publications as $publication) {
        echo "<li>" .$publication['title']." " . date('F Y', $publication['publication_date'] ) . "</li>\n";
    }

    echo "</ul>";
    


?>
</div></div>
