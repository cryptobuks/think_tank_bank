<? 
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php')

class ieaPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("IEA");
        $people = $this->dom_query($this->base_url. "/about/people", '.views-row');
        
        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {     
            $this->person_scrape_read(true, $this->thinktank_id);
   
            $i=0; 
            foreach($people as $person) {
                $this->person_loop_start($i);

                //name
                $name = $this->dom_query($person['node'], ".views-field-title"); 
                $name_array = explode('-', $name['text']);
                $name = $name_array[0];

                //Role
                $role = @$name_array[1];

                //Description             
                $description = $this->dom_query($person['node'], ".views-field-field-body-value");
                $description =  $description['text'];

                //Image URL 
                $image_url = $this->dom_query($person['node'], ".views-field-field-primary-image-fid img");
                $image_url =$image_url['src']; 

                $start_date = time();
                $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                $i++;
                
            }
            
            $this->staff_left_test($thinktank_id);
        }
    }
}


$scraper = new ieaPeople; 
$scraper->init();$scraper->add_footer();

?>