<?
include_once("../../ini.php"); 

class reformPublications extends scraperPublicationClass { 
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Reform");

        //get the number of  pages 
        $last_page  = $this->dom_query($this->base_url . "/content_category/673/research", ".pagination a"); 
        
        $node_count = count($last_page)-2 ;
        
        $last_page_url  = $last_page[$node_count]['href']; 
       
        $last_page = explode('/categories/673/view?page=', $last_page_url); 
        $last_page = $last_page[1]; 
        
        $last_page = explode('&url', $last_page); 
        $last_page = $last_page[0];  
        
        
        if ($last_page==0) {
            $this->scrape_error = array("error"=>"Respublica publication crawler can't find any publications on a publication page");           
        }
        
        else {        
            
            for($i=1; $i <= $last_page; $i++) { 
             
                
                $publications = array();
                $publications_raw = array();                
                $publications_raw = $this->dom_query($this->base_url . "/categories/673/view?page=$i&url%5B%5D=research", '.thumbmember');   
                 
                if (isset($publications_raw['text'])) {$publications[0] = $publications_raw;} 
                else {$publications =$publications_raw;}
                
                $k=0;
                foreach ($publications as $publication) {                     
                    $this->publication_loop_start($k, $i);
                    
                    $title = $this->dom_query($publication['node'], "h2");                
                    $title = $title['text'];
                
                    //Authors 
                    $authors = $this->dom_query($publication['node'], ".tags a");

                    if (!empty($authors[0]['text'])) { 
                        $author_array = array();
                        foreach ($authors as $author) { 
                            if (str_word_count($author['text']) > 1 && $author['text'] != 'criminal justice') { 
                                $author_array[] = $author['text'];
                            }    
                                
                        }    
                        $authors = implode(',', $author_array);
                    }
                    else { 
                        $authors ='';
                    }

                    //Type 
                    $type = 'report';
                
                
                    //Pubdate
                    $pub_date = $this->dom_query($publication['node'], ".author");
                    $pub_date = strtotime($pub_date['text']);
                    $date_display = date("d.m.y", $pub_date);
                
                    //Link 
                    $link = $this->dom_query($publication['node'], "h2 a");                
                    $link = $this->base_url . $link['href'];
                
                    $image_url = $this->dom_query($publication['node'], ".blog_summary_content img");
                    $image_url = $this->base_url . $image_url['src'];


                    $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
                    $this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
                    $k++;
                }
            }  
        } 
    }
}

$scraper = new reformPublications; 
$scraper->init();$scraper->add_footer();


?>