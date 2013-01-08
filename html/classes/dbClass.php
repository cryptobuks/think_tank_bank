<?
class dbClass {
    
    /*
    * Following functions to deal with thinktank resource 
    *
    *  search_thinktanks($target)
    *  $target is a thinktank_id or thinktank name 
    *  
    *
    */
    
    /* 
    
    TRUNCATE TABLE log; 
    TRUNCATE TABLE people;
    TRUNCATE TABLE people_publications; 
    TRUNCATE TABLE people_thinktank;
    TRUNCATE TABLE publications;
    
    */
    
    //Connect to DB
    function __construct($db_location, $db_user_name, $db_password, $db_name) { 
        
        $this->db = MYSQL_CONNECT ($db_location, $db_user_name,  $db_password) or DIE ("Unable to connect to Database Server");
        MYSQL_SELECT_DB ($db_name, $this->db) or DIE ("Could not select database");
    }
    
    //Basic query    
    function query($sql) {
        $result = MYSQL_QUERY ($sql, $this->db) or DIE ("Invalid query: " . MYSQL_ERROR());
        return $result;
    }
    
    //Return array 
    function fetch($sql) {
        $data = array();
        $result = $this->query($sql);
        WHILE($row = MYSQL_FETCH_ASSOC($result)) {
           $data[] = $row;
        }
        return $data;
     }
     
     
    /*
    * Following functions to deal with thinktank resource 
    *
    *  search_thinktanks($target)
    *  $target is a thinktank_id or thinktank name 
    *   
    *  search_thinktanks()
    */ 
    
    function search_thinktanks($target="") { 
        //retrive a specific thinktank, either by id or name
        if (is_numeric($target)) { 
            $query = "SELECT * FROM thinktanks WHERE thinktank_id= '$target'"; 
        }
        
        else if (empty($target)) { 
            $query  = "SELECT * FROM thinktanks"; 
        }
        
        else { 
            $target = mysql_real_escape_string($target);
            $query  = "SELECT * FROM thinktanks WHERE name= '$target' or computer_name= '$target'";
        }
        
        $result = $this->fetch($query);         
        return $result;
    }    
    
    
    /*
     * Following functions to deal with person resource
     *
     *  search_people()
     *  save_person()
     *
     */
    
    function search_people($target) { 
        //retrive a specific thinktank, either by id or name
        if (is_numeric($target)) { 
            $query = "SELECT * FROM people WHERE person_id= '$target'"; 
        }
        else { 
            $target= mysql_real_escape_string($target);  
            $query = "SELECT * FROM people WHERE name_primary = '$target'";
        }
    
        $results = $this->fetch($query);
        $output = array();
        foreach($results as $result) { 
            $jobs = $this->search_jobs($result["name_primary"], '', '', false, false);
            $temp_output['person'] = $result;
            $temp_output['jobs'] = $jobs;
            $output[] = $temp_output;
        }
        
        return $output;
    }   

    function save_person($name_primary, $name_object='', $twitter_handle='') { 

        if (str_word_count($name_primary) >= 2 && strlen($name_primary) >= 5) { 
        
            $date = time();
            $name_primary = mysql_real_escape_string(trim($name_primary)); 
            $name_object = mysql_real_escape_string(trim($name_object));
            $twitter_handle = mysql_real_escape_string($twitter_handle);
        
            $extant_person = $this->search_people($name_primary);
            if (!empty($extant_person)) {
                if (empty($name_object)){$name_object=$extant_person[0]['name_object'];}
                if (empty($twitter_handle)){$twitter_handle=$extant_person[0]['twitter_handle'];}
           
                $sql = "UPDATE people SET name_object='$name_object', twitter_handle='$twitter_handle', date_updated='$date'  WHERE name_primary LIKE '%$name_primary%'"; 
                $this->query($sql);
            }
            else {
                $sql = "INSERT INTO people (name_primary, name_object, twitter_handle, date_created, date_updated, from_csv) VALUES ('$name_primary', '$name_object', '$twitter_handle', '$date', '$date', 1)"; 
                $this->query($sql);
            }
            return mysql_insert_id();
        }
        
        else { 
            return array('message'=> "tried to save invalid name: $name_primary", 'type'=>'error' );
        }
    }     
       
    
    /*
     * Following functions to deal with Jobs resource
     *
     *  search_jobs()     
     *  save_job()
     *  get_job_last_updated_date($thinktank_id)
     *  save_job_end($job_id, $end_date)
     *
     *
     */
     
