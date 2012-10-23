<?
class scraperPublicationClass extends scraperBaseClass { 
    
    function init_thinktank($thinktank_name) { 
        $thinktank              =   $this->db->search_thinktanks($thinktank_name);
        $this->thinktank_id     =   $thinktank[0]['thinktank_id'];  
        $this->base_url         =  $thinktank[0]['url'];        
        if ($_GET['debug'] == 'less') { 
            $this->base_url  = 'test/' . $thinktank[0]['computer_name'] .  "_less.html";   
        }
        if ($_GET['debug'] == 'more') { 
            $this->base_url  = 'test/' . $thinktank[0]['computer_name'] .  "_more.html";   
        }        
        
    }
        
    function publication_scrape_read($success, $thinktank_id, $error='') { 
        if ($success) { echo "Publication scrape has read a person page for thinktank id $thinktank_id "; }
        else {echo "Publication scrape has failed on thinktank id $thinktank_id due to $error"; }
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
                echo "<p>".$output."</p>";
            }
        }    
        echo "<h3>" . $title . "</h3><br/>";
        echo "authors: " . $authors . "<br/>";
        echo "type: " . $type . "<br/>";
        echo "pub_date: " . $pub_date . "<br/>";
        echo "isbn: " . $isbn . "<br/>";
        echo "price: " . $price . "<br/>"; 
        echo "link: " .$link . "<br/>";
        echo "image_url: " .$image_url . "<br/>";
    }  
    
    function scrape_error($message) { 
        print_r("ERROR:".$message);
    } 
    
    
}
?>