<?
class scraperPeopleClass extends scraperBaseClass { 
    function staff_left_test($thinktank_id) {
        
        echo "<h2>Testing to see if any staff have left</h2>";
        $jobs = $this->db->search_jobs("", $thinktank_id, true);
        
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
            }
            else { 
                //still there - leave them current
                echo "<strong>STILL CURRENT</strong>";
            }
           
        }
    }
}
?>