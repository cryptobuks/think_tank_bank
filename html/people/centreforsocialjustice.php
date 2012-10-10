<? 
include_once("../ini.php");

class centreforsocialjusticePeople extends scraperPeopleClass {
    
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
                $table_img      = $this->dom_query($url, "#mainContent_alt table img");
                $table_text     = $this->dom_query($url, "#mainContent_alt table tr td:eq(1)");
                print_r($table_text);
                $number_of_rows = count($table_text); 
                
                for ($i= 0; $i<=$number_of_rows; $i++) { 
                    echo "<h2>$i</h2>";
                    echo "<p><strong>Img:</strong>" .  $table_img[$i]['src'] . "</p>";
                     echo "<p><strong>Img:</strong>" . $table_text[$i]['text'] . "</p>";
                    echo "<p><strong>Role:</strong> $role </p>"; 
                }
                
            } 
            /*
            if ($h4=='no results' || $p=='no results') { 
                $this->status_log->log[] = array("Notice"=>"Centre For Social Justice person scraper could not understand part of a page");
            }
            else { 
                $name = trim($h4[0]['text']);
                $role = trim($h4[1]['text']); 
                $description = @$p[1]['text']; 
                $image_url = $base_url . "/" . @$image['src']; 
                
    
                echo "<h2>$i</h2>";
                echo "<p><strong>Name:</strong> $name </p>";
                echo "<p><strong>Role:</strong> $role </p>";
                echo "<p><strong>Description:</strong> $description</p>";
                echo "<p><strong>Image url: </strong> $image_url </p>";
                
                $start_date = time();
                //$this->db->save_job($name, $thinktank_id, $role, $description, $image_url, $start_date);
            }
            $i++;
            echo "<hr/>";
            
            */
        }
        
        //$this->staff_left_test($thinktank_id);
        
    }
}


$scraper = new centreforsocialjusticePeople; 
$scraper->init();

?>