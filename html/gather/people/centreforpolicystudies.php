<? 
include_once("../../ini.php"); 

class cfpsPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 

        $this->init_thinktank("Centre for policy studies");
        $people    = $this->dom_query($this->base_url . "/about/staff/", '.userBox');
 
        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {     
            $this->person_scrape_read(true, $this->thinktank_id);
            $i = 0;
            foreach ($people as $person) { 
                 $this->person_loop_start($i); 
            
                //name
                $name = $this->dom_query($person['node'], "h3"); 
                $name = $name['text'];
            
                //Role
                $role = $this->dom_query($person['node'], ".jobTitle"); 
                $role = $role['text'];
            
                //Description             
                $description = $this->dom_query($person['node'], ".userIntro"); 
                $description = $description['text'];
            
                //Image URL 
                $image_url = $this->dom_query($person['node'], ".userThumb img"); 
                $image_url =$image_url['src'];
            
                $start_date = time();
                $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date); 
                $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                //$this->staff_left_test($thinktank_id);
                
                $i++;
            }
        }
    }
}


$scraper = new cfpsPeople; 
$scraper->init();

?>