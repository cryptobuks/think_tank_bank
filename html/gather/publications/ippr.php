<?
include_once("../../ini.php");

class ipprPublications extends scraperBaseClass { 
    
    function init() {
        
        //set up thinktank 
        $thinktank_name = "IPPR"; 
        $thinktank   =  $this->db->search_thinktanks($thinktank_name);
        $thinktank_id = $thinktank[0]['thinktank_id'];   
        $base_url= 'http://www.ippr.org';     
      
        //these guys only have a json page 
        $json_url ="http://www.ippr.org/publications/index.php?option=com_ippr&task=ArticlePaging&limitstart=0&limit=100&view=publications&megafilter=&adminfiltered=&siteid=";
        $data = $this->get_page_html($json_url);
        
        
        if ($data =="no results") {
            $this->$status->log[] = array("Notice"=>"IPPR publication crawler can't find any publications on a publication page");
        }
        
        else {        
            $data = json_decode($data, true);
            foreach($data['articles'] as $article) { 
                $title = $article['title'];
                
                $authors = $article['AuthorTags'];
                $authors .= $article['ContributorTags'];
                $authors .= $article['EditorTags'];
                $authors = str_replace(",",' ', $authors); 
                $authors = str_replace(";",',', $authors); 
                $authors = substr_replace($authors ,"",-1);
                
                $type = 'Report';
                
                $pub_date = $article['published_date'];
                $pub_date = strtotime($pub_date); 
                $date_display = date("d.m.y", $pub_date);  
                
                $link = $base_url. $article['content_link'];
                $link = $this->dom_query($link, '.download_link'); 
                $link = $base_url . $link['href'];
                
                $image_url = $base_url . '/' . $article['file'];
                
                echo "<h3>" . $title . "</h3><br/>";
                echo "<strong>authors:</strong> " . $authors . "<br/>";
                echo "type: " . $type . "<br/>";
                echo "pub_date: " . $date_display . "<br/>";
                echo "link: " .$link . "<br/>";
                echo "image_url: " .$image_url . "<br/>";
                
                $this->db->save_publication($thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
            
                echo "<hr/>";
            }   
        }
    }
}

$scraper = new ipprPublications; 
$scraper->init();

$ippr = outputClass::getInstance();

?>