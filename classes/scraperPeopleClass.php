<?
class scraperPeopleClass extends scraperBaseClass { 
    
    function init_thinktank($thinktank_name) {
        $thinktank              =   $this->db->search_thinktanks($thinktank_name);
        $this->thinktank_id     =   $thinktank[0]['thinktank_id'];  
        $this->base_url         =  $thinktank[0]['url'];        
        if ($_GET['debug'] == 'less') { 
            $this->base_url  = 'test/' . $thinktank[0]['computer_name'] .  "_less.html";   
        }
        if ($_GET['debug'] == 'more') { 
            $this->base_url  = 'test/' . $thinktank[0]['computer_name'] .  "_more.html";   
        }
    }
        
    function person_scrape_read($success, $thinktank_id, $error='') { 
        if ($success) { echo "Person scrape has read a person page for thinktank id $thinktank_id "; }
        else {
            $string =  "Person scrape has failed on thinktank id $thinktank_id ";
            if (!empty($error)) {$string .=' '. $error;} 
            echo $string; 
            $this->db->log("error", $string);
        }
    }
    
    function person_loop_start($iteration, $page='') { 
        if(!empty($page)) { 
            echo "<h2>Page: $page, Iteration: $iteration</h2>"; 
        }
        else { 
            echo "<h2>Iteration: $iteration</h2>"; 
        }
    }
    
    function person_loop_end($db_output, $name, $thinktank_id, $role, $description, $image_url, $start_date) { 
        foreach ($db_output as $output) { 
            echo "<p>".$output['message']."</p>";
            $this->db->log($output['type'], $output['message']);
        }
        echo "<p><strong>Thinktank id:</strong> $thinktank_id </p>";
        echo "<p><strong>Name:</strong> $name </p>";
        echo "<p><strong>Role:</strong> $role </p>";
        echo "<p><strong>Description:</strong> $description</p>";
        echo "<img src='$image_url' style='width:200px' />";    
        echo "<hr/>";       
    }  
    
    function staff_left_test($thinktank_id) {    
        echo "<h2>Testing to see if any staff have left</h2>";

        $jobs = $this->db->search_jobs("", $thinktank_id, true, true);

        $latest_update = $this->db->get_job_last_updated_date($thinktank_id);
        $delete_if_not_updated_since =  $latest_update - STAFF_LEFT_AFTER;
        
        echo "<p>Deleting if not updated since: "  . date("F j, Y, g:i a s", $delete_if_not_updated_since) ."</p>"; 
        
        foreach ($jobs as $job) {
             echo "<hr/>";
            $person = $this->db->search_people($job['person_id']);
            echo "<p>" . $person[0]['person']['name_primary'] . "last updated "  . date("F j, Y, g:i a s", $job['date_updated']) ."</p>";
            $delete_array = array();
            if($job['date_updated'] < ($delete_if_not_updated_since)) {
                //this person is longer listed as doing this roll at this thinktank
                $end_date = time();
                $this->db->save_job_end($job['job_id'], $end_date); 
                echo "<strong>DELETE</strong>";
                $string = $person[0]['person']['name_primary'] . " was deleted from think tank id " . $this->thinktank_id . " because they have left"; 
                $this->db->log("notice", $string);
            }
            else { 
                //still there - leave them current
                echo "<strong>STILL CURRENT</strong>";
            }
           
        }
    }
     
    function change_test($md5) { 
        $thinktank_data = $this->db->fetch("SELECT * FROM thinktanks WHERE thinktank_id=". $this->thinktank_id); 
        if (empty($thinktank_data[0]['md5'])) { 
            echo "First time this page has been scanned - writing MD5"; 
            $result = $this->db->query("UPDATE thinktanks SET md5='$md5' WHERE thinktank_id=".$this->thinktank_id);
        } 
        else if ($thinktank_data[0]['md5'] == $md5 ) { 
            $string =  "The change test on $this->thinktank_id revealed no change";
            echo $string; 
            $this->db->log("log", $string);
        }
        
        else {
            $string = "Thinktank named ".$thinktank_data[0]['name']." has changed it's staff page - please manually update"; 
            echo $string; 
            $this->db->log("notice", $string);
        }
        
    }
}
?>