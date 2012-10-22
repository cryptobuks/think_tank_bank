<? 
include_once("../../ini.php");

class tpaPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Tax Payer's Alliance"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   =  $thinktank[0]['thinktank_id'];  

        $base_url           = 'http://www.taxpayersalliance.com';
        $results            = $this->dom_query("$base_url/people", '#post-842 .entry_content div');
       
        $number_of_rows     = count($results); 
        
        
        for ($i = 0; $i< $number_of_rows; $i++) { 
            //name
            $name = $this->dom_query($results[$i]['node'], "h3"); 
            $name = $name['text'];
            
            //Role
            $role = $this->dom_query($results[$i]['node'], "p"); 
            $role = $role['text'];
            
            //Description             
            $description =  "";
            
            //Image URL 
            $image_url = $this->dom_query($results[$i]['node'], "img"); 
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


$scraper = new tpaPeople; 
$scraper->init();

?>