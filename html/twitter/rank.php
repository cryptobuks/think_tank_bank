<? 


include('../header.php');

echo "<h1>Scan for followers</h1>";

$people = $db->fetch("SELECT * FROM people where exclude != 1");

foreach($people as $person) {
    
    //How many publications? 
    //$pub_result = $db->fetch("SELECT * FROM people_publications WHERE person_id='". $person['person_id'] . "'");
    //$number_of_pubs = count($pub_result); 
    
    //How many followers
    $query = "SELECT * FROM people_followees WHERE followee_id='". $person['twitter_id'] . "' && network_inclusion > 0 ";
    echo $query; 
    $follower_result = $db->fetch($query);
    
    $rank =0;
    
    foreach($follower_result as $follower) { 
        if($follower['network_inclusion'] == 4) { 
            $rank = $rank +1; 
        }
        else {
            $rank = $rank +2; 
        }
    } 
    
    //only recorded as a report author   
    $query = "SELECT * FROM people_thinktank WHERE person_id='". $person['person_id'] . "'";
    $job_result = $db->fetch($query);
    
    $status='clean'; 
    foreach($job_result as $job) { 
        if ($job['role']=='report_author_only') {
            $status = 'unclean';
        
        }
    }
    

    if ($status == 'unclean') { 
        $rank = $rank* 0.8;
    }
    

    
    echo "<h4>" . $person['name_primary'] . "</h4>";
    echo "<p>Status : $status </p>";
    

       
    $inset_query = "INSERT INTO people_rank (person_id, rank) VALUES ('". $person['person_id'] . "', '$rank')";
    $db->query($inset_query);
}


?>



<? include('../footer.php'); ?>