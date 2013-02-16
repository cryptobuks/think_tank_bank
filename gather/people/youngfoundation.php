<? 
include_once("../../ini.php"); 

class yfPeople extends scraperPeopleClass {
    
    function init() {
        
        $this->init_thinktank("Young Foundation");
        
        //get last page 
        $pagination = $this->dom_query($this->base_url . "/about-us/people/staff", '.pagination-list a');
        if ($pagination=='no results') {$this->person_scrape_read(false, $this->thinktank_id, "couldn't read pagination");}
        
        else {
            $page_count = count($pagination)-1;
        
            //set up thinktank 
            for($i=0; $i<=$page_count; $i++) {
                $people = $this->dom_query($this->base_url . "/about-us/people/staff/page/".$i, '.listing');

                if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
            
                else {     
                    $this->person_scrape_read(true, $this->thinktank_id);
   
                    $k=0; 
                    foreach($people as $person) {
                        $this->person_loop_start($k,$i); 

                        $name = $this->dom_query($person['node'], 'h3');
                        $name = $name['text'];
       
                        $role = "Staff";

                        $description = $this->dom_query($person['node'], '.job_title');
                        $description = $description['text'];
            
                        $image_url = $this->dom_query($person['node'], 'img');
                        $image_url =  $image_url['src'];
            
                        $start_date = time();
                        $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);  
                        @$this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                        $k++;
                    } 
                }
            }   
            
            $this->staff_left_test($this->thinktank_id);
        }    
    }
}


$scraper = new yfPeople; 
$scraper->init();$scraper->add_footer();

?>