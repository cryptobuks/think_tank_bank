<? 

include('twitter_connect.php');

include('../header.php');

echo "<h1>Scan for followers</h1>";

$people = $db->fetch("SELECT * FROM people WHERE twitter_handle!='-' && twitter_handle!=''");

foreach($people as $person) {
    
    //How many publications? 
    $pub_result = $db->fetch("SELECT * FROM people_publications WHERE person_id='". $person['person_id'] . "'");
    $number_of_pubs = count($pub_result); 
    
    //How many followers
    $query = "SELECT * FROM people_followees WHERE followee_id='". $person['twitter_id'] . "' && network_inclusion > 0 ";
    echo $query; 
    $pub_result = $db->fetch($query);
    $number_of_followers = count($pub_result);    
    
    echo "<h4>" . $person['name_primary'] . "</h4>";
    echo "<p>Number of publications: $number_of_pubs </p>";
    echo "<p>Number of followers: $numner_of_followers </p>";
    
    $rank = ($number_of_pubs * 10) + ($number_of_followers * 3);   
    $inset_query = "INSERT INTO people_rank (person_id, rank) VALUES ('". $person['person_id'] . "', '$rank')";
    $db->query($inset_query);
}


?>



<? include('../footer.php'); ?>