<?
include_once("../../ini.php"); 

class youngfoundationPublications extends scraperPublicationClass { 
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank('Young Foundation');
       
        $url ="http://www.youngfoundation.org/publications-date";
        $pages = $this->dom_query($url, ".pubrow h3 a");
        
        if ($pages =="no results") {
            $this->$status->log[] = array("Notice"=>"Young Foundation publication crawler can't find any publications on a publication page");
        }
        
        else {        

            foreach($pages as $page) { 
               
                $publication = $this->dom_query($this->base_url.$page['href'], "*"); 
                
                
                
                //title 
                $title = $this->dom_query($publication[0]['node'], '.title'); 
                $title = $title['text'];
                
                //authors 
                $authors = $this->dom_query($publication[0]['node'], '.field-field-written-by a');
                if(@!$authors['text']) { //detect a single result from dom_query function
                    $clean_authors = array();
                    foreach ($authors as $author) { 
                        $clean_authors[] = trim($author['text']);
                    }
                    $authors = implode(',', $clean_authors);
                }
                else if ($authors =='no results') { 
                    $authors = '';
                }
                
                else { 
                    $authors =$authors['text'];
                }
                
                //type
                $type = "Report";
                
                //pdf link 
                $links = $this->dom_query($publication[0]['node'], '.node a');
                $found_link = '';
                
                if (isset($links['href'])) { 
                    $found_link = $link['href'];
                }
                
                else { 
                    foreach($links as $link) {
                        if (stripos($link['text'], 'pdf') || stripos($link['text'], 'report')) { 
                            $found_link = $link['href'];
                            break;
                        }
                    }
                }    
                $link = $this->base_url .$found_link;
                
                //image url
                $image_url = $this->dom_query($publication[0]['node'], '.node img:eq(0)');
                $image_url = $this->base_url .$image_url['src'];
                
                //pubdate
                if (strpos($title, '(')) { 
                    $pub_date = explode('(', $title); 
                    $pub_date = explode(')', $pub_date[1]); 
                    $pub_date = strtotime($pub_date[0]); 
                    $date_display = date("d.m.y", $pub_date);  
                }
                else { 
                    $pub_date = 0;
                    $date_display = "unknown";  
                }


                $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
                $this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);

                $i++; 
            }   
        }
    }
}

$scraper = new youngfoundationPublications; 
$scraper->init();$scraper->add_footer();

$ippr = outputClass::getInstance();

?>