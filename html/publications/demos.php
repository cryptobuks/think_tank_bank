<?
include_once("../ini.php");

class demos_publications extends scraperBaseClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Demos"; 
        $thinktank   =  $this->db->get_thinktank($thinktank_name);
        $thinktank_id = $thinktank[0]['id'];   
        $base_url= 'http://demos.co.uk';     
       
        
        //find out how many pages of publications there are
        $pagination_list = $this->dom_query($base_url . '/publications', '.pagination a');
        $number_of_pages_elem_count = count($pagination_list)-2; 
        $number_of_pages = $pagination_list[$number_of_pages_elem_count]['text'];
    
        
        //Loop through each publication page  
        $publication_count = 1; 
        for($i = 1; $i<=1; $i++) { 
            
            //for each page, get a list of publications
            $results = $this->dom_query($base_url . "/publications?page=$i", '.tab-content .publication');    
            
            if ($results != 'no results') {
                foreach($results as $result) {
                    $link =  $this->dom_query($result['node'], '.title a');
                    if ($link !="no results") {
                        //get HTML once for processing 
                        $publication_page = $this->get_page_html($base_url . $link['href']);
                        
                        //title
                        $title = $publication_info = $this->dom_query($publication_page, ".page-header h2"); 
                        $title = $title['text'];
                        
                        //other info 
                        $publication_info = $this->dom_query($publication_page, ".publication-info-area dl dd"); 
                        $authors = $publication_info[0]['text']; 
                        $type = $publication_info[1]['text']; 
                        $pub_date = strtotime($publication_info[2]['text']);                         
                        $isbn = $publication_info[3]['text'];
                        $price = $publication_info[4]['text'];
                        
                        //link to PDF
                        $link =  $this->dom_query($publication_page, ".publication-info-area p a"); 
                        $link = $base_url . $link['href'];
                        
                        //image url 
                        $image_url = $this->dom_query($publication_page, ".publication-info-area img"); 
                        $image_url = $base_url . $image_url['src'];
                        
                        echo "<h3>" . $title . "</h3><br/>";
                        echo "authors: " . $authors . "<br/>";
                        echo "type: " . $type . "<br/>";
                        echo "pub_date: " . $pub_date . "<br/>";
                        echo "isbn: " . $isbn . "<br/>";
                        echo "price: " . $price . "<br/>"; 
                        echo "link: " .$link . "<br/>";
                        echo "image_url: " .$image_url . "<br/>";
                        
                        $this->db->save_publication($thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, $isbn, $price, $type);
                        
                        echo "<hr/>";
                        
                        $publication_count++;
                    }
                    else { 
                        $this->$status->log[] = array("Notice"=>"Demos publication crawler can't find any publications on a publication page");
                    }
                }
            }
            
            else { 
                $this->status->log[] = array("Notice"=>"Demos publication crawler can't find a page that should be there") ;  
            }
        }
    }
}

$scraper = new demos_publications; 
$scraper->init();

$status = outputClass::getInstance();
print_r($status->log);
?>