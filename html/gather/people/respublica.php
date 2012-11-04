<? 
include_once("../../ini.php"); 

class respublicaPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Respublica");
        $people = $this->dom_query($this->base_url. '/authors', '.authors .author');
        
        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {     
            $this->person_scrape_read(true, $this->thinktank_id);        
            for ($i= 0; $i< count($people); $i++) {
                 $this->person_loop_start($i); 
                
                //Name and Role
                $name       =  $this->dom_query($people[$i]['node'], '.authorlink'); 
                $name       =  explode(' - ', $name['text']);
                $role       =  $name[1];
                $name       =  $name[0];
            
                //Description
                $description = $this->dom_query($people[$i]['node'], '.miniBio');
                $description = $description['text'];
           
                //Image URL 
                $image_url  =  $this->dom_query($people[$i]['node'], 'img');
                $image_url  =  $this->base_url . $image_url['src'];
            
                $start_date = time();
                $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                $i++;
            }
         
            $this->staff_left_test($this->thinktank_id);
        }    
    }
}


$scraper = new respublicaPeople; 
$scraper->init();$scraper->add_footer();

?>