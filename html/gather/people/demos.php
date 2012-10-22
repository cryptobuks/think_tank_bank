<? 
include_once("../../ini.php");

class demosPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Demos"); 
        $people = $this->dom_query($this->base_url . '/people', '.person');
        
        if (count($people)==0) {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {     
            $this->person_scrape_read(true, $this->thinktank_id);
            
            $i=0;
            foreach($people as $person) {
                $this->person_loop_start($i); 
                $h4     = $this->dom_query($person['node'], 'h4');
                $p      = $this->dom_query($person['node'], 'p');
                $image  = $this->dom_query($person['node'], '.person-image');

                $name = trim($h4[0]['text']);
                $role = trim($h4[1]['text']); 
                $description = @$p[1]['text']; 
                
                $image_url = $this->base_url . "/" . @$image['src']; 
                
                $start_date = time();
                $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                $i++;
            }
            $this->staff_left_test($thinktank_id);
        }
    }
}

$scraper = new demosPeople; 
$scraper->init();

?>