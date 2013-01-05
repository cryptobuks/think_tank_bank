<? 



include('../header.php');

echo "<h1>Test for importance</h1>";

$relationships = $db->fetch("SELECT * FROM people_followees LIMIT 60000,30000");

?>


<?

foreach($relationships as $relationship) {
    
    echo "testing ". $relationship['followee_id'];
        
    $is_popular  = $db->fetch("SELECT * FROM people_followees WHERE followee_id='".$relationship['followee_id']."' && id!='".$relationship['id']."'");
    $is_person = $db->fetch("SELECT * FROM people WHERE twitter_id='".$relationship['followee_id']."'");
    
    $results = count($is_person) + count($is_popular);
    
    echo "is person: " . count($is_person) . "<br/>";
    echo "is popular: " . count($is_popular) . "<br/>";
    
    if(count($is_person)  > 0 ) {
        $query = "UPDATE people_followees SET network_inclusion='2' WHERE followee_id='".$relationship['followee_id'] . "' && follower_id='".$relationship['follower_id']."'";
        echo "is a person";
    }
    else if(count($is_popular)  > 40 ) {
        $query = "UPDATE people_followees SET network_inclusion='1' WHERE followee_id='".$relationship['followee_id'] . "' && follower_id='".$relationship['follower_id']."'";
        echo "is popluar";
    }
    
    else { 
        $query = "UPDATE people_followees SET network_inclusion='0' WHERE followee_id='".$relationship['followee_id'] . "' && follower_id='".$relationship['follower_id']."'";
        echo "exclude";        
    }
    
    $db->query($query);
    echo "<hr/>";
}




?>



<? include('../footer.php'); ?>