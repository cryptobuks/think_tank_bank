<? 
include_once("../../ini.php"); 

class tpaPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Tax Payer's Alliance");
        $people = $this->dom_query($this->base_url."/people", '#post-842 .entry_content div');

        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {
                    
            foreach ($people as $person) { 
                $this->person_loop_start($i); 
                
                //name
                $name = $this->dom_query($person['node'], "h3"); 
                $name = $name['text'];
            
                //Role
                $role = $this->dom_query($person['node'], "p"); 
                $role = $role['text'];
            
                //Description             
                $description =  "";
            
                //Image URL 
                $image_url = $this->dom_query($person['node'], "img"); 
                $image_url =$image_url['src']; 
            
        
                $start_date = time();
                $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);            
                //$this->staff_left_test($thinktank_id);
            }
        }    
    }
}


$scraper = new tpaPeople; 
$scraper->init();

?>