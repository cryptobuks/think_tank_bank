<? 
include_once("../ini.php");

class youngfoundationPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Ippr"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   =  $thinktank[0]['thinktank_id'];  
        echo "<h1>Thinktank: ". $thinktank_id ."</h1>";

        $base_url= 'http://www.youngfoundation.org'; 
        
        $people = $this->dom_query($base_url . "/about-us/people/staff", '.listing_box');
        
        $i=0; 
        echo "<h2>Getting Staff</h2>";
        foreach($people as $person) {
            echo "<h2>$i</h2>";

            $name = $this->dom_query($person['node'], '.listing_title a');
            $name = $name['text'];
       
            $role = "Staff";
  
            
            $description = $this->dom_query($person['node'], '.listing_content');
            $description = $description['text'];
            
            $image_url = $this->dom_query($person['node'], 'img');
            $image_url = $base_url . $image_url['src'];
            
            
            echo "<p><strong>Name:</strong> $name </p>";
            echo "<p><strong>Role:</strong> $role </p>";
            echo "<p><strong>Description:</strong> $description</p>";
            echo "<p><strong>Img:</strong>" . $image_url . "</p>";

            $start_date = time();
            $this->db->save_job($name, $thinktank_id, $role, $description, $image_url, $start_date);
            echo "<hr/>";
            $i++;
            
        } 
    }
}


$scraper = new youngfoundationPeople; 
$scraper->init();

?>