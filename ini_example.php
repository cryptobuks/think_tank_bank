<?

define("DB_LOCATION", 'localhost');
define("DB_USER_NAME", 'root');
define("DB_PASSWORD", 'root');
define("DB_NAME", 'think_tank_new');
define("LOCAL_URL",'http://localhost:88');

//twitter config 
define('CONSUMER_KEY', '');
define('CONSUMER_SECRET', '');
define('OAUTH_CALLBACK', 'http://domain.com/twitter/callback.php');

define('OAUTH_KEY', '');
define('OAUTH_SECRET', '');


//If a person is not listed as a staff member on a thinktank website
//then they may have left. This constant is the number of days the  
//scraper should wait before deciding they must have left
define("STAFF_LEFT_AFTER",30);


define("ROOT",realpath($_SERVER["DOCUMENT_ROOT"]));
include_once ROOT .'/functions/includes.php';

$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

?>