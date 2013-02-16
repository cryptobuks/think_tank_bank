<?
include('ini.php');
ob_start ();
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);


$sql = 'SELECT * FROM graph ORDER BY weight DESC LIMIT 80';

$links = $db->fetch($sql);
$i = 0;
$node_array = array(); 
foreach ($links as $link) { 
    $node_array[] = $link['to']; 
    $node_array[] = $link['from'];
}
$node_array = array_unique($node_array); 

$node_lookup = array();
foreach($node_array as $node) { 
    $output['nodes'][] = (object)array('nodeName' => $node, 'id'=> $i,'color' => 'red', 'fontColor' => 'black', 'fontSize'=>'20');
    $node_lookup[] = $node; 
    $i ++; 
}

foreach($links as $link) { 
    $to_id = array_search($link['to'],$node_lookup); 
    $from_id = array_search($link['from'], $node_lookup); 
    $output['links'][] = (object)array('source' =>  $to_id , 'target' =>  $from_id, 'value' => ($link['weight']/200));
    
}


$json = json_encode($output);
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
echo isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;


?>
