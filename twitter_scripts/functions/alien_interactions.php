<? 

function alien_interactions() { 
 
    //Get the current index to scan
    $cron_monitor = $db->fetch("SELECT * FROM cron_monitor WHERE script='alien_interactions'");
    $index = $cron_monitor[0]['index_val'];
 
    //Update the index for next time
    if($index < 800) { 
        $db->query("UPDATE cron_monitor SET  index_val = index_val+100 WHERE script = 'alien_interactions'");
    }

    else { 
        $db->query("UPDATE cron_monitor SET  index_val = 0 WHERE script='alien_interactions'");   
    }

    $people_query = "SELECT * FROM aliens WHERE twitter_id!='' LIMIT $index,100 ";

    $people = $db->fetch($people_query);

    $return = twitter_interactions($people, $connection, $db);
    
    return $return;
    
}

?>


