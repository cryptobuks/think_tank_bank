<? 
include_once("../../ini.php"); 

class cfsjPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Centre For Social Justice");
        
        
        if ($_GET['debug'] == 'more' || $_GET['debug'] == 'less') { 
             $people = $this->dom_query($this->base_url, '#mainContent_alt tr');
        }

    
        
        else { 
            echo "this is what you think" . $this->base_url . '/about-us/team';
            $people = $this->dom_query($this->base_url , 'body');
            echo "<p>not debug</p>";
        }

        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else { 
            $this->person_scrape_read(true, $this->thinktank_id);
            $i = 0; 
            foreach ($people as $person) {
                $test_row = $table_text = $this->dom_query($person['node'], "img");
                
                if ($test_row != 'no results') { 
                    
                    $this->person_loop_start($i);
                    $text       = $person['text'];
                    $name      = $this->dom_query($person['node'], "h2");
                    $role      = $this->dom_query($person['node'], "h3");
                    $image  = $this->dom_query($person['node'], 'img');
                    $image_url = $this->base_url . "/" . @$image['src'];
                    
  

                    
                
                    $start_date = time();
                    $db_output = $this->db->save_job($name, $this->thinktank_id, $role, '', $image_url, $start_date);
                    $this->person_loop_end($db_output, $name, $this->thinktank_id, $role, '', $image_url, $start_date);
                    $i++;
                }    
            }
        }
        
        $this->staff_left_test($this->thinktank_id);
    }
}


$scraper = new cfsjPeople; 
$scraper->init();$scraper->add_footer();

?>