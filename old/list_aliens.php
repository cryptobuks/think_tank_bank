<? 

include('twitter_connect.php');

include('../header.php');

echo "<h1>List ALiens</h1>";

$people = $db->fetch("SELECT * FROM people_followees WHERE  network_inclusion='1' GROUP BY followee_id LIMIT 0,1000");


foreach($people as $person) { 

    echo "<br/>";
    echo "RECORD ID" . $person['id'];
    $is_it_in = $db->fetch("SELECT * FROM alien_cache WHERE twitter_id='".$person['followee_id']."'");
    if (empty($is_it_in)) {
        $query = "INSERT INTO alien_cache (twitter_id, name, screen_name, description, followers_count) VALUES ('" . $person['followee_id'] . "', '', '', '', '')";
        echo $query;
        $db->query($query);
        echo "<br/>";
        echo "$query";
    }


    echo "<br/>";
}


?>



<? include('../footer.php'); ?>