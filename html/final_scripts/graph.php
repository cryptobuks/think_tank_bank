<? 


include('../header.php');

echo "<h1>Calculate Graph</h1>";

$relationships = $db->fetch("SELECT * FROM people_followees LIMIT 10000,5000");

foreach($relationships as $relationship) {
    
    $from_tt = false;
    $to_alien = false; 
    
    //establish what organisation the follower belongs to 
    $follower_id = $relationship['follower_id'];
    echo "<p>ID $follower_id</p>";
    
    $follower = $db->fetch("SELECT * FROM people 
    JOIN people_thinktank ON people_thinktank.person_id = people.person_id 
    JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id  
    WHERE people.twitter_id=$follower_id");  
    
    if (!empty($follower)) { 
        $follower_organisation = addslashes($follower[0]['name']);
        $from_tt = true; 
    }
      
    
    else { 
        $follower = $db->fetch("SELECT * FROM aliens where twitter_id=$follower_id"); 
        $follower_organisation = addslashes($follower[0]['organisation']);
    }
    
    
    
    
    //establish what organisation the followee belongs to 
    $followee_id = $relationship['followee_id'];
    
    $followee = $db->fetch("SELECT * FROM people 
    JOIN people_thinktank ON people_thinktank.person_id = people.person_id 
    JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id  
    WHERE people.twitter_id=$followee_id");  
    
    if (!empty($followee)) { 
        $followee_organisation = addslashes($followee[0]['name']);
    }
      
    
    else { 
        $to_alien = true;
        $followee = $db->fetch("SELECT * FROM aliens where twitter_id=$follower_id"); 
        $followee_organisation = addslashes($followee[0]['organisation']);
    }
    
   echo "<P>$followee_organisation --> $follower_organisation</p>";
   
   if ($from_tt && $to_alien) { 
       echo "<em>excluding</em>";
   }
   
   else {     
        
       //if the relationship already exists add one to the weight 
       $exists = $db->fetch("SELECT * FROM graph WHERE `from`='$follower_organisation' && `to`='$followee_organisation'"); 

       if (!empty($exists)) { 
           $db->query("UPDATE graph SET weight=weight+1 WHERE `from`='$follower_organisation' && `to`='$followee_organisation'"); 
           echo "<em>Adding weight</em>";
       }
       
       //otherwise, create it 
       else { 
           $db->query("INSERT INTO graph (`to`, `from`, weight) VALUES ('$followee_organisation', '$follower_organisation',1) ");   
           echo "<em>Creating</em>";
       }
       
       
   }

}


?>



<? include('../footer.php'); ?>