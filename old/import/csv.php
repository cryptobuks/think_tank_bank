<? 


include('../header.php');

echo "<h1>Process CSV</h1>";

$records = $db->fetch("SELECT * FROM excel group by A ORDER BY id ASC");

$i = 0;
$k = 0; 
foreach($records as $record) {
    $text = trim(preg_replace('/[^\\/\-a-z\s]/i', '', $record['A']));
    echo $record['id'] . "--";
    echo $text;
    echo "--". $record["B"];
    $people_query = "SELECT * FROM people WHERE name_primary like '$text' ";
    $people_results = $db->fetch($people_query); 
    if (empty($people_results)) { 
        echo "  NEEDS SAVING"; 
        $ttname = addslashes(trim($record['B']));
        $thinktank_query = "SELECT * FROM thinktanks WHERE name like '$ttname' ";
        $thinktank_results = $db->fetch($thinktank_query); 
        if (empty($thinktank_results)) { 
            echo " <span style='color:red'> need to add thinktank</span> ". $ttname;
            $k++;
        }
        else {
            $thinktank_id = $thinktank_results[0]['thinktank_id'];
            $db->save_job($text, $thinktank_id, '', '', "", "");
            echo "saving";
        }
        $i++;
    }
    else { 
        echo " ALREADY IN"; 
    }
    echo "<br/>";
}

echo "<h1>". $i . "</h1>";
echo "<h1>". $k . "</h1>";

?>



<? include('../footer.php'); ?>