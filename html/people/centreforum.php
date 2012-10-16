<? 
include_once("../ini.php");

class centreforumPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Centre Forum"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   =  $thinktank[0]['thinktank_id'];  
        echo "<h1>". $thinktank_id ."</h1>";

        $base_url= 'http://centreforum.org'; 
        $people = $this->dom_query($base_url. '/index.php/staff', 'td');
        $rows_to_try = count($people); 
        
        for ($i= 0; $i< $rows_to_try; $i++) {
            
            if ( $this->dom_query($people[$i]['node'], 'h3') != 'no results') { 

                print_r($people[$i]['node']);
                //Name and Role
                $name       =  $this->dom_query($people[$i]['node'], 'h3');
                $name       =  $name['text'];
            
                //Role
                $role       = $this->dom_query($people[$i]['node'], 'h3 + p'); 
                $role       = $role['text'];
            
                //Description
                $description = '';
           
                //Image URL 
                $image_url  =  '';

            
            
                echo "<p><strong>Name:</strong> $name </p>";
                echo "<p><strong>Role:</strong> $role </p>";
                echo "<p><strong>Description:</strong> $description</p>";
       
                echo "<p><strong>Img:</strong>" . $image_url . "</p>";
                $start_date = time();
                $this->db->save_job($name, $thinktank_id, $role, $description, $image_url, $start_date);
                echo "<hr/>";
            }
        }
          
        
        //$this->staff_left_test($thinktank_id);
        
    }
}


$scraper = new centreforumPeople; 
$scraper->init();

?>