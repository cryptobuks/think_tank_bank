<? 

include('../../ini.php');
$status = outputClass::getInstance();
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME, $status);

@$name = urldecode($_GET['name']);
@$thinktank = urldecode($_GET['thinktank']); 

$people = $db->search_people($name, $thinktank);

$result['info']['success'] = true;
$result['info']['number_of_results'] = count($people); 
$result['data'] = $people;
output_json($result);


?>