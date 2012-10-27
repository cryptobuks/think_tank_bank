<?
include('../../ini.php');

$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$pub_id = addslashes($_GET['pub_id']);
$tags = addslashes(urldecode($_GET['tags']));

$query = "UPDATE publications SET tags_object='$tags' WHERE publication_id='$pub_id' ";
$result = $db->query($query); 
echo $result;




?>