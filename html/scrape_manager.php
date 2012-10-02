<?
//Environment settings 
include('ini.php')

//this string will provide the output 
$global_output = array(); 

//instantiate the DB class, constructor will connect to the DB
include('dbClass.php')
$db = new dbClass; 



$thinktanks = $db->get_thinktanks(); 

$scrape = new scraper;



include('people/people.php'); 





?>