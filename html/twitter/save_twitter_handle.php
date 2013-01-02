<? 

include('../ini.php');
@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$id             = $_GET['id'];
$twitter_handle = addslashes($_GET['twitter_handle']); 
$query = "UPDATE people SET twitter_handle = '$twitter_handle' WHERE person_id='$id' "; 
$result = $db->query($query);
print_r($result);
?>