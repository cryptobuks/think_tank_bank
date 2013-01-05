<? 

include('twitter_connect.php');

include('../header.php');

echo "<h1>Scan for followers</h1>";

$aliens = $db->fetch("SELECT * FROM alien_cache LIMIT 120,100");

foreach($aliens as $alien) {
    echo 'checking for match with '. $alien['name'] . "<br/>";
    $data = $connection->get('friends/ids', array('user_id' =>  $alien['twitter_id']));
    foreach($data->ids as $id) {
    
        $query = "SELECT * FROM people WHERE twitter_id='$id'"; 
        //echo $query;
        $is_person = $db->fetch($query); 
        if (count($is_person) >= 1){
            echo 'Found match between '. $alien['name'] . ' and ' . $is_person[0]['name_primary'] ."<br/>";
            $followee = $is_person[0]['twitter_id'];
            $follower = $alien['twitter_id'];
            $db->query("INSERT INTO people_followers (followee_id, follower_id, degree) VALUES ('$followee', '$follower', '3')");
        }
        else { 
            //echo " no";
        }
    }
    echo "<br/>";
}


?>



<? include('../footer.php'); ?>