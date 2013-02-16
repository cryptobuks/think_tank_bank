<?
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include($root .'/ini.php')

class policyexchangePublications extends scraperPublicationClass { 
    
    function init() {

        $this->init_thinktank("Policy Exchange");      
      
        //get the number of  pages 
        $last_page = $this->dom_query($this->base_url . "/publications", ".pagination .end"); 
        
        $last_page = explode('category/publications/', $last_page['href']); 
        $last_page = $last_page[1]; 
        echo $last_page;
        if ($last_page==0) {
            $this->$status->log[] = array("Notice"=>"Policy Exchange publication crawler can't find any pages with publications on ");
        }
        
        else {        
            
            for($i=1; $i<= $last_page; $i++) { 
                echo "<h1>Page Number " .  $i . '</h1>'; 
                $publications = $this->dom_query($this->base_url . "/publications/category/category/publications/$i", '.row'); 
              
                foreach ($publications as $publication) {                
                    
                    $this->publication_loop_start($i);
                         
                    //Title 
                    $title = $this->dom_query($publication['node'], ".pos-title");
                    $title = explode('|', $title['text']);   
                    $pub_date =  $title[0];
                    $title    =  $title[1];                    
                    
                    //Authors 
                    $authors = $this->dom_query($publication['node'], ".credits");
                    
                    $authors = trim($authors['text']); 
                    $authors = substr($authors, 3);
                    $authors = str_replace('Foreword by', '', $authors);
                    //$authors = str_ireplace('Foreword by ', '', $authors['text']);
                    
                    //Type 
                    $type = 'report';
                    
                    
                    $pub_date = strtotime(trim($pub_date));
                    $date_display = date("d.m.y", $pub_date);  
                    
                    //Link 
                    $link = $this->dom_query($publication['node'], ".pos-title a");
                    $link = $this->base_url . $link['href'];
                    
                    $image_url = $this->dom_query($publication['node'], "img");
                    $image_url = $image_url['src'];

                    $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '', $pub_date, $image_url, "", "", $type);
                    $this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);

                }
            }  
        } 
    }
}

$scraper = new policyexchangePublications; 
$scraper->init();$scraper->add_footer();

$ippr = outputClass::getInstance();

?>