<? 
include_once("../ini.php");

class policyexchangePeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Policy Exchange"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   =  $thinktank[0]['thinktank_id'];  
        echo "<h1>". $thinktank_id ."</h1>";

        $base_url= 'http://centreforsocialjustice.org.uk'; 
        
        //TODO: People are not unique across these pages - test what happens
        $url_array = array(
        "Research" => 'http://policyexchange.org.uk/people/research',
        "Directors" => "http://policyexchange.org.uk/people/directors",
        "Development" => 'http://policyexchange.org.uk/people/development',
        "operations-and-communications" => 'http://policyexchange.org.uk/people/operations-and-communications'        
        );
        
        $i=0; 
        foreach($url_array as $role=>$url) {
            
         
                echo "<h2>Getting Staff: $role</h2>";
    
                $people = $this->dom_query($url,'.row');
                $number_of_rows = count($people);
                
                for ($i= 0; $i<2; $i++) { 
                    
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
                    
                    
                    echo "<p><strong>Name:</strong> $name </p>";
                    echo "<p><strong>Role:</strong> $role </p>";
                    echo "<p><strong>Description:</strong> $description</p>";
               
                    echo "<p><strong>Img:</strong>" . $image_url . "</p>";
                    $start_date = time();
                    $this->db->save_job($name, $thinktank_id, $role, $description, $image_url, $start_date);
                    echo "<hr/>";
                    
                }
            
          
        }
        
        $this->staff_left_test($thinktank_id);
        
    }
}


$scraper = new policyexchangePeople; 
$scraper->init();

?>