<?
if (isset($url[1]) && !empty($url[1])) { 
    $query = $url[1];
    
    $thinktank = $db->search_thinktanks($query);

    
    echo  "<h2>" .  $query . " People </h2>";
    
    $people = json_decode(file_get_contents("http://localhost:88/api/people/?thinktank=".$thinktank[0]['thinktank_id']), true);
    
    
    foreach ($people['data'] as $person) { 
    
        ?>
        
        <div class='row'> 
            <div class='column'> 
                <a href='/explorer/people/<?= $person['person']['name_primary'] ?>'><?= $person['person']['name_primary']?></a>
            </div>
        
        </div>
        <? foreach($person['jobs'] as $job) {  ?>
            <div class='row'> 
                <div class='grid_2'> 
                    <img src='<?=$job['image_url'] ?>' class='pub_image' />
                </div>

                <div class='grid_2'> 
                    <?= substr($job['role'], 0, 500); ?>    
                </div>
            
                <div class='grid_4'> 
                    <?= substr($job['description'], 0, 500); ?>    
                </div>    
                
                <div class='grid_2'> 
                    Start Date: <?= date("F j, Y", $job['begin_date']); ?>    
                </div>
            
                <div class='grid_2'> 
                    <? if ( $job['end_date'] != 0) { ?> 
                    End Date: <?= date("F j, Y", $job['end_date']); ?>
                    <? } ?>    
                </div>    
            
                                 
                <br class='clearfix' />
            </div>
            <? }
        }
    
    
}

else { 
    echo "This is not the thinktank you are looking for"; 
    
}

?>