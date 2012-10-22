<? 
include_once("../../ini.php");

class cfsjPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Centre For Social Justice"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   =  $thinktank[0]['thinktank_id'];  
        echo "<h1>". $thinktank_id ."</h1>";

        $base_url= 'http://centreforsocialjustice.org.uk'; 
        
        $url_array = array(
        "Staff" => 'http://www.centreforsocialjustice.org.uk/default.asp?pageRef=49', 
        "Board Of Directors" => "http://www.centreforsocialjustice.org.uk/default.asp?pageRef=69",
        "Advisory Board" => 'http://www.centreforsocialjustice.org.uk/default.asp?pageRef=46'
        );
        
        $i=0; 
        foreach($url_array as $role=>$url) {
            
            if ($role == "Staff") { 
                echo "<h2>Getting Staff</h2>";
                $table_img            = $this->dom_query($url, "#mainContent_alt table img");
                $table_text           = $this->dom_query($url, "#mainContent_alt table tr td:nth-child(2)");
                $table_title_role     = $this->dom_query($url, "#mainContent_alt table tr td:nth-child(2) strong");
                $number_of_rows       = count($table_text); 
                
                
                
                for ($i= 0; $i<$number_of_rows; $i++) { 
                    $table_title_role_exploded  = explode(',', $table_title_role[$i]['text']);  
                    $name       =  trim($table_title_role_exploded[0]); 
                    $role       =  trim($table_title_role_exploded[1]);
                    $image_url  =  $base_url . $table_img[$i]['src'];
                    
                    $description =  explode($table_title_role[$i]['text'], $table_text[$i]['text']);
                    $description = $description[1]; 
                    echo "<p><strong>Name:</strong> $name </p>";
                    echo "<p><strong>Role:</strong> $role </p>";
                    echo "<p><strong>Description:</strong> $description</p>";
               
                    echo "<p><strong>Img:</strong>" . $image_url . "</p>";
                    $start_date = time();
                    $this->db->save_job($name, $thinktank_id, $role, $description, $image_url, $start_date);
                    echo "<hr/>";
                    
                }
            } 
            
            if ($role =='Board Of Directors' ) { 
                
                $ul = $this->dom_query($url, "#mainContent_alt ul li");
                
                foreach($ul as $person) { 
                    
                    $name       =  trim($person['text']);
                    $image_url  =  '';
                    $description = '';
                    $start_date = time();
                    echo "<p><strong>Name:</strong> $name </p>";
                    echo "<p><strong>Role:</strong> $role </p>";
                    $this->db->save_job($name, $thinktank_id, $role, $description, $image_url, $start_date);
                } 
                
            }

            if ($role == "Advisory Board") { 
                
                $ul = $this->dom_query($url, "#mainContent_alt strong");
                
                foreach($ul as $person) { 
                    
                    $name       =  trim($person['text']);
                    $image_url  =  '';
                    $description = '';
                    $start_date = time();
                    echo "<p><strong>Name:</strong> $name </p>";
                    echo "<p><strong>Role:</strong> $role </p>";
                    $this->db->save_job($name, $thinktank_id, $role, $description, $image_url, $start_date);
                } 
                
            }            
        }
        
        $this->staff_left_test($thinktank_id);
        
    }
}


$scraper = new cfjsPeople; 
$scraper->init();

?>