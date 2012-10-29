<? 
include_once("../../ini.php"); 

class progressPeople extends scraperPeopleClass {
    
    function init() {
        
        //set up thinktank 
        $this->init_thinktank("Progress");        
        $people = $this->dom_query($this->base_url . '/about-progress/the-progress-team/', '.title');

        if ($people=='no results') {$this->person_scrape_read(false, $this->thinktank_id);}
        
        else {
            $md5 = md5($people['text']);
            $this->change_test($md5);
        }
    }    
}

$scraper = new progressPeople; 
$scraper->init();$scraper->add_footer();

?>