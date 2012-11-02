<?
if (isset($url[1]) && !empty($url[1])) { 
    $query = $url[1];
    
    $thinktank = $db->search_thinktanks($query);
    $tags = $db->get_tags();
    
    echo  "<h2>" .  $query . " Reports </h2>";    
    $publications = $db->search_publications('', $thinktank[0]['thinktank_id']); 

     foreach ($publications as $publication) {   
        
         $tags_text = json_decode($publication['tags_object']); 
         $tags_text = @implode(', ', $tags_text);
         ?>
         

         <div class='row_publication'> 
             <div class='grid_2'> 
                 <p><a href='<?= $publication['url'] ?>'><?= $publication['title']?></a></p>
                 <img src='<?=$publication['image_url'] ?>' class='pub_image' />
             </div>

             <div class='grid_3'> 
                 <p>
                 <? 
                 $authors = $db->search_authors($publication['publication_id']);
                 
                 foreach($authors as $author) { 
                     echo $author['name_primary']. ", ";
                 } 
                 ?>
                 </p>
             </div>
             
             <div class='grid_2'> 
                 <p>Published:<br/> <?= date("F j, Y", $publication['publication_date']); ?></p>
             </div>
            
            
            <div class='grid_3' >   
                <div class="assign_tags">
                    <?
                    foreach($tags as $tag) {
                        echo "<span> " . $tag['tag_text'] . "<input type='checkbox' value=" .  $tag['tag_text'] . " /></span>   ";
                    }
                    
                    ?>
                    <input type='button' value='save tags' class='save_tags_btn'  />
                </div>
            </div>
       
         </div>
     <? } 
    
}

else { 
    echo "This is not the thinktank you are looking for"; 
    
}

?>