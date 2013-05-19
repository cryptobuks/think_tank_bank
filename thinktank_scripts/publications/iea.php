<?
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php');

class youngfoundationPublications extends scraperPublicationClass { 
    
    function init() {
        
        $this->init_thinktank("IEA");    
      
        //get the number of  pages 
        $last_research_page = $this->dom_query($this->base_url . "/publications/research?page=1", ".pager-last a"); 
        $last_research_page = explode('page=', $last_research_page['href']); 
        $last_research_page = $last_research_page[1]; 

        $last_ea_page = $this->dom_query($this->base_url . "/publications/economic-affairs?page=1", ".pager-last a"); 
        $last_ea_page = explode('page=', $last_ea_page['href']);
        $last_ea_page = $last_ea_page[1];
        
        $url_array  =  array(); 
        
        for($i=0; $i <= $last_research_page; $i++)  { 
            $url_array[] = $this->base_url . "/publications/research?page=$i"; 
        }
        
        for($i=0; $i <= $last_ea_page; $i++)  { 
            $url_array[] = $this->base_url . "/publications/economic-affairs?page=$i"; 
        }
        
        $number_of_pages = count($url_array); 
        if ($number_of_pages== 0 ) {
           $this->scrape_error = array("error"=>"IEA publication crawler can't find any publications on a publication page");
        }
        
        else {        
            
            for($i=0; $i <= $number_of_pages; $i++) { 
                echo "<h1>Page URL " .  $url_array[$i] . '</h1>'; 
                $publications = $this->dom_query($url_array[$i], '.views-row'); 
                
                $k=0;
                foreach ($publications as $publication) { 
                    
                    $this->publication_loop_start($k, $i);
                                                                
                    //Title 
                    $title = $this->dom_query($publication['node'], ".views-field-title");
                    $title = $title['text'];                     
                    
                    //Authors 
                    $authors = $this->dom_query($publication['node'], ".views-field-field-author-value");
                    $authors = str_ireplace('Edited by', '', $authors['text']); 
                    $authors = str_ireplace(' and ', ', ', $authors); 
                    
                    //Type 
                    $type = 'report';
                    
                    //Pubdate
                    $pub_date = $this->dom_query($publication['node'], ".date-display-single");
                    $pub_date = strtotime($pub_date['text']);
                    
                    if($pub_date > time()-60) {
                        $pub_date = 0;   
                    }
                    
                    $date_display = date("d.m.y", $pub_date);  
                    
                    //Link 
                    $link = $this->dom_query($publication['node'], ".grey-btn a");
                    $link = $this->base_url . $link['href'];
                    
                    $image_url = $this->dom_query($publication['node'], ".imagecache img");
                    $image_url = $image_url['src'];
                    
                    $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
                    $this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
                    
                    $k++;
                }
            }  
        } 
    }
}

$scraper = new youngfoundationPublications; 
$scraper->init();$scraper->add_footer();


?>