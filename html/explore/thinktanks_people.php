<?
if (isset($url[1]) && !empty($url[1])) { 
    $query = $url[1];
    
    $thinktank = $db->search_thinktanks($query);

    
    echo  "<h2>" .  $query . " People </h2>";
    
    $people = $db->search_jobs('', $thinktank[0]['thinktank_id'], '', true);
    
    
    foreach ($people as $person) { ?>

        <div class='row'> 
            <div class='column'> 
                <a href='/explorer/people/<?= $person['name_primary'] ?>'><?= $person['name_primary']?></a>
            </div>
            
            <div class='column'> 
                <p><?= $person['role'] ?> </p>
            </div>
               
            <div class='column'> 
                <img src='<?=$person['image_url'] ?>' class='pub_image' />
            </div>
            
            <div>
                <?= substr($person['description'], 0, 150); ?>    
            </div>    
                                 
            <br class='clearfix' />
        </div>
    <? }
    
    
    
}

else { 
    echo "This is not the thinktank you are looking for"; 
    
}

?>