    function search_jobs($name='', $thinktank_id='', $role='',  $current_job=false, $exclude_report_authors=false) {
        $sql = "SELECT * FROM people INNER JOIN people_thinktank ON people.person_id=people_thinktank.person_id"; 

        $where_clause_array=array();

        if(!empty($name)) {
            $name = mysql_real_escape_string($name);  
            $where_clause_array[] = " name_primary = '$name' ";
        }

        if(!empty($thinktank_id)) {
            $where_clause_array[] = " thinktank_id = '$thinktank_id' ";
        }

        if(!empty($role)) {
            $name = mysql_real_escape_string($role);  
            $where_clause_array[] = " role = '$role' ";
        }     
        
        if($exclude_report_authors) {  
            $where_clause_array[] = " role != 'report_author_only' ";
        }           

        if ($current_job) { 
            $where_clause_array[] = " end_date=0";
        }

        if (count($where_clause_array) > 0) { 
            $sql .= " WHERE " . implode('&&', $where_clause_array); 
        }

        $result = $this->fetch($sql);
        return($result);
    }
          

    function save_job($person_name, $thinktank_id, $role='', $description='', $image_url='', $end_date='0') { 
        
        $output = array();
        
        //remove "edited by"s etc
        $person_name = str_ireplace("edited by", "", $person_name);
        $person_name = str_ireplace("et al", "", $person_name);
        $person_name = str_ireplace("Foreword by", "", $person_name);
        $person_name = str_ireplace("Edited by", "", $person_name);        
        
        //check to see if this person exists         
        $person_search = $this->search_people($person_name);

        if (str_word_count($person_name) <2) { 
            $output[] = array('message' => "$person_name is not a valid name when trying to save a person to TTID: $thinktank_id", 'type'=>'error');
        }
        
        else { 
        
            //no person is found, then add one 
            if(empty($person_search))  {  
                $person_id = $this->save_person($person_name, '', '', ''); 
                if (is_numeric($person_id)) { 
                    $output['person_id'] = $person_id;
                    $output[] = array("message" => "Person added to the DB while saving a new job, thinktank_id= $thinktank_id, person name = $person_name", 'type'=>'log');
                
                }
                else { 
                    $output[] = array("message" => "Trying to save a new job, person name was not well formed, thinktank_id= $thinktank_id, person name = $person_name", 'type'=>'error');
                    $output = array_merge($output, $person_id);
                }

            }
            
            //person is found 
            else { 
                $person_id = $person_search[0]['person']['person_id'];
                if (is_numeric($person_id)) { 
                    $output['person_id'] = $person_id;
                    $output[] = array("message" => "Person already exists for new job, thinktank_id= $thinktank_id, person name = $person_name", 'type'=>'log');                   
                    
                }
                else { 
                    $output[] = array("message" => "While saving a job a person was found, however their ID number was not, thinktank_id= $thinktank_id, person name = $person_name", 'type'=>'error');                   
                }                
                
            }

            $job = $this->search_jobs($person_name, $thinktank_id, '', true, false);        
        
            $role           = mysql_real_escape_string($role);
            $description    = mysql_real_escape_string($description);
            $image_url      = mysql_real_escape_string($image_url);
            $date_created   = time();
        
            //job doesn't exist, create it 
            if (empty($job[0])) {
                $output[] = array('message' => "Job for person $person_name doesn't exist yet at thinktank id $thinktank_id, creating it... ", 'type'=>'log');
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
                $job_id         = $job[0]['job_id'];
                $output[]       = array('message' => "Job for $person_name already exists at thinktank id $thinktank_id, updating it. JOB: $job_id ", 'type'=>'log');
                $date_updated   = time(); 
                $sql = "UPDATE people_thinktank SET role='$role', description='$description', image_url='$image_url', end_date='0', date_updated='$date_updated' WHERE job_id='$job_id'"; 
                $this->query($sql);
            }
        }    
        
       
        
        return $output;
    }
    

    
    function get_job_last_updated_date($thinktank_id) {
        $thinktank_id = mysql_real_escape_string($thinktank_id);  
        $query = "SELECT * FROM people_thinktank WHERE thinktank_id='$thinktank_id' ORDER BY date_updated DESC"; 
        $result = $this->fetch($query);
        
        return $result[0]['date_updated'];
    }
    
    function save_job_end($job_id, $end_date) { 
        $sql = "UPDATE people_thinktank SET end_date='$end_date' WHERE job_id='$job_id'";
        $this->query($sql);
    }
    
    

