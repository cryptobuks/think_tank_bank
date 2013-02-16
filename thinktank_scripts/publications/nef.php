<?
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php')

class nefPublications extends scraperPublicationClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "NEF"; 
        $this->init_thinktank($thinktank_name);
        

        
        
        for ($i=0;$i<50;$i++) { 
            
            $publications = $this->dom_query($this->base_url . '/publications?page='.$i, '.list-content li');
            if ($publications =="no results") {
                echo "no more pages";
            }
            else { 
                $k=0; 
                foreach($publications as $publication) {
                    
                    $this->publication_loop_start($k, $i); 
                
                    //Title
                    $title = $this->dom_query($publication['node'], 'h3');
                    $title = $title['text'];

                    //Authors
                    $authors = '';
                
                    $type       = "pamphlets";
                    $pub_date   = 0;
                    $isbn       = 0;
                    $price      = $this->dom_query($publication['node'], '.sellprice');
                    $price      = $price['text'];
                
                    $link       = $this->dom_query($publication['node'], '.imagecache');
                    $link       = $link[0]['href'];
                
                    $image_url  = $this->dom_query($publication['node'], 'img');
                    $image_url  = $image_url['src'];
            
                    $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, $isbn, $price, $type);
                    $this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, $isbn, $price, $type);
                    $k++;
                }
            }
        }    
    }
}

$scraper = new nefPublications; 
$scraper->init();$scraper->add_footer();


?>