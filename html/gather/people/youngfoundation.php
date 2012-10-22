<? 
include_once("../../ini.php");

class yfPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Young Foundation");
        $people = $this->dom_query($this->base_url . "/about-us/people/staff", '.listing_box');

        if (count($people)==0) {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {     
            $this->person_scrape_read(true, $this->thinktank_id);
   
            $i=0; 
            foreach($people as $person) {
                $this->person_loop_start($i); 

                $name = $this->dom_query($person['node'], '.listing_title a');
                $name = $name['text'];
       
                $role = "Staff";

                $description = $this->dom_query($person['node'], '.listing_content');
                $description = $description['text'];
            
                $image_url = $this->dom_query($person['node'], 'img');
                $image_url = $this->base_url . $image_url['src'];
            
                $start_date = time();
                $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                
                $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                $i++;
            } 
        }    
    }
}


$scraper = new yfPeople; 
$scraper->init();

?>