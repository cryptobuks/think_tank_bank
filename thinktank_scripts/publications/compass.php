<?
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php')

class compassPublications extends scraperPublicationClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Compass"; 
        $this->init_thinktank($thinktank_name);
        
        //find out how many pages of publications there are
        $publications = $this->dom_query($this->base_url . '/publications/', '.news_item');

        if ($publications =="no results") {
            $this->$status->log[] = array("Notice"=>"IPPR publication crawler can't find any publications on a publication page");
        }
        else { 
            $i=0; 
            foreach($publications as $publication) {
                
                $this->publication_loop_start($i); 
                
                //Title
                $title = $this->dom_query($publication['node'], 'h2');
                $title = $title['text'];

                //Authors
                $authors = $this->dom_query($publication['node'], '.news_date');
                $authors = $authors['text'];                
                $authors = str_ireplace(' and ', ',', $authors);
                $authors = str_ireplace(' & ', ',', $authors);
                
                $type       = "pamphlets";
                $pub_date   = 0;
                $isbn       = 0;
                $price      = "£5";
                
                $link       = $this->dom_query($publication['node'], 'a');
                $link       = $link[0]['href']; 
                
                $image_url  = $this->dom_query($publication['node'], 'img');
                $image_url  = $image_url['src'];
            
                $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, $isbn, $price, $type);
                $this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, $isbn, $price, $type);
                $i++;
            }
        }
    }
}

$scraper = new compassPublications; 
$scraper->init();$scraper->add_footer();


?>