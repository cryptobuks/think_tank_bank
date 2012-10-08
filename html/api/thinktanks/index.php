<? 

include('../../ini.php');
$status = outputClass::getInstance();
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME, $status);

$thinktanks = $db->get_thinktanks();
$result['info']['success'] = true;
$result['info']['number_of_results'] = count($thinktanks); 
$result['data'] = $thinktanks;
output_json($result);


?>