<? 

include('../ini.php');
@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$id             = $_GET['id'];
$twitter_handle = addslashes($_GET['twitter_handle']); 
$twitter_id = addslashes($_GET['twitter_id']); 
$twitter_follower_number = addslashes($_GET['twitter_follower_number']); 
$query = "UPDATE people SET twitter_handle = '$twitter_handle', twitter_id= '$twitter_id' WHERE person_id='$id' "; 
$result = $db->query($query);
$date = time();
$query = "INSERT INTO  people_twitter_rank (`person_id`, `twitter_followers`, `date`) VALUES ($id, $twitter_follower_number, $date)";
echo $query;
$db->query($query);
print_r($result);
?>