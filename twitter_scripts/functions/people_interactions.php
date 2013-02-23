<? 

function people_interactions($db, $connection) { 
    //Get the current index to scan
    $cron_monitor = $db->fetch("SELECT * FROM cron_monitor WHERE script='people_interactions'");
    $index = $cron_monitor[0]['index_val'];

    //Update the index for next time
    if($index < 800) { 
        $db->query("UPDATE cron_monitor SET  index_val = index_val+100 WHERE script = 'people_interactions'");
    }

    else { 
        $db->query("UPDATE cron_monitor SET  index_val = 0 WHERE script='people_interactions'");   
    }
    
    
    $people = $db->fetch("SELECT * FROM people join people_thinktank on people_thinktank.person_id= people.person_id WHERE twitter_id!='' && role LIKE 'official twitter account'  LIMIT $index, 100" );
    //$people = $db->fetch("SELECT * FROM people join people_thinktank on people_thinktank.person_id= people.person_id WHERE twitter_id!='' && role LIKE 'official twitter account'" );
    
    $return = twitter_interactions($people, $connection, $db,0);
}
?>

