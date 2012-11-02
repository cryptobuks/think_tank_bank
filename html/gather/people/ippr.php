<? 
include_once("../../ini.php"); 

class ipprPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("IPPR");
        
        $people = $this->dom_query($this->base_url . "/our-people", '.a_profile');
        
        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {     
            $this->person_scrape_read(true, $this->thinktank_id);
        
            $i=0; 
            
            foreach($people as $person) {

                $name = $this->dom_query($person['node'], 'h4');
                $name = $name['text'];
       
                $role = $this->dom_query($person['node'], 'p');
                $role = $role['text'];   
            
                $description = '';
            
                $image_url = $this->dom_query($person['node'], 'img');
                $image_url = "http://www.ippr.org" . $image_url['src'];
            
                $start_date = time();
                $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                
                $i++;
            } 
            
            $this->staff_left_test($this->thinktank_id);
        }    
    }
}


$scraper = new ipprPeople; 
$scraper->init();$scraper->add_footer();

?>