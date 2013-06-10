<? 

function people_followees($db, $connection) { 

    $cron_monitor = $db->fetch("SELECT * FROM cron_monitor WHERE script='people_followees'");
    $index = $cron_monitor[0]['index_val'];

    //Update the index for next time
    if($index < 400) { 
        $db->query("UPDATE cron_monitor SET  index_val = index_val+50 WHERE script = 'people_followees'");
    }

    else { 
        $db->query("UPDATE cron_monitor SET  index_val = 0 WHERE script='people_followees'");   
    }

    $people = $db->fetch("SELECT * FROM people WHERE twitter_id='1464' LIMIT 0,50");

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
            
                $is_person = $db->fetch("SELECT * FROM people WHERE twitter_id='" . $id . "'");
            
                $existing = $db->fetch("SELECT * FROM people_followees WHERE followee_id='" . $id . "' && follower_id='" . $person['twitter_id']. "'");
            
            
                if (count($existing)==0 && count($is_person)!=0) { 
                    $followee = $id;
                    $follower = $person['twitter_id'];
                    echo 'Adding<br/>';
                
                    $db->query("INSERT INTO people_followees (followee_id, follower_id, network_inclusion) VALUES ('$followee', '$follower', '2')");
                
                }
                else if (count($existing)==1) {
                    print_r($existing);
                    echo "duplicate record<br/>";
                }
            
                else if (count($is_person)==0) { 
                    echo "Connection is not to another TT person<br/>";
                }
            }
            $cursor = $data->next_cursor_str;
        }

    }
}



?>
