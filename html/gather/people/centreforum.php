<? 
include_once("../../ini.php"); 

class cfPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Centre Forum");
        $people = $this->dom_query($this->base_url. '/index.php/staff', 'td');

        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {     
            $this->person_scrape_read(true, $this->thinktank_id);
            $rows_to_try = count($people); 
            for ($i= 0; $i< $rows_to_try; $i++) {
            
                if ($this->dom_query($people[$i]['node'], 'h3') != 'no results') { 

                    //Name and Role
                    $name       =  $this->dom_query($people[$i]['node'], 'h3');
                    $name       =  $name['text'];
            
                    //Role
                    $role       = $this->dom_query($people[$i]['node'], 'h3 + p'); 
                    $role       = $role['text'];
            
                    //Description
                    $description = '';
           
                    //Image URL 
                    $image_url  =  '';

            
                    $start_date = time();
                    $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);  
                    $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);

                }           
            }
          
        //$this->staff_left_test($thinktank_id);
        }        
    }
}


$scraper = new cfPeople; 
$scraper->init();$scraper->add_footer();

?>