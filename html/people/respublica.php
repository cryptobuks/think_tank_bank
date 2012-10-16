<? 
include_once("../ini.php");

class policyexchangePeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Respublica"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   =  $thinktank[0]['thinktank_id'];  
        echo "<h1>". $thinktank_id ."</h1>";

        $base_url= 'http://respublica.org.uk'; 
        $people = $this->dom_query($base_url. '/authors', '.authors .author');
        $number_of_rows = count($people);
        
        for ($i= 0; $i< $number_of_rows; $i++) {
            
            //Name and Role
            $name       =  $this->dom_query($people[$i]['node'], '.authorlink'); 
            $name       =  explode(' - ', $name['text']);
            $role       =  $name[1];
            $name       =  $name[0];
            
            //Description
            $description = $this->dom_query($people[$i]['node'], '.miniBio');
            $description = $description['text'];
           
            //Image URL 
            $image_url  =  $this->dom_query($people[$i]['node'], 'img');
            $image_url  =  $base_url . $image_url['src'];
            
            
            echo "<p><strong>Name:</strong> $name </p>";
            echo "<p><strong>Role:</strong> $role </p>";
            echo "<p><strong>Description:</strong> $description</p>";
       
            echo "<p><strong>Img:</strong>" . $image_url . "</p>";
            $start_date = time();
            $this->db->save_job($name, $thinktank_id, $role, $description, $image_url, $start_date);
            echo "<hr/>";
            
        }
         
        $this->staff_left_test($thinktank_id);
    }
}


$scraper = new policyexchangePeople; 
$scraper->init();

?>