<? 

include('twitter_connect.php');

include('../header.php');

echo "<h1>Scan for followers</h1>";
/* THIS FILE TO DISCOVER THE NETWORK */ 


$people = $db->fetch("SELECT * FROM people WHERE twitter_id!='' LIMIT 400,100");

?>


<?

foreach($people as $person) {
    echo "<div id='about_" . $person['person_id'] . "'>";
    echo "<h1>".$person['name_primary']."</h1>";
    print_r($person);
    $search_string = "'" . $person['name_primary'] . "'";
    $cursor = -1;
    while ($cursor!=0){ 
        echo "<h1>NEW</h1>";
        $data = $connection->get('friends/ids', array('user_id' =>  $person['twitter_id'], 'cursor'=> $cursor));
        
        foreach($data->ids as $id) {
            
            $existing = $db->fetch("SELECT * FROM people_followees WHERE followee_id='" . $id . "' && follower_id='" . $person['twitter_id']. "'");
            if (count($existing)==0) { 
                $followee = $id;
                $follower = $person['twitter_id'];
                echo '.';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                $db->query("INSERT INTO people_followees (followee_id, follower_id, degree) VALUES ('$followee', '$follower', '1')");
                
            }
            else { 
                echo "duplicate record";
            }
        }
        $cursor = $data->next_cursor_str;
    }

}




?>



<? include('../footer.php'); ?>