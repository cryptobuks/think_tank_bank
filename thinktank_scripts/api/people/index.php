<? 
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once($root .'/ini.php');;

$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

if(isset($_GET['thinktank'])) {
    @$thinktank = urldecode($_GET['thinktank']);
    $query = "SELECT DISTINCT(people_thinktank.person_id) FROM people_thinktank WHERE thinktank_id='$thinktank' ";
}

else if(isset($_GET['name'])) {
    @$name = urldecode($_GET['name']);
    $query = "SELECT * FROM people WHERE name_primary='$name' "; 
}

else { 
    echo "Must specify a name or thinktank ID";
}

$people_at_thinktank = $db->fetch($query);

$output = array();

$i=0;
foreach ($people_at_thinktank as $person_at_thinktank) { 
    $person_id      = $person_at_thinktank['person_id'];
    $person_record  = $db->fetch("SELECT * FROM people WHERE person_id = '$person_id'");
    $twitter_record  = $db->fetch("SELECT twitter_followers FROM people_twitter_rank WHERE person_id = '$person_id' ORDER BY date DESC LIMIT 1");     
   
    $jobs_records   = $db->fetch("SELECT * FROM people_thinktank INNER JOIN thinktanks ON people_thinktank.thinktank_id=thinktanks.thinktank_id  WHERE person_id = '$person_id'");          
    $output[$i]['person']   = $person_record[0];
    $output[$i]['twitter']  = @$twitter_record[0]['twitter_followers'];
    $output[$i]['jobs']     = $jobs_records;
    $i++;
}


$result['info']['success'] = true;
$result['info']['number_of_results'] = count($output); 
$result['data'] = $output;
output_json($result);


?>