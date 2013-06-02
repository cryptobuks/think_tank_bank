<?  

    //this script takes people who work at think tanks 
    //and adds the name of the thinktank to the person record
    //this avoids three different joins further down the line... 
    
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    include('twitter_scripts/twitter_connect.php');

    $edit_query ="SELECT * FROM people 
        JOIN people_thinktank ON people_thinktank.person_id = people.person_id 
        JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id 
        WHERE twitter_id !=0
        LIMIT 10000";

    $people = $db->fetch($edit_query);

    echo "<ol>";
    foreach($people as $person) {
        
        $twitter_id = $person['twitter_id'];
        $id = $person['person_id']; 
        $old_time = time()-100 *60*60*24; 
        $query = "SELECT *, AVG(rts), Count(*) FROM `tweets`  
        WHERE user_id='$twitter_id' 
        && is_rt=0
        && time > $old_time 
        GROUP BY user_id='$twitter_id'
        
        ";
        
        echo $query . "<br/>";
        
        $tweets = $db->fetch($query);
        
        $ave = $tweets[0]['AVG(rts)'];
        $ave_tweets = $tweets[0]['Count(*)'] / 100;
        
        //$twitter_image    = addslashes($person['image_url']);
        
        $query          = "UPDATE people SET ave_rts='$ave', ave_tweets='$ave_tweets' WHERE person_id='$id'";
        echo $query . "<br/>";
        $db->query($query);
        
     }
    echo "</ol>";

?>
