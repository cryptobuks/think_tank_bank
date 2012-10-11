<? 
include_once("../ini.php");

class demosPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Demos"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   =  $thinktank[0]['thinktank_id'];  
        echo "<h1>". $thinktank_id ."</h1>";

        $base_url= 'http://demos.co.uk'; 
        
        $results = $this->dom_query($base_url . '/people', '.person');
        //$results = $this->dom_query('../test/demos_people_less.html', '.person');
       
        $i=0; 
        foreach($results as $result) {
            $h4     = $this->dom_query($result['node'], 'h4');
            $p      = $this->dom_query($result['node'], 'p');
            $image  = $this->dom_query($result['node'], '.person-image');
            
            
            if ($h4=='no results' || $p=='no results') { 
                $this->status_log->log[] = array("Notice"=>"Demos person scraper could not understand part of the page");
            }
            
            else { 
                $name = trim($h4[0]['text']);
                $role = trim($h4[1]['text']); 
                $description = @$p[1]['text']; 
                $image_url = $base_url . "/" . @$image['src']; 
                
    
                echo "<h2>$i</h2>";
                echo "<p><strong>Name:</strong> $name </p>";
                echo "<p><strong>Role:</strong> $role </p>";
                echo "<p><strong>Description:</strong> $description</p>";
                echo "<p><strong>Image url: </strong> $image_url </p>";
                
                $start_date = time();
                $this->db->save_job($name, $thinktank_id, $role, $description, $image_url, $start_date);
                
            }
            $i++;
            echo "<hr/>";
        }
        
        $this->staff_left_test($thinktank_id);
        
    }
}


$scraper = new demosPeople; 
$scraper->init();

?>