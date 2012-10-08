<? 
include_once("../ini.php");

class demosPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Demos"; 
        $thinktank   =  $this->db->get_thinktank($thinktank_name);
        $thinktank_id = $thinktank[0]['id'];   
        $base_url= 'http://demos.co.uk'; 
        
        //setup output 
        $output = outputClass::getInstance();
        $output->errors[]= 'hi';
        
        //$results = $this->dom_query('../test/demos_people_less.html', '.person');
        $results = $this->dom_query('../test/demos_people_less.html', '.person');
        print_r($results);
        $i=0; 
        foreach($results as $result) {
            $h4 = $this->dom_query($result['node'], 'h4');
            $p = $this->dom_query($result['node'], 'p');
            if ($h4=='no results' || $p=='no results') { 
                //$this->$status->log[] = array("Notice"=>"Demos person scraper could not understand part of the page");
            }
            else { 
                $name = trim($h4[0]['text']);
                $role = trim($h4[1]['text']); 
                $description = @$p[1]['text']; 
    
                echo "<h2>$i</h2>";
                echo "<p><strong>Name:</strong> $name </p>";
                echo "<p><strong>Role:</strong> $role </p>";
                echo "<p><strong>Description:</strong> $description</p>";
        
                $start_date = time();
                $this->db->save_job($name, $thinktank_name, $role, $description, $start_date);
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