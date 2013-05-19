<?
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php');

class respublicaPublications extends scraperPublicationClass { 
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Respublica");   
      
        //get the number of  pages 
        $last_page  = $this->dom_query($this->base_url . "/publications", ".paging a");
        
        if ($last_page == "no results") {
     
           $this->scrape_error = array("error"=>"Respublica publication crawler can't find any publications on a publication page");           
        }
        
        $node_count = count($last_page)-2 ;
        
        $last_page_url  = $last_page[$node_count]['href']; 
        
        $last_page = explode('publications/page-', $last_page_url); 
        $last_page = $last_page[1];
        
        if ($last_page==0) {
           $this->scrape_error = array("error"=>"Respublica publication crawler can't find any publications on a publication page");           
        }
        
        else {        
            
            for($i=1; $i <= $last_page; $i++) { 
                echo "<h1>Page Number " .  $i . '</h1>'; 
                
                $publications = array();
                $publications_raw = array();                
                $publications_raw = $this->dom_query($this->base_url . "/publications/page-$i", '.article'); 
                
                //if there is only one item per page
                if (isset($publications_raw['text'])) {$publications[0] = $publications_raw;} 
                else {$publications =$publications_raw;}
                
                foreach ($publications as $publication) {                     
                    $this->publication_loop_start($i);
                    
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
                    $link = $this->base_url . $link['href'];
                
                    $image_url = $this->dom_query($publication['node'], ".eventImage img");
                    $image_url =  $this->base_url . $image_url['src'];

                    $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
                    $this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
       
                        
                }
            }  
        } 
    }
}

$scraper = new respublicaPublications; 
$scraper->init();$scraper->add_footer();


?>