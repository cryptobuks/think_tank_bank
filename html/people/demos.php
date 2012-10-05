<? 
class demos_people extends scraperBaseClass { 
    function init() { 
        $results = $this->zend_query('http://demos.co.uk/people', '.person');    
        foreach($results as $result) {
            $h4 = $this->zend_query($result, 'h4');
            echo "<h1>" . $h4[0]->textContent . "</h1>";
            echo "<p>" . $h4[1]->textContent . "<p>";
            
            $p = $this->zend_query($result, 'p');
            echo "<p>" . @$p[1]->textContent . "<p>";
            
            $title = $this->zend_query($result, '.job-title');  
            
        }
    }
}
?>