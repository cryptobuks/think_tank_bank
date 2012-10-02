<?
//Environment settings 
include('ini.php');

//Instantiate the DB class, constructor will connect to the DB
include('dbClass.php');
$db = new dbClass($db_info['location'], $db_info['user_name'], $db_info['password'], $db_info['name']); 

//Class to log errors 
include('outputClass.php');
$output = new outputClass; 

//This will instantiate classes to scrape reports for each think tank  
include('reports/reports.php');

//This will instantiate classes to scrape people for each think tank  
include('people/people.php');

//Get a list of thinktanks 
$thinktanks = $db->get_thinktanks(); 


//scrape reports and people for each thinktank on the list
if (empty($thinktanks)) { 
    echo "You have no think tanks in the database"; 
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

//go down the pub    
 





?>