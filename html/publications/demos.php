<?
include_once("../ini.php");

class demos_publications extends scraperBaseClass { 
    function init() { 
        for($i = 1; $i<2; $i++) { 
            $results = $this->zend_query("http://demos.co.uk/publications?page=$i", '.tab-content .publication');    
            if ($results != 'no results') {
                foreach($results as $result) {
                    $title =  $this->zend_query($result, '.title');
                    if ($title !="no results") {
                        echo "<h1>". $i . ". " . $title . "</h1>";
                        $link =  $this->zend_query($result, 'a');
                        echo "<p>" . $link[0]->nodeValue . "</p>"; 
                    }
                    else { 
 
                    }
                }
            }
        }
    }
}

$scraper = new demos_reports; 
$scraper->init();

?>