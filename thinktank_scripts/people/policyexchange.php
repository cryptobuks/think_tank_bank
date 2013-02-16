<? 
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php')

class pePeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Policy Exchange");
        echo $this->thinktank_id;
        
        $pagination_list = $this->dom_query($this->base_url . '/category/people/', '#paginationdropdown option');
        
        $number_of_pages = count($pagination_list);

        for($i=1; $i<=$number_of_pages; $i++) {
            
            $people = $this->dom_query($this->base_url . "/category/people/".$i, '.row');
            
            if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}

            else {     
                $this->person_scrape_read(true, $this->thinktank_id);
           
                $number_of_rows = count($people);
            
                for ($k= 0; $k<$number_of_rows; $k++) { 
                    
                    $this->person_loop_start($k, $i); 
                    
                    //Name
                    $name       =  $this->dom_query($people[$k]['node'], '.element-itemname.first'); 
                    $name       =  $name['text'];
                
                    //Role
                    $role       =  $this->dom_query($people[$k]['node'], '.element-text.last');
                    $role       =  $role['text'];
                
                    //Description
                    $description = "";
               
                    //Image URL 
                    $image_url  =  $this->dom_query($people[$k]['node'], 'img');
                    $image_url  =  $image_url['src'];
                
                    $start_date = time();
                    $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);

                    $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);

                }
            }    
        }
        
        $this->staff_left_test($thinktank_id);
    }
}


$scraper = new pePeople; 
$scraper->init();$scraper->add_footer();

?>