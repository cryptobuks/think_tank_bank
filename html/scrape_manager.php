<?
//Environment settings 
include('ini.php');

//this string will provide the output TODO: make class 
$global_output = array(); 

//Instantiate the DB class, constructor will connect to the DB
include('dbClass.php');
$db = new dbClass($db_info['location'], $db_info['user_name'], $db_info['password'], $db_info['name']); 

//This will instantiate classes to scrape reports for each think tank  
include('reports/reports.php');

//This will instantiate classes to scrape people for each think tank  
include('people/people.php');

//Get a list of thinktanks 
$thinktanks = $db->get_thinktanks(); 


//scrape reports and people for each thinktank on the list
if (empty($thinktanks)) { 
    echo "you have no think tanks in the database"; 
}

else { 
    foreach($thinktanks as $thinktank){ 
        if (class_exists($thinktank['name'])) { 
            echo "yep - scraper_exists"; 
        }
        else { 
            echo "no scraper for this"; 
        }
    }
}

//go down the pub    
 





?>