<?
if (isset($url[1]) && !empty($url[1])) { 
    $query = $url[1];
    
    $thinktank = $db->search_thinktanks($query);
    
     echo  "<h2>" .  $query . " Reports </h2>";
        

     $publications = $db->search_publications('', $thinktank[0]['thinktank_id']);
     
     

     foreach ($publications as $publication) {   
        
         $tags_text = json_decode($publication['tags_object']); 
         $tags_text = @implode(', ', $tags_text);
         ?>
         

         <div class='row'> 
             <div class='grid_2'> 
                 <a href='<?= $publication['url'] ?>'><?= $publication['title']?></a>
             </div>


             <div class='grid_2'> 
                 <img src='<?=$publication['image_url'] ?>' class='pub_image' />
             </div>
             
             <div class='grid_2'> 
                 Publication Date: <?= date("F j, Y", $publication['publication_date']); ?>    
             </div>
            
            <div class='grid_2'> 
                <? 
                $authors = $db->search_authors($publication['publication_id']);
                
                foreach($authors as $author) { 
                    echo $author['name_primary']. ", ";
                } 
                
                ?>
            </div>
            
            <div class='grid_3' >
                <p>Tags</p>
                <div>
                    <input type='text' data-pub_id='<?=$publication['publication_id'] ?>' class='save_tags_text' value='<?=$tags_text ?>'  />
                    <input type='button' value='save tags' class='save_tags_btn'  />
                </div>
            </div>
            
             <br class='clearfix' />
         </div>
     <? } 
    
}

else { 
    echo "This is not the thinktank you are looking for"; 
    
}

?>