<?
include('ini.php');
ob_start ();
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);


$sql = 'SELECT * FROM thinktanks'; 
$thinktanks = $db->fetch($sql);
$output['nodes'] = array();
$output['vertices'] = array();


foreach ($thinktanks as $thinktank) { 
    $output['nodes'][] = addslashes($thinktank['name']);
} 

$sql = 'SELECT *, COUNT(*) as count FROM people_thinktank GROUP BY person_id ORDER BY count DESC LIMIT 20';

$jobs = $db->fetch($sql);
foreach ($jobs as $job) { 
    $person_id = $job['person_id'];
    $sql =  "SELECT * FROM people INNER JOIN people_thinktank ON people.person_id=people_thinktank.person_id INNER JOIN thinktanks ON people_thinktank.thinktank_id=thinktanks.thinktank_id WHERE people_thinktank.person_id = '$person_id'"; 
    $results = $db->fetch($sql);

    $person_name = $results[0]['name_primary'];
    $output['nodes'][] = str_replace("'", " ", $person_name);

    foreach($results as $result) { 
        $thinktank_name = str_replace("'", " ", $result['name']);
        $person_name    = str_replace("'", " ", $result['name_primary']); 
        $output['vertices'][] = array(1 => $thinktank_name, 2 => $person_name);
    }
}

$json = json_encode($output);
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;


?>
