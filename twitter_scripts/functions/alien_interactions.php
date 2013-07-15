<? 

function alien_interactions($db, $connection) { 
 
    //Get the current index to scan
    $cron_monitor = $db->fetch("SELECT * FROM cron_monitor WHERE script='alien_interactions'");
    $index = $cron_monitor[0]['index_val'];
 
    $increment = 50;
    $max = $db->fetch(" SELECT count(*) FROM people join people_thinktank on people_thinktank.person_id=people.person_id WHERE twitter_id!=''");
    $max = $max[0]['count(*)']; 
 
    //Update the index for next time
    if($index < $max) { 
        $db->query("UPDATE cron_monitor SET  index_val = index_val+$increment WHERE script = 'alien_interactions'");
    }

    else { 
        $db->query("UPDATE cron_monitor SET  index_val = 0 WHERE script='alien_interactions'");   
    }

    $people_query = "SELECT * FROM aliens WHERE twitter_id!='' LIMIT $index,$increment";
    
    
    
    $people = $db->fetch($people_query);

    $return = twitter_interactions($people, $connection, $db, 1);
    
    return $return;
    
}

?>


