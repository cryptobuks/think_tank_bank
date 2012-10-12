<?
include_once("../ini.php");

class youngfoundationPublications extends scraperBaseClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Young Foundation"; 
        $thinktank   =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id = $thinktank[0]['thinktank_id'];   
        $base_url= 'http://youngfoundation.org';     
      
        //these guys only have a json page 
        $url ="http://www.youngfoundation.org/publications-date";
        $pages = $this->dom_query($url, ".pubrow h3 a");
        
        if ($pages =="no results") {
            $this->$status->log[] = array("Notice"=>"Young Foundation publication crawler can't find any publications on a publication page");
        }
        
        else {        

            foreach($pages as $page) { 
               
                $publication = $this->get_page_html($base_url.$page['href']); 
               
                //title 
                $title = $this->dom_query($publication, '.title'); 
                $title = $title['text'];
                
                //authors 
                $authors = $this->dom_query($publication, '.field-field-written-by a');
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
                $links = $this->dom_query($publication, '.node a');
                $found_link = '';
                foreach($links as $link) {
                    if (stripos($link['text'], 'pdf') || stripos($link['text'], 'report')) { 
                        $found_link = $link['href'];
                        break;
                    }
                }
                $link = $base_url .$found_link;
                
                //image url
                $image_url = $this->dom_query($publication, '.node img:eq(0)');
                $image_url =  $base_url .$image_url['src'];
                
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
                
                echo "<h3>" . $title . "</h3><br/>";
                echo "<strong>authors:</strong> " . $authors . "<br/>";
                echo "<strong>type:</strong> " . $type . "<br/>";
                echo "<strong>pub_date:</strong>  $date_display  <br/>";
                echo "<strong>link:</strong> " . $link . "<br/>";
                echo "<strong>image_url:</strong> " .$image_url . "<br/>";

                $this->db->save_publication($thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
            
                echo "<hr/>";
          
                $i++; 
            }   
        }
    }
}

$scraper = new youngfoundationPublications; 
$scraper->init();

$ippr = outputClass::getInstance();

?>