<?
include_once("../../ini.php"); 

class demos_publications extends scraperBaseClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Tax Payer's Alliance"; 
        $thinktank   =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id = 6; 
        $base_url= 'http://www.taxpayersalliance.com';     
       
        $reports = $this->dom_query($base_url."/reports", ".reports ");
        
        //Loop through each publication page  
        $report_count = count($reports); 
        for($i = 0; $i<=$report_count; $i++) { 
            if ($reports[$i] != 'no results') {
                
                //Title
                $title = $this->dom_query($reports[$i]['node'], 'h1'); 
                $title = $title['text']; 
                
                //Author 
                $authors = $this->dom_query($reports[$i]['node'], '.report-author'); 
                $authors = $authors['text'];                
                
                //Type
                $type = 'Report';
                
                //Pubdate 
                $day =  $this->dom_query($reports[$i]['node'], '.day'); 
                $month =  $this->dom_query($reports[$i]['node'], '.month');               
                $pub_date = strtotime($day['text'] . " " . $month['text']);
                $date_display = date("d.m.y", $pub_date);
                
                //Image URL 
                $image_url = $this->dom_query($reports[$i]['node'], '.report-cover img');
                $image_url = $image_url['src'];
                 
                //Link  
                $link = $this->dom_query($reports[$i]['node'], '.report-cover a');
                $link = $link['href'];
                
                echo "<h3>" . $title . "</h3><br/>";
                echo "<strong>authors:</strong> " . $authors . "<br/>";
                echo "<strong>type: </strong>" . $type . "<br/>";
                echo "<strong>link: </strong> " .$link . "<br/>";
                echo "<strong>image_url: </strong>" .$image_url . "<br/>";                
                echo "<strong>pub_date: </strong> " . $date_display . "<br/>";
                echo "<hr/>";

                $this->db->save_publication($thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
            }
            
            else { 
                $this->$status->log[] = array("Notice"=>"Tax Payer's Alliance publication crawler can't find any publications on a publication page");
            }
        }
    }
}

$scraper = new demos_publications; 
$scraper->init();$scraper->add_footer();

$status = outputClass::getInstance();
print_r($status->log);
?>