<?
include('ini.php');
ob_start ();
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);


$sql = 'SELECT * FROM thinktanks'; 
$thinktanks = $db->fetch($sql);
$output['nodes'] = array();
$output['links'] = array();
$shaddow_output = array();


$i=0;
foreach ($thinktanks as $thinktank) { 
    $thinktank_name = str_replace("'", " ", $thinktank['name']);
    $output['nodes'][] = (object)array('nodeName' => $thinktank_name, 'id'=> $i,'color' => 'red', 'fontColor' => 'black', 'fontSize'=>'20');
    $shaddow_output[$i] = $thinktank_name;
    $i++;
} 

$sql = 'SELECT *, COUNT(*) as count FROM people_thinktank GROUP BY person_id ORDER BY count DESC LIMIT 200';

$jobs = $db->fetch($sql);
foreach ($jobs as $job) { 
    $person_id = $job['person_id'];
    $sql =  "SELECT * FROM people INNER JOIN people_thinktank ON people.person_id=people_thinktank.person_id INNER JOIN thinktanks ON people_thinktank.thinktank_id=thinktanks.thinktank_id WHERE people_thinktank.person_id = '$person_id'"; 
    $results = $db->fetch($sql);
    
   

    $person_name = str_replace("'", " ", $results[0]['name_primary']);
    $output['nodes'][] = (object)array('nodeName' => $person_name, 'id'=> $i,  'color' => 'blue', 'fontColor' => 'gray', 'fontSize'=>'10');
    $shaddow_output[$i] = $person_name;   
    
    
    
    foreach($results as $result) {
        
        
        $thinktank_name     = str_replace("'", " ", $result['name']);
        $person_name        = str_replace("'", " ", $result['name_primary']);
        //echo "TT: " . $thinktank_name;
        //echo "\n Person: ".$person_name;
        $thinktank_index    = array_search($thinktank_name , $shaddow_output); 
        $person_index       = array_search($person_name, $shaddow_output);
        
        //print_r( $shaddow_output);

          
        if (is_numeric($thinktank_index) && is_numeric($person_index)) { 

            
            //echo ' --> ' . $thinktank_index;
            //echo ' --> ' . $person_index;
            //echo "\n\n";
            $output['links'][]   = (object)array('source' => $person_index, 'target' => $thinktank_index, 'value' => 1);
        }
    }
    
    $i++;
    
}

$json = json_encode($output);
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;


?>
