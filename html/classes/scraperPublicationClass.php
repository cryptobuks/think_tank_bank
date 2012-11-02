<?
class scraperPublicationClass extends scraperBaseClass { 
    
    function init_thinktank($thinktank_name) { 
        $thinktank              =   $this->db->search_thinktanks($thinktank_name);
        $this->thinktank_id     =   $thinktank[0]['thinktank_id'];  
        $this->base_url         =  $thinktank[0]['url'];        
        if (@$_GET['debug'] == 'less') { 
            $this->base_url  = 'test/' . $thinktank[0]['computer_name'] .  "_less.html";   
        }
        if (@$_GET['debug'] == 'more') { 
            $this->base_url  = 'test/' . $thinktank[0]['computer_name'] .  "_more.html";   
        }        
        
    }
        
    function publication_scrape_read($success, $thinktank_id, $error='') { 
        if ($success) { echo "Publication scrape has read a person page for thinktank id $thinktank_id "; }
        else {
            $string =  "Publication scrape has failed on thinktank id $thinktank_id due to $error"; 
            echo $string; 
            $this->db->log("error", $string);
        }
    }
    
    function publication_loop_start($iteration, $page='') { 
        if(!empty($page)) { 
            echo "<h2>Page: $page, Iteration: $iteration</h2>"; 
        }
        else { 
            echo "<h2>Iteration: $iteration</h2>"; 
        }
    }
 
    function publication_loop_end($db_output, $thinktank_id, $authors, $title, $link, $type , $pub_date, $image_url, $isbn, $price, $type) { 
        if(isset($db_output)) { 
            foreach ($db_output as $output) { 
                
                if (is_array($output['NEW PUB FLAG'])){ 
                    $this->db->log("notice", $output['NEW PUB FLAG']);
                    echo "<p>".$output['NEW PUB FLAG']."</p>";
                }
                else {
                    $this->db->log("log", $output);
                    echo "<p>".$output."</p>";
                }
            }
        }    
        
        $pub_date_human = date("F j, Y", $pub_date);
        $link_encoded   = url_clean($link);
        //$image_url      = url_clean($image_url);
        
        echo "<h3> $title </h3>\n";
        echo "<p>Authors: $authors </p>\n";
        echo "<p>Text: $type </p>\n";
        echo "<p>Pub Date: $pub_date_human </p>\n";
        echo "<p>link: <a href=''>$link</a> </p>\n";
        echo "<img src='$image_url' alt='No image' class='pub_image' /> \n";
    }  
    
    function scrape_error($message) { 
        print_r("ERROR:".$message);
        $this->db->log("error", $string);
    } 
    
    
}
?>