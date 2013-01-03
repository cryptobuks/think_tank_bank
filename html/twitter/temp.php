<? 
include('../header.php');

//get old twitter table useful rows 

$old_people = $db->fetch("SELECT * FROM people_old WHERE twitter_hanle !='' ");

foreach($old_people as $old_person) { 
    
    $query = "SELECT * FROM people WHERE name_primary= '". $old_person['name_primary'] ."'"; 
    $new_person = $db->fetch($query); 
    
    $update_query = 


}


include('../header.php');

?>