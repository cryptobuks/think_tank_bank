<? 

function people_interactions($db, $connection) { 
    //Get the current index to scan
    $cron_monitor = $db->fetch("SELECT * FROM cron_monitor WHERE script='people_interactions'");
    $index = $cron_monitor[0]['index_val'];
    
    $increment = 30;
    $max = $db->fetch("SELECT count(*) FROM people join people_thinktank on people_thinktank.person_id=people.person_id WHERE twitter_id!=''");
    $max = $max[0]['count(*)'];
    
     //Update the index for next time
    if($index < $max) { 
        $db->query("UPDATE cron_monitor SET index_val = index_val+$increment WHERE script = 'people_interactions'");
    }

    else { 
        $db->query("UPDATE cron_monitor SET index_val = 0 WHERE script='people_interactions'");   
    }
    
    $query = "SELECT * FROM people join people_thinktank on people_thinktank.person_id=people.person_id WHERE twitter_id!=''  && people.person_id='3836'  LIMIT $index, $increment" ;
    
    print_R($query);
    
    $people = $db->fetch($query);
    
    
      
    $return = twitter_interactions($people, $connection, $db,0);
}
?>

