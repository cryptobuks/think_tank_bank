<?


if (isset($url[1]) && !empty($url[1])) { 
    $query = $url[1];
    echo  "<h2>Search: " .  $query . "</h2>";
    
    $results = $db->fetch("SELECT * FROM publications WHERE title LIKE '%$query%' "); 

  
    foreach ($results as $result) { ?>
        <div class='row'> 
            <div class='double_column'> 
                <a href='<?= $result['url'] ?>'><?= $result['title']?></a>
            </div>
            
            <div class='column'> 
                <p><?= date("F j, Y", $result['publication_date']) ?> </p>
            </div>
               
            <div class='column'> 
                <img src='<?=$result['image_url'] ?>' class='pub_image' />
            </div>
                                 
            <br class='clearfix' />
        </div>
    <? }
}

else { 
    echo "This is not the search term you are looking for"; 
    
}

?>