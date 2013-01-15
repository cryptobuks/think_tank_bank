<? 
include('../header.php');


echo "<h1>Collect Twitter Activity</h1>";

$people = $db->fetch("SELECT * FROM people WHERE twitter_handle !=''");

foreach($people as $person) {
    echo "<h3>".$person['twitter_handle']."<h3>";

    
    $data = $connection->get('users/show', array('screen_name' => $person['twitter_handle']));

    
    echo "<p>" . $data->followers_count . "<p>";
    $date       = time();
    $person_id  = $person['person_id'];
    $twitter_followers = $data->followers_count;
    $db->query("INSERT INTO people_twitter_rank (person_id, twitter_followers, date) VALUES ('$person_id', '$twitter_followers', '$date')"); 
}




?>



<? include('../footer.php'); ?>