<?

require_once('ini.php');

//Get a list of thinktanks 
$thinktanks = $db->get_thinktanks(); 

//scrape reports and people for each thinktank on the list
if (empty($thinktanks)) { 
    $output->errors[] = "You have no think tanks in the database"; 
}

else { 
    foreach($thinktanks as $thinktank) { 
        
        //make an object to scrape the people
        $class_name = $thinktank['name'] . "_people"; 
        if (class_exists($class_name)) { 
            $scraper = new $class_name;
            $scraper->init();  
        }
        
        else { 
            $output->errors[] = "There is no scraper for " . $class_name;  
        }
        
        //make an object to scrape the reports
        $class_name = $thinktank['name'] . "_reports";
        if (class_exists($class_name)) { 
            $scraper = new $class_name;
            $scraper->init();  
        }
        
        else { 
            $output->errors[] = "There is no scraper for " . $class_name;  
        }        
        
    }
}

$output->display_output();

 





?>