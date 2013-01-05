<? 

include('twitter_connect.php');

include('../header.php');

echo "<h1>Cache ALiens</h1>";

$people = $db->fetch("SELECT DISTINCT(followee_id) FROM people_followees WHERE  network_inclusion='1'");


foreach($people as $person) { 
    $data = $connection->get('users/show', array('user_id' =>  $person['followee_id']));
    $name = addslashes($data->name);
    $screen_name = addslashes($data->screen_name);
    $description = addslashes($data->description);
    $followers_count = $data->followers_count;    
    echo "<br/>";        
    $query = "INSERT INTO alien_cache (twitter_id, name, screen_name, description, followers_count) VALUES ('".$person['followee_id']."', '$name', '$screen_name', '$description', '$followers_count')";
    echo $query;
    $db->query($query); 
    echo "<br/>";
    echo "$query"; 
    echo "<br/>";
}


?>



<? include('../footer.php'); ?>