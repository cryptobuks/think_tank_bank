<?

function twitter_id_to_name($twitter_id, $db) { 
    //get the name of the target 

    $target_name = $db->fetch("SELECT * from aliens WHERE twitter_id = '$twitter_id'");

    if(!isset($target_name[0])) { 
        $target_name = $db->fetch("SELECT * from people WHERE twitter_id = '$twitter_id'");
        
    }
    
    $target_name = $target_name[0]['twitter_handle'];
    
    return $target_name;
}

?>