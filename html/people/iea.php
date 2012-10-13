<? 
include_once("../ini.php");

class ieaPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "IEA"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   =  $thinktank[0]['thinktank_id'];  

        $base_url           = 'http://iea.org.uk';
        $results            = $this->dom_query("$base_url/about/people", '.views-row');
        $number_of_rows     = count($results); 
        
        for ($i = 0; $i< $number_of_rows; $i++) { 
            //name
            $name = $this->dom_query($results[$i]['node'], ".views-field-title"); 
            $name_array = explode('-', $name['text']);
            $name = $name_array[0];
            
            //Role
            $role = $name_array[1]; 
            
            //Description             
            $description = $this->dom_query($results[$i]['node'], ".views-field-field-body-value");
            $description =  $description['text'];
            
            //Image URL 
            $image_url = $this->dom_query($results[$i]['node'], ".views-field-field-primary-image-fid img");
            $image_url =$image_url['src']; 
            
            
            echo "<h2>$i</h2>";
            echo "<p><strong>Name:</strong> $name </p>";
            echo "<p><strong>Role:</strong> $role </p>";
            echo "<p><strong>Description:</strong> $description</p>";
            echo "<p><strong>Image url: </strong> $image_url </p>";
            $start_date = time();
            $this->db->save_job($name, $thinktank_id, $role, $description, $image_url, $start_date);
            
            //$this->staff_left_test($thinktank_id);
        }
    }
}


$scraper = new ieaPeople; 
$scraper->init();

?>