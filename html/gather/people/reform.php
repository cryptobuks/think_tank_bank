<? 
include_once("../../ini.php");

class reformPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Reform"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   =  $thinktank[0]['thinktank_id'];  
        echo "<h1>". $thinktank_id ."</h1>";

        $base_url= 'http://reform.co.uk'; 
        $people = $this->dom_query($base_url. '/content_category/870/reform/our_people/executive_team', '.thumbmember');
        $number_of_rows = count($people);
        
        for ($i= 0; $i< $number_of_rows; $i++) {
            
            //Name and Role
            $name       =  $this->dom_query($people[$i]['node'], 'h2'); 
            $name       =  $name['text'];
            
            //Role
            $role       = $this->dom_query($people[$i]['node'], '.h2_replace'); 
            $role       = $role['text'];
            
            //Description
            $description = $this->dom_query($people[$i]['node'], '.blog_summary_content p:eq(1)');
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


$scraper = new reformPeople; 
$scraper->init();

?>