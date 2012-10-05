<? 
include_once("../ini.php");

class demos_people extends scraperBaseClass { 

    function init() { 
        $results = $this->zend_query('http://demos.co.uk/people', '.person');    
        $i = 0; 
        foreach($results as $result) {
        
            $h4 = $this->zend_query($result, 'h4');
            $p = $this->zend_query($result, 'p');
            
            $name = $h4[0]->textContent; 
            $role = $h4[1]->textContent; 
            $description = @$p[1]->textContent; 
    
            echo "<h2>$i</h2>";
            echo "<p><strong>Name:</strong> $name </p>";
            echo "<p><strong>Role:</strong> $role </p>";
            echo "<p><strong>Description:</strong> $description</p>";
        
            $start_date = time();
            $this->db->save_job($name, "Demos", $role, $description, $start_date);
            $i++;
            echo "<hr/>";
        }
    }
}


$scraper = new demos_people; 
$scraper->init();

?>