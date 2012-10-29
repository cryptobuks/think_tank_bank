<? 
include_once("../../ini.php"); 

class cfsjPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Centre For Social Justice");
            
        $people = $this->dom_query($this->base_url . '/default.asp?pageRef=49', '#mainContent_alt tr');
        
        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else { 
            $this->person_scrape_read(true, $this->thinktank_id);
            $i = 0; 
            foreach ($people as $person) {
                $test_row = $table_text = $this->dom_query($person['node'], "img");
                
                if ($test_row != 'no results') { 
                    
                    $this->person_loop_start($i);
                    $text       = $person['text'];
                    $title      = $this->dom_query($person['node'], "td p strong");
                    
                    if ($title == 'no results') { 
                        $title      = $this->dom_query($person['node'], "strong");
                    }
                
                    $title_exploded  = explode(',', $title['text']);
                    
                    print_r($title_exploded);
                    
                    $name       =  trim($title_exploded[0]); 
                    $role       =  trim($title_exploded[1]);
                    
                    $description =  str_replace($title['text'], '', $text);
                
                    $table_img  =   $this->dom_query($person['node'], "img");
                    $image_url  =   $this->base_url . $table_img['src'];
        
                    
                
                    $start_date = time();
                    $db_output = $this->db->save_job($name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                    $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, $description, $image_url, $start_date);
                    $i++;
                }    
            }
        }
        
        $this->staff_left_test($this->thinktank_id);
    }
}


$scraper = new cfsjPeople; 
$scraper->init();

?>