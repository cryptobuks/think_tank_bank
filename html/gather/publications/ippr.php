<?
include_once("../../ini.php"); 

class ipprPublications extends scraperPublicationClass { 
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("IPPR");    
      
        //these guys only have a json page 
        $json_url ="http://www.ippr.org/publications/index.php?option=com_ippr&task=ArticlePaging&limitstart=0&limit=1000&view=publications&megafilter=&adminfiltered=&siteid=";
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
                
                $link = $this->base_url. $article['content_link'];
                $link = $this->dom_query($link, '.download_link'); 
                $link = $this->base_url . $link['href'];
                
                $image_url = $this->base_url . '/' . $article['file'];

                $db_output = $this->db->save_publication($this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
                $this->publication_loop_end($db_output, $this->thinktank_id, $authors, $title, $link, '' , $pub_date, $image_url, "", "", $type);
     
        
            }   
        }
    }
}

$scraper = new ipprPublications; 
$scraper->init();$scraper->add_footer();


?>