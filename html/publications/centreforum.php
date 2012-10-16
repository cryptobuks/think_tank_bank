<?
include_once("../ini.php");

class centreforumPublications extends scraperBaseClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Centre Forum"; 
        $thinktank   =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id = $thinktank[0]['thinktank_id'];   
        $base_url= 'http://centreforum.org';     
      
        //get the number of  pages 
        $last_page  = $this->dom_query($base_url . "/index.php/toppublications", ".pagination-end a"); 
        $last_page = explode('start=', $last_page['href']);
        $last_page = $last_page[1];
        
        if ($last_page==0) {
            $this->$status->log[] = array("Notice"=>"Policy Exchange publication crawler can't find any pages with publications on ");
        }
        
        else {        
            
            for($i=1; $i <= $last_page; $i=$i+5) { 
                echo "<h1>Page Number " .  $i . '</h1>'; 
                
                $publications = array();
                $publications_raw = array();                
                $publications_raw = $this->dom_query($base_url . "/index.php/toppublications?start=$i", '.item, .leading-0');                 
                if (isset($publications_raw['text'])) {$publications[0] = $publications_raw;} 
                else {$publications =$publications_raw;}
                

                foreach ($publications as $publication) {    
                    
                    $title = $this->dom_query($publication['node'], "h2");                
                    $title = $title['text'];
                
                    //Authors 
                    $authors = "";

                    //Type 
                    $type = 'report';
                
                
                    //Pubdate
                    $pub_date = '';
                
                    //Link 
                    $link = $this->dom_query($publication['node'], "h2 a");                
                    $link = $base_url . $link['href'];
                
                    $image_url = $this->dom_query($publication['node'], "div strong img");
                    $image_url =  $base_url . $image_url['src'];
                
                    echo "<h3>" . $title . "</h3><br/>";
                    echo "<strong>authors:</strong>  Not Available <br/>";
                    echo "<strong>type:</strong> " . $type . "<br/>";
                    echo "<strong>pub_date:</strong>  Not Available  <br/>";
                    echo "<strong>link:</strong> " . $link . "<br/>";
                    echo "<strong>image_url:</strong> " .$image_url . "<br/>";

                    $this->db->save_publication($thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);

                    echo "<hr/>";
                        
                }
            }  
        } 
    }
}

$scraper = new centreforumPublications; 
$scraper->init();

$ippr = outputClass::getInstance();

?>