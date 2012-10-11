<? 
include_once("../ini.php");

class ipprPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Ippr"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   =  $thinktank[0]['thinktank_id'];  
        echo "<h1>Thinktank: ". $thinktank_id ."</h1>";

        $base_url= 'http://ippr.org.uk'; 
        
        $people = $this->dom_query("http://www.ippr.org/our-people", '.summary h4 a');
        
        $i=0; 
        echo "<h2>Getting Staff</h2>";
        foreach($people as $person) {

            echo "<h2>$i</h2>";
            $page = $this->get_page_html($base_url . $person['href'], 'a');
            $name = $this->dom_query($page, '.attributes h1');
            $name = $name['text'];
       
            $role = $this->dom_query($page, '.attributes h2');
            $role = $role['text'];   
            
            $description = $this->dom_query($page, '#staff_profile_page p:eq(1)');
            $description = $description['text'];
            
            $image_url = $this->dom_query($page, '.profile_image');
            $image_url = "http://www.ippr.org" . $image_url['src'];
            
            
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


$scraper = new ipprPeople; 
$scraper->init();

?>