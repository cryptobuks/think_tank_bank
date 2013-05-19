<?
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php');

class instituteforgovernmentPublications extends scraperPublicationClass { 
    
    function init() {
        
         $this->init_thinktank("Institute For Government"); 
        
        //get the number of  pages 
        $last_page  = $this->dom_query($this->base_url . "/publications", ".pager .pager-last a"); 
        $last_page = explode('page=', $last_page['href']);
        $last_page = $last_page[1];
        
        if ($last_page==0) {
            $this->scrape_error = array("error"=>"IfG publication crawler can't find any publications on a publication page");
        }
        
        else {        
            
            for($i=0; $i <= $last_page; $i++) { 
  
                
                $publications = array();
                $publications_raw = array();                
                $publications_raw = $this->dom_query($this->base_url . "/publications?field_publication_authors_nid=All&sort_by=field_publication_date_value&sort_order=DESC&page=$i", '.view-publications .views-row');                 
                if (isset($publications_raw['text'])) {$publications[0] = $publications_raw;} 
                else {$publications =$publications_raw;}
                
                $k=0;
                foreach ($publications as $publication) {    
                    
                    $this->publication_loop_start($k, $i);
                    
                    $title = $this->dom_query($publication['node'], ".views-field-title");                
                    $title = $title['text'];
               
                    //Authors 
                    $authors  = '';
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
                    $link = $link['href'];
                
                    $image_url = $this->dom_query($publication['node'], ".views-field-field-publication-thumbnail img");     
                    $image_url = $image_url['src'];
                    


                    $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
                    $this->publication_loop_end($db_output, $this->thinktank_id,$authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);

                    $k++;
                }
            }  
        } 
    }
}

$scraper = new instituteforgovernmentPublications; 
$scraper->init();$scraper->add_footer();


?>