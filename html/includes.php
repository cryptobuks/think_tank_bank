<?
//set timezone
date_default_timezone_set ('Europe/London');

//phpQuery;
require_once __DIR__ .'/phpQuery.php';

//Instantiate the DB class, constructor will connect to the DB
require_once  __DIR__ .'/dbClass.php';

//Class to log errors 
require_once('outputClass.php'); 

//Report and People use classes that extent this class
require_once __DIR__ ."/scraperBaseClass.php";
require_once __DIR__ ."/scraperPeopleClass.php";


require_once  __DIR__ .'/json.php';
?>