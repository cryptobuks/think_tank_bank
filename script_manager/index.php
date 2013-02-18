<?
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    include(__DIR__ . '/../ini.php');
    include(__DIR__ '/twitter_scripts/twitter_connect.php'); 
    include(__DIR__ '/twitter_scripts/functions/interactions.php'); 
    include(__DIR__ '/twitter_scripts/functions/alien_interactions.php'); 
    include(__DIR__ '/twitter_scripts/functions/people_interactions.php'); 
    include(__DIR__ '/twitter_scripts/functions/rank_people.php'); 
    include(__DIR__ '/twitter_scripts/functions/people_followees.php'); 
    include(__DIR__ '/text_analysis_scripts/text_analysis.php'); 
?> 


<h1>Recurring Task - time is: <?= date("F j, Y, g:i a", time());  ?></h1>

<?
//fetch and display task list 
$tasks = $db->fetch('SELECT * FROM cron_manage');
foreach($tasks as $task) {
    if ($task['pointer'] == 1) {
        echo "<h3>--<strong>" . $task['task'] . "</strong>--</h3>";
    }
    else { 
        echo "<p>" . $task['task'] . "</p>";
    }
}


echo "<hr/><hr/>";

//Loop through and find the current task 
foreach($tasks as $task) {
    if ($task['pointer'] == 1) {
        
        /* -------- Alien Interactions   ---------*/ 
        if($task['task'] == 'alien_interactions') { 
            $results = alien_interactions($db, $connection);
            
            if (count($results) == 0) { 
                echo "<h1>This Twitter connection has failed</h1>"; 
            }
            else { 
                increment_counter($db);
            }
        }
        
        
        /* -------- People Interactions   ---------*/
        if($task['task'] == 'people_interactions') { 
            $results = alien_interactions($db, $connection);
            
            if (count($results) == 0) { 
                echo "<h1>This Twitter connection has failed</h1>"; 
            }
            else { 
                increment_counter($db);
            }
        }
        
        /* -------- Rank People --------- */
        if($task['task'] == 'rank_people') { 
            $people = $db->fetch("SELECT * FROM people where exclude != 1");
            rank_people($people, $db, $connection);
            increment_counter($db);
        }
        
        /* -------- Text Analysis --------- */
        if($task['task'] == 'text_analysis') { 
            text_analysis(1);
            increment_counter($db);
        }     
        
        /* -------- Text Analysis --------- */
        if($task['task'] == 'people_followees') { 
            people_followees($db, $connection);
            increment_counter($db);
        }           
        
                          
    }
}    

//set where to point to next
function increment_counter($db) { 
   
    $increment_counter = $db->fetch("SELECT id FROM cron_manage WHERE pointer = 1");
    $increment_counter = $increment_counter[0]['id'];
    
    $db->query("UPDATE cron_manage SET pointer = 0 WHERE id = '$increment_counter'");
    
    echo "<h1>CURRENT TASK ID = $increment_counter</h1>";
    
    $max_increment = $db->fetch("SELECT MAX(id) FROM cron_manage WHERE task != ''");

    if($increment_counter >= $max_increment[0]['MAX(id)']) { 
        $increment_counter = 1; 
    }
    else { 
         $increment_counter ++; 
    }    
    
    echo "<h1>NEXT TASK ID = $increment_counter</h1>";
    
    $db->query("UPDATE cron_manage SET pointer = 1 WHERE id = '$increment_counter'");
}
        

?>
