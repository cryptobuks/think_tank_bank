<? 
include_once("../../ini.php"); 

class ifgPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Institute for Government");
        $people = $this->dom_query($this->base_url . "/about-us/our-people", 'h3');

        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {     
            $this->person_scrape_read(true, $this->thinktank_id);
         
            for ($i = 0; $i< count($people); $i++) { 
                //name
                $name = $this->dom_query($people[$i]['node'], "h3"); 
                $name = $name['text'];
            
                //Role
                $role = $this->dom_query($people[$i]['node'], ".jobTitle"); 
                $role = $role['text'];
            
                //Description             
                $description = $this->dom_query($people[$i]['node'], ".userIntro"); 
                $description = $description['text'];
            
                //Image URL 
                $image_url = $this->dom_query($people[$i]['node'], ".userThumb img"); 
                $image_url =$image_url['src'];
            
                $start_date = time();
                $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);  
                $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
            }
            //$this->staff_left_test($thinktank_id);
        }
    }
}


$scraper = new ifgPeople; 
$scraper->init();$scraper->add_footer();

?>