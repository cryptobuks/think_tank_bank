<? 
include('../header.php');

//get old twitter table useful rows 

$old_people = $db->fetch("SELECT * FROM people_old WHERE twitter_handle !='' ");

foreach($old_people as $old_person) { 
    $update_query = "UPDATE people SET twitter_handle='". $old_person['twitter_handle'] ."' WHERE name_primary= '". $old_person['name_primary'] ."'";
    echo "<br/> $update_query <br/>";
    $db->query($update_query);
}


include('../footer.php');

?>