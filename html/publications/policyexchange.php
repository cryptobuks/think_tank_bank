<?
include_once("../ini.php");

class policyexchangePublications extends scraperBaseClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Policy Exchange"; 
        $thinktank   =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id = $thinktank[0]['thinktank_id'];   
        $base_url= 'http://policyexchange.org.uk';     
      
      
        //get the number of  pages 
        $last_page = $this->dom_query($base_url . "/publications", ".pagination .end"); 
        
        $last_page = explode('category/publications/', $last_page['href']); 
        $last_page = $last_page[1]; 
        echo $last_page;
        if ($last_page==0) {
            //$this->$status->log[] = array("Notice"=>"Policy Exchange publication crawler can't find any pages with publications on ");
        }
        
        else {        
            
            for($i=1; $i <= $last_page; $i++) { 
                echo "<h1>Page Number " .  $i . '</h1>'; 
                $publications = $this->dom_query($base_url . "/publications/category/category/publications/$i", '.row'); 
              
                foreach ($publications as $publication) {                     
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
                    $link = $base_url . $link['href'];
                    
                    $image_url = $this->dom_query($publication['node'], "img");
                    $image_url = $image_url['src'];
                    
                    echo "<h3>" . $title . "</h3><br/>";
                    echo "<strong>authors:</strong> " . $authors . "<br/>";
                    echo "<strong>type:</strong> " . $type . "<br/>";
                    echo "<strong>pub_date:</strong>  $date_display  <br/>";
                    echo "<strong>link:</strong> " . $link . "<br/>";
                    echo "<strong>image_url:</strong> " .$image_url . "<br/>";

                    $this->db->save_publication($thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);

                    echo "<hr/>";
                }
            }  
        } 
    }
}

$scraper = new policyexchangePublications; 
$scraper->init();

$ippr = outputClass::getInstance();

?>