<? 
include('../header.php');

//get old twitter table useful rows 

$old_people = $db->fetch("SELECT * FROM people_old WHERE twitter_handle !='' ");

echo "<ol>"; 
foreach($old_people as $old_person) { 

    
    $update_query = "SELECT * FROM  people WHERE name_primary= '". addslashes($old_person['name_primary']) ."' && twitter_handle ==''";
    
    $result = $db->fetch($update_query);
    
    if (count($result) == 0) { 
        echo "<br/>". $update_query . "<br/>" ;
        echo "<li>" .  $old_person['name_primary']. " -- ". $old_person['twitter_handle'] . "</li>" ;
    }    
    else { 
        
        $update_query = "UPDATE people SET twitter_handle='". $old_person['twitter_handle'] ."' WHERE name_primary= '". addslashes($old_person['name_primary']) ."'";
        //echo "<br/> $update_query <br/>";
        $db->query($update_query);        
        
    }
}


echo "</ol>";
include('../footer.php');

?>


