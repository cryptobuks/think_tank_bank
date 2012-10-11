<?
include_once("../ini.php");

class centreforsocialjusticePublications extends scraperBaseClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "Demos"; 
        $thinktank   =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id = $thinktank[0]['thinktank_id'];   
        $base_url= 'http://www.centreforsocialjustice.org.uk/default.asp?pageRef=266';     
      

            
        //for each page, get a list of publications
        $results = $this->dom_query($base_url,'table tr');    

        if ($results =="no results") {
            $this->$status->log[] = array("Notice"=>"Centre for Social Justice publication crawler can't find any publications on a publication page");
        }
        
        else {        

            foreach($results as $result) { 
                
                if(strlen($result['text']) > 20) { 
     
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
                    
                    echo "<h3>" . $title . "</h3><br/>";
                    echo "<br/>link: " .$link . "<br/>";
                    echo "pub_date: " . $date_display . "<br/>";
                    echo "image_url: " .$image_url . "<br/>";
                    /*
                    echo "<h3>" . $title . "</h3><br/>";
                    echo "authors: " . $authors . "<br/>";
                    echo "type: " . $type . "<br/>";
                    echo "pub_date: " . $pub_date . "<br/>";
                    echo "isbn: " . $isbn . "<br/>";
                    echo "price: " . $price . "<br/>"; 
                    echo "link: " .$link . "<br/>";
                    echo "image_url: " .$image_url . "<br/>";
                    */
                    $this->db->save_publication($thinktank_id, '', $title, $link, '' , $pub_date, $image_url, '', '', '');
                
                    
                }
                echo "<hr/>";
          
            }
        }

    }
}

$scraper = new centreforsocialjusticePublications; 
$scraper->init();

$status = outputClass::getInstance();
print_r($status->log);
?>