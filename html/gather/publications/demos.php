<?
include_once("../../ini.php"); 

class demosPublications extends scraperPublicationClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Demos"; 
        $this->init_thinktank($thinktank_name);
        
        //find out how many pages of publications there are
        $pagination_list = $this->dom_query($this->base_url . '/publications', '.pagination a');
        
        $number_of_pages_elem_count = count($pagination_list)-2; 
        $number_of_pages = $pagination_list[$number_of_pages_elem_count]['text'];
    
        //Loop through each publication page  
        $publication_count = 1; 
        for($i = 1; $i<=$publication_count; $i++) { 
            
            //for each page, get a list of publications
            $results = $this->dom_query($this->base_url . "/publications?page=$i", '.tab-content .publication');    
            
            if ($results != 'no results') {
                foreach($results as $result) {
                    $link = $this->dom_query($result['node'], '.title a');
                    if ($link !="no results") {
                        
                        $this->publication_loop_start($publication_count);
                        
                        //get HTML once for processing 
                        $publication_page = $this->dom_query($this->base_url . $link['href'], '.body-content');
                        
                        //title
                        $title = $this->dom_query($publication_page['node'], ".page-header h2"); 
                        $title = $title['text'];
                        
                        //other info 
                        $publication_info = $this->dom_query($publication_page['node'], ".publication-info-area dl dd"); 
                        $authors = $publication_info[0]['text']; 
                        $type = $publication_info[1]['text']; 
                        $pub_date = strtotime($publication_info[2]['text']);                         
                        $isbn = $publication_info[3]['text'];
                        $price = $publication_info[4]['text'];
                        
                        //link to PDF
                        $link =  $this->dom_query($publication_page['node'], ".publication-info-area p a"); 
                        $link = $this->base_url . $link['href'];
                        
                        //image url 
                        $image_url = $this->dom_query($publication_page['node'], ".publication-info-area img"); 
                        $image_url = $this->base_url . $image_url['src'];

                        
                        $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, $isbn, $price, $type);
                        $this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, $isbn, $price, $type);
                        
                        
                        $publication_count++;
                    }
                    else { 
                        $this->scrape_error = array("Notice"=>"Demos publication crawler can't find any publications on a publication page");
                    }
                }
            }
            
            else { 
                $this->scrape_error = array("Notice"=>"Demos publication crawler can't find a page that should be there") ;  
            }
        }
    }
}

$scraper = new demosPublications; 
$scraper->init();


?>