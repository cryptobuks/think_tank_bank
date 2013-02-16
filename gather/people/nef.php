<? 
include_once("../../ini.php"); 

class nefPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("NEF");
        
        for($i=0; $i<15; $i++) {
            $url_suffix = "/about/who-we-are?page=".$i; 
            
            $people = $this->dom_query($this->base_url . $url_suffix, '.view li');


            $k=0;
            foreach($people as $person) {
                $this->person_loop_start($k, $i); 
                
                $name           = $this->dom_query($person['node'], '.content h3');
               
                $name           = trim($name['text']);
        
                $role           = $this->dom_query($person['node'], 'h3 + p');
                $role           = trim($role['text']);

                $description    = '';

                $image          =   $this->dom_query($person['node'], 'img');
                $image_url      =  @$image['src']; 
        
                $start_date = time();
                $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                @$this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);                
               
                $k++;
            }
        }   
        $this->staff_left_test($this->thinktank_id); 
    }
}

$scraper = new nefPeople; 
$scraper->init();$scraper->add_footer();

?>