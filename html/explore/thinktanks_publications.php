<?
if (isset($url[1]) && !empty($url[1])) { 
    $query = $url[1];
    
    $thinktank = $db->search_thinktanks($query);
    $tags = $db->get_tags();
    
    echo  "<h2>" .  $query . " Reports </h2>";    
    $publications = $db->search_publications('', $thinktank[0]['thinktank_id']); 

     foreach ($publications as $publication) {   
        
         $tags_text = explode(',', $publication['tags_object']); 
      
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
            
            
            <div class='grid_5' >   
                <div class="assign_tags">
                    <?
                    foreach($tags as $tag) {

                        if (is_numeric(array_search($tag['tag_text'], $tags_text))){
                            $checked = "checked='checked'"; 
                        }
                        else { 
                            $checked = " "; 
                        }
                        echo "<span> " . $tag['tag_text'] . "<input type='checkbox' $checked  value=" .  $tag['tag_text'] . " data-pub-id=". $publication['publication_id'] ."  /></span>   ";
                    }
                    
                    ?>
                    <input type='button' value='save tags' class='save_tags_btn'  data-pub-id="<?= $publication['publication_id'] ?>"   />
                </div>
            </div>
       
         </div>
     <? } 
    
}

else { 
    echo "This is not the thinktank you are looking for"; 
    
}

?>