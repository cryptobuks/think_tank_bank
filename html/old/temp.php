<? 
include('twitter_connect.php');
include('../header.php');

//get old twitter table useful rows 

$people = $db->fetch("SELECT * FROM people WHERE twitter_handle !=''  && twitter_handle !='-' LIMIT 1000");


foreach($people as $person) { 
    $data = $connection->get('users/show', array('screen_name' => $person['twitter_handle']));
    $query = "UPDATE people SET twitter_id='" .$data->id. "' WHERE twitter_handle='" .$person['twitter_handle'] . "' ";
    $db->query($query);
    echo "$query <br/>";
}


echo "</ol>";
include('../footer.php');

?>


