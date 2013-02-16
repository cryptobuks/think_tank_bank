<?
//set timezone
date_default_timezone_set ('Europe/London');


//phpQuery;
require_once ROOT  .'/classes/phpQuery.php';

//Instantiate the DB class, constructor will connect to the DB
require_once  ROOT  .'/classes/dbClass.php';


//Report and People use classes that extent this class
require_once ROOT  ."/classes/scraperBaseClass.php";
require_once ROOT  ."/classes/scraperPeopleClass.php";
require_once ROOT  ."/classes/scraperPublicationClass.php";


require_once  ROOT  .'/functions/json.php';
require_once  ROOT  .'/functions/url_clean.php';
?>