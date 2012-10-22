<? 

include('../../ini.php');

$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

@$name = urldecode($_GET['name']);
@$thinktank = urldecode($_GET['thinktank']); 

$people = $db->search_jobs($name, $thinktank);

$result['info']['success'] = true;
$result['info']['number_of_results'] = count($people); 
$result['data'] = $people;
output_json($result);


?>