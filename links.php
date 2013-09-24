<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    
?>
    

<div class='row-fluid'>
    
    
    <div class='span8 module'>
       
       <?
            $link_query = "SELECT *, COUNT(DISTINCT twitter_id) as number_of_mentions 
            FROM `links` WHERE time > $old 
            GROUP BY expanded_url 
            HAVING number_of_mentions > 2
            ORDER BY number_of_mentions DESC"; 
           
            $links = $db->fetch($link_query);
            
            echo $link_query;
            
            foreach($links as $link) { 
                
                $title = getUrlTitle($link['expanded_url']);
                if (empty($title)) { 
                    $title = $link['expanded_url'];
                }
                echo "<h4><a href='". $link['expanded_url']. "'>" . $title ." </a></h4>";
                
                $person_query = "SELECT * FROM links 
                JOIN people ON links.twitter_id = people.twitter_id 
                WHERE `expanded_url` = '" . $link['expanded_url'] . "'
                ORDER BY time ASC";
                
                
                $people = $db->fetch($person_query);
                
                echo "<ul>";
                
                foreach($people as $person) { 
                    echo "<li>" . $person['name_primary'] . " - " . date('D, F j, g:i a', $person['time']) .  "</li>";
                }
                
                echo "</ul><br/>";
            }
            
            
       ?>
       
    </div>
    
    <div class='span4' >
     
    </div>
    
</div> 

<?  include('fragments/footer.php'); ?>
        

