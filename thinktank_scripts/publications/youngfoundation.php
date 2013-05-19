<?
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php');

class youngfoundationPublications extends scraperPublicationClass { 
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank('Young Foundation');
       
       
        $pagination = $this->dom_query($this->base_url . "/publications", ".pagination-list a");
        
        if ($pagination =="no results") {
           $this->publication_scrape_read(false, $this->thinktank_id, "couldn't read pagination");
        }
        
        else {        
            
            $page_count = count($pagination)-1;

            //set up thinktank 
            for($i=1; $i<=$page_count; $i++) {

                $publications = $this->dom_query($this->base_url . "/publications/page/".$i, ".listing");

                if ($publications =="no results") {
                    $string = "couldn't read page $i";
                   $this->publication_scrape_read(false, $this->thinktank_id, "couldn't read page $i");
                   echo  $string;
                }
                
                else {
                
                    $k=0;
                    foreach($publications as $publication) { 
                        $this->publication_loop_start($k, $i);
                        //title 
                        $title = $this->dom_query($publication['node'], 'h3'); 
                        $title = $title['text'];
                
                        //authors 
                        $clean_author = array();
                        $authors = $this->dom_query($publication['node'], '.meta a');

                        if(isset($authors[1]['text'])) {
                            foreach($authors as $author) {
                                $clean_author[] = $author['text'];
                            }
                            $authors = implode(',', $clean_author);
                        }
                        else{ 
                            $authors = $authors['text'];
                        }    
                
                        //type
                        $type = "Report";
                
                        //pdf link 
                        $link = $this->dom_query($publication['node'], '.listing-thumb');
                        $link = $link['href'];
                
                        //image url
                        $image_url = $this->dom_query($publication['node'], 'img');
                        $image_url = $image_url['src'];
                
                        //pubdate
                        $pub_date   = $this->dom_query($publication['node'], '.meta:eq(0)');
                        $pub_date   = str_replace("Print date:", " ", $pub_date['text']);
                        $pub_date   = strtotime($pub_date);
                    
                        if ($pub_date >= time()-200) {
                            $pub_date = 0;
                        }
                    
                    
                        $date_display = date("d.m.y", $pub_date); 

                        $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
                        @$this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);

                        $k++;      
                    }               
                }  
            }     
        }
    }
}

$scraper = new youngfoundationPublications; 
$scraper->init();$scraper->add_footer();


?>