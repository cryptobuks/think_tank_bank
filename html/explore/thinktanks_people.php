<?
if (isset($url[1]) && !empty($url[1])) { 
    $query = $url[1];
    
    $thinktank = $db->search_thinktanks($query);
    
    echo  "<h2>" .  $query . " People </h2>";
    
    $people = json_decode(file_get_contents("/../../../../api/people/?thinktank=".$thinktank[0]['thinktank_id']), true);
    
    
    foreach ($people['data'] as $person) { 
        foreach($person['jobs'] as $job) { ?>
            <div class='row_person'> 
                <div class='grid_3'>
                    <h3><?= $person['person']['name_primary'] ?> </h3>
                    <p><? if (is_numeric($person['twitter'])) { ?>(Twitter Followers: <?=$person['twitter']?>) <? } ?></p>
                    <img alt='No image' src='<?=$job['image_url'] ?>' class='pub_image' />
                    <p><?= substr($job['role'], 0, 500); ?></p>
                </div>

                <div class='grid_4'> 
                    <p><?= substr($job['description'], 0, 500); ?></p>
                </div>    
                
                <div class='grid_2'> 
                    <p>Start: <?= date("F j, Y", $job['begin_date']); ?></p> 
                    <? if ( $job['end_date'] != 0) { ?> 
                    <p>End Date: <?= date("F j, Y", $job['end_date']); ?></p>
                    <? } ?>     
                </div>
            
                
                <div class='grid_2'> 
                    <div class='twitter_handle'>
                        <input type='text' data-person_id='<?=$job['person_id'] ?>' name='twitter_handle' class='save_twitter_handle' value='<?= $person['person']['twitter_handle']  ?>'  />
                        <input type='button' value='save twitter handle' class='save_twitter_btn'  />
                    </div>
                </div>                   
            </div>
        <? }
    }
}

else { 
    echo "This is not the thinktank you are looking for"; 
    
}

?>