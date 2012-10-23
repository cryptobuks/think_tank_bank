<? 

include('../../ini.php');

$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);


@$thinktank = urldecode($_GET['thinktank']); 

$query = "SELECT DISTINCT(people_thinktank.person_id) FROM people_thinktank WHERE thinktank_id='$thinktank' "; 

$people_at_thinktank = $db->fetch($query);

$output = array();

$i=0;
foreach ($people_at_thinktank as $person_at_thinktank) { 
    $person_id      = $person_at_thinktank['person_id'];
    $person_record  = $db->fetch("SELECT * FROM people WHERE person_id = '$person_id'"); 
    $jobs_records   = $db->fetch("SELECT * FROM people_thinktank WHERE person_id = '$person_id'");          
    $output[$i]['person']   = $person_record[0];
    $output[$i]['jobs']     = $jobs_records;
    $i++;
}


$result['info']['success'] = true;
$result['info']['number_of_results'] = count($output); 
$result['data'] = $output;
output_json($result);


?>