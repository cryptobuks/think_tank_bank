<?
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once($root .'/ini.php');

$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$person_id      = addslashes($_GET['person_id']);
$twitter_handle = addslashes(urldecode($_GET['twitter_handle']));

$query = "UPDATE people SET twitter_handle='$twitter_handle' WHERE person_id='$person_id'";
$result = $db->query($query);
echo $result;




?>