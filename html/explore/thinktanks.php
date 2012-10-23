<?
if (isset($url[1]) && !empty($url[1])) { 
    $query = $url[1];
    
    $thinktank = $db->search_thinktanks($query);
    ?>
    <h1>Name: <?= $thinktank[0]['name'] ?></h1> 
    <h2>URL: <?= $thinktank[0]['url'] ?></h1> 
    <h2>ID: <?= $thinktank[0]['thinktank_id'] ?></h1> 
    
    <h2><a href='../thinktanks/<?= $thinktank[0]['computer_name'] ?>/people'>People</a></h2>
    <h2><a href='../thinktanks/<?= $thinktank[0]['computer_name'] ?>/reports'>Reports</a></h2>    
    
    <?
}

else { 
    echo "Does not exist"; 
    
}

?>