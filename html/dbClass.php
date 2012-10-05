<?
class dbClass {

    function __construct($db_location, $db_user_name, $db_password, $db_name) { 
        $this->db = MYSQL_CONNECT ($db_location, $db_user_name,  $db_password) or DIE ("Unable to connect to Database Server");
        MYSQL_SELECT_DB ($db_name, $this->db) or DIE ("Could not select database");
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
    
    function save_job($person_name, $thinktank, $role='', $description='', $image_url='', $start_date='', $end_date='') { 
        
        //where job is defined as a relationship between a thinktank and a person     
        //check to see if this person exists
        $sql = "SELECT * FROM people WHERE name_primary = '$person_name'";         
        $person_search = $this->fetch($sql); 
        $date_created = time();
        
        //no person is found, then add one TODO: fallback to alternative namess
        if(empty($person_search))  { 
            $sql = "INSERT INTO people (name_primary, date_created) VALUES ('$person_name', '$date_created')"; 
            $this->query($sql); 
            echo "<p>Person not found -- added to the DB</p>"; 
            $person_id = mysql_insert_id();
        }
        
        else { //person is found 
            echo "<p>Person found</p>";
            $person_id = $person_search[0]['id'];
        }
        
        //get the Thinktank ID 
        $thinktank = $this->get_thinktank($thinktank);
        $thinktank_id = $thinktank[0]['id']; 
        
        if (empty($thinktank_id)) {"<p>Thinktank not found to save this job</p>";}
        
        //search to see if this job already exists 
        $sql = "SELECT * FROM people_thinktank WHERE person_id='$person_id' &&  thinktank_id='$thinktank_id'";
        echo $sql; 
        
    }
    
    function save_publication() { 
    
    }
    
    
}

?>