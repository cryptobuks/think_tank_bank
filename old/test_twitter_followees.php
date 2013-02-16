<?
    include('../ini.php');
    @$url = explode("/",$_GET['url']);
    $db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);
    
    $rank_query = "SELECT * FROM people_rank ORDER BY rank DESC ";
    $ranks = $db->fetch($rank_query);
    
    foreach($ranks as $rank) { 
        $person = $db->fetch("SELECT * FROM people WHERE person_id='".$rank['person_id']."'");
        echo "<p>Testing: " .$person[0]['twitter_id'] . "</p>";
        if (!empty($person[0]['twitter_id'])) {
            
            $query ="SELECT * FROM people_followees WHERE follower_id='" . $person[0]['twitter_id'] . " ' && network_inclusion =1 ";
        
            $followers = $db->fetch($query);
            print_r($followers);
        }
        echo "<hr/>"; 
    } 
?>