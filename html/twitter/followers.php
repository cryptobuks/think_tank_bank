<? 

include('twitter_connect.php');

include('../header.php');

echo "<h1>Scan for followers</h1>";

$people = $db->fetch("SELECT * FROM people_followees WHERE network_inclusion>0");


foreach($relationships as $relationship) {
    if ($relationship['network_inclusion']==1) { 
        
    }

}




?>



<? include('../footer.php'); ?>