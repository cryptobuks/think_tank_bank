<? 


include('../header.php');

echo "<h1>Scan for followers</h1>";

$people = $db->fetch("SELECT * FROM people where exclude != 1");

foreach($people as $person) {
    
    //How many publications? 
    //$pub_result = $db->fetch("SELECT * FROM people_publications WHERE person_id='". $person['person_id'] . "'");
    //$number_of_pubs = count($pub_result); 
    
    //How many followers
    $query = "SELECT * FROM people_followees WHERE followee_id='". $person['twitter_id'] . "' ";
    $follower_result = $db->fetch($query);
    
    $number_of_followers = count($follower_result);
    
    
    $query = "SELECT * FROM people_interactions WHERE target_id='". $person['twitter_id'] . "' ";
    $number_result = $db->fetch($query);
    
    $number_of_mentions = count($follower_result);
    

    $rank = $number_of_followers + (3*  $number_of_mentions );

    

    
    echo "<h4>" . $person['name_primary'] . "</h4>";
    echo "<p>Status : $rank </p>";
    

       
    $inset_query = "INSERT INTO people_rank (person_id, rank) VALUES ('". $person['person_id'] . "', '$rank')";
    
    echo "<br/>". $inset_query."<br/>";
    
    $db->query($inset_query);
}


?>



<? include('../footer.php'); ?>