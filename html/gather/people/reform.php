<? 
include_once("../../ini.php"); 

class reformPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Reform");
        $people = $this->dom_query($this->base_url. '/content_category/870/reform/our_people/executive_team', '.thumbmember');

        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {     
            $this->person_scrape_read(true, $this->thinktank_id);
            
        
            for ($i= 0; $i< count($people); $i++) {
                $this->person_loop_start($i); 
            
                //Name and Role
                $name       =  $this->dom_query($people[$i]['node'], 'h2'); 
                $name       =  $name['text'];
            
                //Role
                $role       = $this->dom_query($people[$i]['node'], '.h2_replace'); 
                $role       = $role['text'];
            
                //Description
                $description = $this->dom_query($people[$i]['node'], '.blog_summary_content p:eq(1)');
                $description = $description['text'];
           
                //Image URL 
                $image_url  =  $this->dom_query($people[$i]['node'], 'img');
                $image_url  =  $this->base_url . $image_url['src'];
            
                $start_date = time();
                $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);  
                $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
            }
            
            //$this->staff_left_test($this->thinktank_id);
        }  
    }
}


$scraper = new reformPeople; 
$scraper->init();

?>