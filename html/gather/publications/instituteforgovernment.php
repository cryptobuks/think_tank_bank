<?
include_once("../../ini.php"); 

class instituteforgovernmentPublications extends scraperBaseClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Institute For Government"; 
        $thinktank   =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id = $thinktank[0]['thinktank_id'];   
        $base_url= 'http://instituteforgovernment.org.uk';     
      
        //get the number of  pages 
        $last_page  = $this->dom_query($base_url . "/publications", ".pager .pager-last a"); 
        $last_page = explode('page=', $last_page['href']);
        $last_page = $last_page[1];
        
        if ($last_page==0) {
            $this->$status->log[] = array("Notice"=>"Policy Exchange publication crawler can't find any pages with publications on ");
        }
        
        else {        
            
            for($i=0; $i <= $last_page; $i++) { 
                echo "<h1>Page Number " .  $i . '</h1>'; 
                
                $publications = array();
                $publications_raw = array();                
                $publications_raw = $this->dom_query($base_url . "/publications?field_publication_authors_nid=All&sort_by=field_publication_date_value&sort_order=DESC&page=$i", '.view-publications .views-row');                 
                if (isset($publications_raw['text'])) {$publications[0] = $publications_raw;} 
                else {$publications =$publications_raw;}
    
                foreach ($publications as $publication) {    
                    
                    $title = $this->dom_query($publication['node'], ".views-field-title");                
                    $title = $title['text'];
               
                    //Authors 
                    $authors = $this->dom_query($publication['node'], ".views-field-field-publication-authors a");                
                    if (!isset($authors['text'])) {
                        $authors_array = array();
                        foreach($authors as $author) { 
                            $authors_array[] = $author['text'];
                        }
                        $authors = implode(',', $authors_array);
                    }    
                    else { 
                        $authors = $authors['text'];
                    }

                    //Type 
                    $type = 'report';
                
                
                    //Pubdate
                    $pub_date = $this->dom_query($publication['node'], ".views-field-php"); 
                    $pub_date = $pub_date['text'];
                    $pub_date = strtotime($pub_date); 
                    $date_display = date("d.m.y", $pub_date);
                
                    //Link 
                    $link = $this->dom_query($publication['node'], ".views-field-field-publication-file .file a");               
                    $link = $base_url . $link['href'];
                
                    $image_url = $this->dom_query($publication['node'], ".views-field-field-publication-thumbnail img");     
                    $image_url = $base_url . $image_url['src'];
                    
                    echo "<h3>" . $title . "</h3><br/>";
                    echo "<strong>authors:</strong>   $authors <br/>";
                    echo "<strong>type:</strong> " . $type . "<br/>";
                    echo "<strong>pub_date:</strong> ". $date_display ."<br/>";
                    echo "<strong>link:</strong> " . $link . "<br/>";
                    echo "<strong>image_url:</strong> " .$image_url . "<br/>";

                    $this->db->save_publication($thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);

                    echo "<hr/>";
                        
                }
            }  
        } 
    }
}

$scraper = new instituteforgovernmentPublications; 
$scraper->init();

$ippr = outputClass::getInstance();

?>