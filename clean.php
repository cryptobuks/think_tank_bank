<?

include_once('ini.php');

@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$old = strtotime('2 weeks ago');
$delete_query = "DELETE from tweets WHERE time < $old";
$db->query($delete_query);

$delete_query = "DELETE from people_interactions WHERE time < $old";
$db->query($delete_query);

?>