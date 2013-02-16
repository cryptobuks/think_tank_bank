<?
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php')

class centreforumPublications extends scraperPublicationClass { 
    
    function init() {
        
        $this->init_thinktank("centreforum");

        //get the number of  pages 
        $last_page  = $this->dom_query($this->base_url . "/index.php/toppublications", ".pagination-end a"); 
        $last_page = explode('start=', $last_page['href']);
        $last_page = $last_page[1];
        
        if ($last_page==0) {
            $this->scrape_error = array("error"=>"Centre Forum publication crawler can't find any publications on a publication page");
        }
        
        else {        
            
            for($i=0; $i <= $last_page; $i=$i+5) { 
                 
                
                $publications = array();
                $publications_raw = array();                
                $publications_raw = $this->dom_query($this->base_url . "/index.php/toppublications?start=$i", '.item, .leading-0');                 
                if (isset($publications_raw['text'])) {$publications[0] = $publications_raw;} 
                else {$publications =$publications_raw;}
                
                $k=0;
                foreach ($publications as $publication) {    
                     
                    $this->publication_loop_start($k, $i);
                    
                    $title = $this->dom_query($publication['node'], "h2");                
                    $title = $title['text'];
                
                    //Authors 
                    $meta = $this->dom_query($publication['node'], "strong");
                    
                    $authors = $meta[1]['text'];
                    $authors = str_ireplace('with', 'and', $authors);
                    $authors = str_replace('and', ',', $authors); 
                    
                    //Type 
                    $type = 'report';
                
                
                    //Pubdate
                    $pub_date = $meta[2]['text'];
                    $pub_date = strtotime($pub_date);
                
                    //Link 
                    $link = $this->dom_query($publication['node'], "h2 a");                
                    $link = $this->base_url . $link['href'];
                
                    $image_url = $this->dom_query($publication['node'], "div strong img");
                    $image_url =  $this->base_url . $image_url['src'];
      

                    $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
                    $this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);

                    $k=0;
                        
                }
            }  
        } 
    }
}

$scraper = new centreforumPublications; 
$scraper->init();$scraper->add_footer();


?>