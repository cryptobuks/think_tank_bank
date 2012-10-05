<?



//Start Zend
require_once 'Zend/Loader/StandardAutoloader.php';
$loader = new Zend_Loader_StandardAutoloader();
$loader->register();
require_once 'Zend/Dom/Query.php'; 
 
//Instantiate the DB class, constructor will connect to the DB
include('dbClass.php');
$db = new dbClass($db_info['location'], $db_info['user_name'], $db_info['password'], $db_info['name']); 

//Class to log errors 
require_once('outputClass.php');
$output = new outputClass; 

//Report and People use classes that extent this class
require_once("scraperBaseClass.php");

?>