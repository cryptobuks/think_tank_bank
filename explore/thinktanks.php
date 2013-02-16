<?
if (isset($url[1]) && !empty($url[1])) { 
    $query = $url[1];
    $thinktank = $db->search_thinktanks($query);
    ?>
    <div class='grid_4'>
        <h2>Details</h2>
        <h4>Name: <?= $thinktank[0]['name'] ?></h3> 
        <h4>URL: <?= $thinktank[0]['url'] ?></h3> 
        <h4>ID: <?= $thinktank[0]['thinktank_id'] ?></h3> 
    </div>
    
    <div class='grid_4'>
        <h2>Explore</h2>
        <h4><a href='../thinktanks/<?= $thinktank[0]['computer_name'] ?>/people'>People</a></h4>
        <h4><a href='../thinktanks/<?= $thinktank[0]['computer_name'] ?>/publications'>Publications</a></h4>    
    </div>
    <?
}

else { 
    echo "Does not exist"; 
    
}

?>