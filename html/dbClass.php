<?
class dbClass {
    
    //Basic DB functions 
    function __construct($db_location, $db_user_name, $db_password, $db_name) { 
        
        //connect to DB
        $this->db = MYSQL_CONNECT ($db_location, $db_user_name,  $db_password) or DIE ("Unable to connect to Database Server");
        MYSQL_SELECT_DB ($db_name, $this->db) or DIE ("Could not select database");
        
        //get the log class
        $status = outputClass::getInstance();
    }
       
    function query($sql) {
        $result = MYSQL_QUERY ($sql, $this->db) or DIE ("Invalid query: " . MYSQL_ERROR());
        return $result;
    }

    function fetch($sql) {
        $data = array();
        $result = $this->query($sql);
        WHILE($row = MYSQL_FETCH_ASSOC($result)) {
           $data[] = $row;
        }
        return $data;
     }
     
     
    //For ThinkTank Resource 
    function get_thinktanks() { 
        //lists all think tanks in the DB
        $query = "SELECT * FROM thinktanks"; 
        $result = $this->fetch($query);         
        return $result; 
    }

    function get_thinktank($target) { 
        //retrive a specific thinktank, either by id or name
        if (is_numeric($target)) { 
            $query = "SELECT * FROM thinktanks WHERE id= '$target'"; 
        }
        else { 
            $query = "SELECT * FROM thinktanks WHERE name= '$target'";
        }
        
        $result = $this->fetch($query);         
        return $result;
    }    
    
    //For Jobs Resource 
    function save_job($person_name, $thinktank, $role='', $description='', $image_url='') { 
        //where job is defined as a relationship between a thinktank and a person
        
        $person_name    = mysql_real_escape_string($person_name);
        $thinktank      = mysql_real_escape_string($thinktank);
        $role           = mysql_real_escape_string($role);
        $description    = mysql_real_escape_string($description);
        $image_url      = mysql_real_escape_string($image_url);
                          
         
        //check to see if this person exists
        $sql = "SELECT * FROM people WHERE name_primary = '$person_name'";         
        $person_search = $this->fetch($sql); 
        $date_created = time();
        
        //no person is found, then add one TODO: fallback to alternative names TODO: use the function in this class
        if(empty($person_search))  { 
            $sql = "INSERT INTO people (name_primary, date_created) VALUES ('$person_name', '$date_created')"; 
            $this->query($sql); 
            echo "<p>Person not found -- added to the DB</p>"; 
            $person_id = mysql_insert_id();
        }
        
        //person is found 
        else { 
            echo "<p>Person found</p>";
            $person_id = $person_search[0]['id'];
        }
        
        //get the Thinktank ID 
        $thinktank = $this->get_thinktank($thinktank);
        $thinktank_id = $thinktank[0]['id']; 
        if (empty($thinktank_id)) {"<p>Thinktank not found to save this job</p>";}
        
        //search to see if this job already exists, and hasn't ended 
        $sql = "SELECT * FROM people_thinktank WHERE person_id='$person_id' && thinktank_id='$thinktank_id' && role ='$role' && end_date = 0";
        
        $job = $this->fetch($sql);
        
        //job doesn't exist, create it 
        if (empty($job[0])) {
            echo "Job doesn't exist, creating it... ";
            $begin_date = time();
            $end_date = 0; //obviously this has no end date as yet 
            $date_updated = time(); 
            $sql = "INSERT INTO people_thinktank 
            (person_id, thinktank_id, role, begin_date, end_date, date_updated, description, image_url) 
            VALUES ('$person_id', '$thinktank_id', '$role', '$begin_date', '$end_date', '$date_updated', '$description', '$image_url')";
            $this->query($sql);
        }
        
        //job with matching description, person and thinktank does exist 
        else { 
            echo "this job already exists, updating it";
            $date_updated = time(); 
            $sql = "UPDATE people_thinktank SET role='$role', description='$description', image_url='$image_url', end_date='0', date_updated='$date_updated' WHERE person_id='$person_id' && thinktank_id='$thinktank_id' "; 
            $this->query($sql);
        }
    }
    
    function get_jobs() { 
        $query = "SELECT * FROM thinktanks"; 
        $result = $this->fetch($query);         
        return $result;            
    }
    
   
    function get_jobs_by_thinktank_current($thinktank_id) { 
        $query = "SELECT * FROM people_thinktank WHERE thinktank_id='$thinktank_id' && end_date=0"; 
        $result = $this->fetch($query);
        return $result;            
    }
    
    function get_job_last_updated_date($thinktank_id) { 
        $query = "SELECT * FROM people_thinktank WHERE thinktank_id='$thinktank_id' ORDER BY date_updated DESC"; 
        $result = $this->fetch($query);
        return $result[0]['date_updated'];
    }
    
    function save_job_end($job_id, $end_date) { 
        $sql = "UPDATE people_thinktank SET end_date='$end_date' WHERE id='$job_id'";
        echo $sql;
        $this->query($sql);
    }
    
    //For people resource
    function get_person($target) { 
        //retrive a specific thinktank, either by id or name
        if (is_numeric($target)) { 
            $query = "SELECT * FROM people WHERE id= '$target'"; 
        }
        else { 
            $query = "SELECT * FROM people WHERE name= '$target'";
        }
        
        $result = $this->fetch($query);         
        return $result;
    }    
    
    
    //For publications resource
    function save_publication($thinktank_id, $authors, $title, $url, $tags_object='', $publication_date, $image_url, $isbn, $price, $type) { 
    
    //test to see if authors exist
    $author_array_dirty = explode($authors, ',') 
    foreach ($author_array_dirty as $author) { 
       $author_array_clean[] = trim($author_array_dirty); 
    }
    
    foreach ($author_array_clean as $author) { 
       $author_data = $this->db->get_person($author);
       print_r($author_data);
       
    }    
     
    $this->db->get_person($target);
    
    
    
        //create authors if they do not, and raise an alert

    
    //test to see if this publication already exists
    
    
    //update if it exists
    
    
    //save if it doesn't 
    
    
    }
    
    
}

?>