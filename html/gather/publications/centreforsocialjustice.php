<?
include_once("../../ini.php");

class centreforsocialjusticePublications  extends scraperPublicationClass { 
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank('centreforsocialjustice');    

            
        //for each page, get a list of publications
        $results = $this->dom_query($this->base_url ."/default.asp?pageRef=266", 'table tr');    

        if ($results =="no results") {
            $message = array("Notice"=>"Centre for Social Justice publication crawler can't find any publications on a publication page");
            scrape_error($message);
        }
        
        else {        
            $i = 0;
            foreach($results as $result) { 
                
                if(strlen($result['text']) > 20) { 
                    $this->publication_loop_start($i);
                    
                    $link = $this->dom_query($result['node'],"strong a");   
                    if ($link == 'no results') { 
                        $link = $this->dom_query($result['node'],"td a:eq(0)");  
                    }
                    
                    $date = $this->dom_query($result['node'], "td");
                    $date = explode ('[', $result['text']); 
                    $date = explode (']', $date[1]); 
                    $date =  str_replace("/", ".", $date[0]); 
                    $pub_date = strtotime($date);
                    $date_display = date("d.m.y", $pub_date);  
                    
                    $img = $this->dom_query($result['node'], "img");
                    
                    $image_url = 'http://www.centreforsocialjustice.org.uk' . $img['src'];
                    

                    $title = $link['text'];
                    
                    $link  = 'http://www.centreforsocialjustice.org.uk' .$link['href'];
                    
                    $type = 'Report';
                    

                    $db_output = $this->db->save_publication($this->thinktank_id, '', $title, $link, '' , $pub_date, $image_url, '', '', $type);      
                    $this->publication_loop_end($db_output, $this->thinktank_id, '', $title, $link, '' , $pub_date, $image_url, '', '', $type);
                    
                    $i++;
                }
            }
        }
    }
}

$scraper = new centreforsocialjusticePublications; 
$scraper->init();

?>