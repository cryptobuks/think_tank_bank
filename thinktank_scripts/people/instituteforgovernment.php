<? 
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php')

class ifgPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Institute for Government");
        $people_element = $this->dom_query($this->base_url . "/about-us/our-people", '.field-items h3');
       
        if ($people_element=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
            
        else {     
            $this->person_scrape_read(true, $this->thinktank_id);
         
            for ($i = 1; $i< count($people_element); $i++) { 
                             
                //name                
                $name = $people_element[$i]['text'];

                //Image URL 
                $image_url = '';
                
                $start_date= 0;

                $db_output = $this->db->save_job($name, $this->thinktank_id, "", '', '', $start_date);  
                $this->person_loop_end($db_output, $name, $this->thinktank_id, '', '', '', $start_date);
                
            }
            $this->staff_left_test($this->thinktank_id);
        }
    }
}


$scraper = new ifgPeople; 
$scraper->init();$scraper->add_footer();

?>