    /*
     * Following functions to deal with publications resource
     *
     *  save_publication()
     *  get_publication()
     *
     */
    function save_publication($thinktank_id, $authors, $title, $url, $tags_object='', $publication_date, $image_url, $isbn, $price, $type) { 
        
        $output = array();
        $title = trim($title);
        
        if(!empty($authors)) { 
            //test to see if authors exist
            $author_ids = array();
            $author_array_dirty = explode(',', $authors);
            foreach ($author_array_dirty as $author) { 
               $author_array_clean[] = trim($author); 
            }
    
            foreach ($author_array_clean as $author) { 
                $author_data = $this->search_jobs($author, $thinktank_id, '', true, false);
            
                if (empty($author_data[0])){     
                    $output[] = array('message' => "Author '$author' is not currently associated with this thinktank, they will be recorded as a report author for $thinktank_id", 'type'=>'notice');
                    $author_id = $this->save_job($author, $thinktank_id, "report_author_only");
                    $author_ids[] = $author_id['person_id'];
                    $output = array_merge($author_id); 
                }
            
                else { 
                    $output[] =  array('message' => "Author '$author' already exists as a person", 'type'=> 'log'); 
                    $author_ids[] = $author_data[0]['person_id'];     
                }
            }    
        
        }
        else { 
                $output[] = array('message' => "No authors for this publication", 'type'=>'log');
        }
        
        $extant = $this->search_publications($title, $thinktank_id);
        
        $thinktank_id       = mysql_real_escape_string($thinktank_id); 
        $title              = mysql_real_escape_string($title); 
        $url                = mysql_real_escape_string($url); 
        $tags_object        = mysql_real_escape_string($tags_object); 
        $publication_date   = mysql_real_escape_string($publication_date); 
        $image_url          = mysql_real_escape_string($image_url);
        $isbn               = mysql_real_escape_string($isbn);
        $price              = mysql_real_escape_string($price);
        $type               = mysql_real_escape_string($type);        

        //save a new publication
        if (empty($extant[0])) { 
            $sql = "INSERT INTO publications (thinktank_id, title, url, tags_object, publication_date, image_url, isbn, price, type) VALUES ('$thinktank_id', '$title', '$url', '$tags_object', '$publication_date', '$image_url', '$isbn', '$price', '$type')";
            $resource = $this->query($sql);
           
            $output[] = array('message' => "$title  is a new publication for thinktank id $thinktank_id", "type"=>'notice');
            $pub_id  = mysql_insert_id();
            
            //link publications to authors 
            if(!empty($author_ids)) {
                foreach($author_ids as $author_id) { 
                    $sql = "INSERT INTO people_publications (person_id, publication_id) VALUES ('$author_id', '$pub_id' )"; 
                    $this->query($sql);
                } 
            }
        }
        
        //update and old one
        else {
            $pub_id = $extant[0]['publication_id']; 
            $sql = "UPDATE publications SET url='$url', tags_object='$tags_object', publication_date='$publication_date', image_url='$image_url', isbn='$isbn', price='$price', type='$type' WHERE publication_id='$pub_id'";
            $this->query($sql);
            $output[] = array('message' => "$title  is an existing publication for thinktank id $thinktank_id", 'type'=> 'log');
        }

        return $output;
    }
    
    function search_publications($title='', $thinktank_id='') {
        
        $sql = "SELECT * FROM publications ";
        $title = mysql_real_escape_string($title);
        $where_clause_array=array();
        
        if(!empty($title)) {
            $title = mysql_real_escape_string($title);  
            $where_clause_array[] = " title LIKE '%$title%' ";
        }
        
        if(!empty($thinktank_id)) {
            $where_clause_array[] = " thinktank_id = '$thinktank_id' ";
        }
        
        if (count($where_clause_array) > 0) { 
            $sql .= " WHERE " . implode('&&', $where_clause_array); 
        }
        $sql .= " ORDER BY publication_date DESC";

        $results = $this->fetch($sql);
        
        //add author information
        return($results);
    }    
    
    /*
     * Author Resource 
     *
     *  search_authors()
     *  
     *
     */    
    function search_authors($publication_id) { 
        $sql = "SELECT * FROM people_publications INNER JOIN people ON people_publications.person_id=people.person_id WHERE people_publications.publication_id = '$publication_id' "; 
        $authors = $this->fetch($sql);
        return($authors);
    }
    
    /*
     * Save to the log
     *
     *  log()
     *  
     *
     */
     
    function log($type, $content) { 
        $date  = time();
        $content = addslashes($content);
        $sql = "INSERT INTO `log` (type, content, `date`) VALUES ('$type', '$content', '$date')";
        $this->query($sql); 
    }
    
    function get_tags() {
        $tags = $this->fetch("SELECT * FROM tags");
        return $tags;
    }
         
    
    
}

?>