<? 
include_once("../../ini.php"); 

class pePeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Policy Exchange");
        echo $this->thinktank_id;
        
        //TODO: People are not unique across these pages - test what happens
        $url_array = array(
        "Research"      => $this->base_url . '/people/research',
        "Directors"     => $this->base_url . "/people/directors",
        "Development"   => $this->base_url . '/people/development',
        "operations-and-communications" => $this->base_url . '/people/operations-and-communications'        
        );
        
        $k=0; 
        foreach($url_array as $role=>$url) {
            
            $people = $this->dom_query($url,'.row');
            
            
            if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}

            else {     
                $this->person_scrape_read(true, $this->thinktank_id);
           
                $number_of_rows = count($people);
            
                for ($i= 0; $i<$number_of_rows; $i++) { 
                    
                    $this->person_loop_start($i, $k); 
                    
                    //Name
                    $name       =  $this->dom_query($people[$i]['node'], '.element-itemname.first'); 
                    $name       =  $name['text'];
                
                    //Role
                    $role       =  $this->dom_query($people[$i]['node'], '.element-text.last');
                    $role       =  $role['text'];
                
                    //Description
                    $description = "";
               
                    //Image URL 
                    $image_url  =  $this->dom_query($people[$i]['node'], 'img');
                    $image_url  =  $image_url['src'];
                
                    $start_date = time();
                    $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);

                    $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                    $i++;
                }
            }    
            
            $k++;
        }
        
        //$this->staff_left_test($thinktank_id);
        
    }
}


$scraper = new pePeople; 
$scraper->init();$scraper->add_footer();

?>