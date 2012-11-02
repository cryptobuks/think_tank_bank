<?
include_once("../../ini.php"); 

class youngfoundationPublications extends scraperBaseClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "IEA"; 
        $thinktank      =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id   = $thinktank[0]['thinktank_id'];   
        $base_url= 'http://www.iea.org.uk';     
      
        //get the number of  pages 
        $last_research_page = $this->dom_query($base_url . "/publications/research?page=1", ".pager-last a"); 
        $last_research_page = explode('page=', $last_research_page['href']); 
        $last_research_page = $last_research_page[1]; 

        $last_ea_page = $this->dom_query($base_url . "/publications/economic-affairs?page=1", ".pager-last a"); 
        $last_ea_page = explode('page=', $last_ea_page['href']);
        $last_ea_page = $last_ea_page[1];
        
        $url_array  =  array(); 
        
        for($i=0; $i <= $last_research_page; $i++)  { 
            $url_array[] = $base_url . "/publications/research?page=$i"; 
        }
        
        for($i=0; $i <= $last_ea_page; $i++)  { 
            $url_array[] = $base_url . "/publications/economic-affairs?page=$i"; 
        }
        
        $number_of_pages = count($url_array); 
        if ($number_of_pages== 0 ) {
            $this->$status->log[] = array("Notice"=>"IEA publication crawler can't find any pages with publications on ");
        }
        
        else {        
            
            for($i=0; $i <= $number_of_pages; $i++) { 
                echo "<h1>Page URL " .  $url_array[$i] . '</h1>'; 
                $publications = $this->dom_query($url_array[$i], '.views-row'); 
                

                foreach ($publications as $publication) {                     
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
                    $link = $base_url . $link['href'];
                    
                    $image_url = $this->dom_query($publication['node'], ".imagecache img");
                    $image_url = $image_url['src'];
                    
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

$scraper = new youngfoundationPublications; 
$scraper->init();$scraper->add_footer();

$ippr = outputClass::getInstance();

?>