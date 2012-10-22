<?
include_once("../../ini.php");

class respublicaPublications extends scraperBaseClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Respublica"; 
        $thinktank   =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id = $thinktank[0]['thinktank_id'];   
        $base_url= 'http://respublica.org.uk';     
      
      
        //get the number of  pages 
        $last_page  = $this->dom_query($base_url . "/publications", ".paging a"); 
        
        $node_count = count($last_page)-2 ;
        
        $last_page_url  = $last_page[$node_count]['href']; 
        
        $last_page = explode('publications/page-', $last_page_url); 
        $last_page = $last_page[1]; 
        echo $last_page;
        
        if ($last_page==0) {
            $this->$status->log[] = array("Notice"=>"Policy Exchange publication crawler can't find any pages with publications on ");
        }
        
        else {        
            
            for($i=1; $i <= $last_page; $i++) { 
                echo "<h1>Page Number " .  $i . '</h1>'; 
                
                $publications = array();
                $publications_raw = array();                
                
                $publications_raw = $this->dom_query($base_url . "/publications/page-$i", '.article'); 
                
                
                if (isset($publications_raw['text'])) {$publications[0] = $publications_raw;echo "Recasting!";} 
                else {$publications =$publications_raw;}
                
                foreach ($publications as $publication) {                     
                    print_r($publication);
                    //Title 
                    $title = $this->dom_query($publication['node'], ".articletitle");                
                    $title = $title['text'];
                
                    //Authors 
                    $authors = $this->dom_query($publication['node'], ".tags .author a");

                    if (!empty($authors[0]['text'])) { 
                        $author_array = array();
                        foreach ($authors as $author) { 
                            $author_array[] = $author['text'];
                        }    
                        $authors = implode(',', $author_array);
                    }
                    else { 
                        $authors ='';
                    }

                    //Type 
                    $type = 'report';
                
                
                    $pub_date = 0;
                    $date_display = "None Given";
                
                    //Link 
                    $link = $this->dom_query($publication['node'], ".articletitle a");
                    $link = $base_url . $link['href'];
                
                    $image_url = $this->dom_query($publication['node'], ".eventImage img");
                    $image_url =  $base_url . $image_url['src'];
                
                    echo "<h3>" . $title . "</h3><br/>";
                    echo "<strong>authors:</strong> " . $authors . "<br/>";
                    echo "<strong>type:</strong> " . $type . "<br/>";
                    echo "<strong>pub_date:</strong>  $date_display  <br/>";
                    echo "<strong>link:</strong> " . $link . "<br/>";
                    echo "<strong>image_url:</strong> " .$image_url . "<br/>";

                    $this->db->save_publication($thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);

                    echo "<hr/>";
                        
                }
            }  
        } 
    }
}

$scraper = new respublicaPublications; 
$scraper->init();

$ippr = outputClass::getInstance();

?>