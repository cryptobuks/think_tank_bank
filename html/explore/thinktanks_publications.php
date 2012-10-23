<?
if (isset($url[1]) && !empty($url[1])) { 
    $query = $url[1];
    
    $thinktank = $db->search_thinktanks($query);
    
     echo  "<h2>" .  $query . " Reports </h2>";
        

     $publications = $db->search_publications('', $thinktank[0]['thinktank_id']);
     
     

     foreach ($publications as $publication) {   ?>
         

         <div class='row'> 
             <div class='grid_4'> 
                 <a href='<?= $publication['url'] ?>'><?= $publication['title']?></a>
             </div>


             <div class='grid_2'> 
                 <img src='<?=$publication['image_url'] ?>' class='pub_image' />
             </div>
             
             <div class='grid_2'> 
                 Publication Date: <?= date("F j, Y", $publication['publication_date']); ?>    
             </div>


             <br class='clearfix' />
         </div>
     <? } 
    
}

else { 
    echo "This is not the thinktank you are looking for"; 
    
}

?>