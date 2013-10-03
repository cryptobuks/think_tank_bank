<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    
?>
    

<div class='row-fluid'>
    
    
    
       
       <?
            $link_query = "SELECT *, COUNT(DISTINCT twitter_id) as number_of_mentions 
            FROM `links` 
            WHERE time > $old
            GROUP BY expanded_url 
            HAVING number_of_mentions > 2
            ORDER BY number_of_mentions DESC 
            LIMIT 10"; 
            
            $links = $db->fetch($link_query);
            
            echo "<div class='span6 links_list'><ol>";
            
            echo "<h4>Most Shared Links</h4>";
            
            foreach($links as $link) { 

                
                $title = getUrlTitle($link['expanded_url']);
                if (empty($title)) { 
                    $title = $link['expanded_url'];
                }
                echo "<li>" . $title ."  <a class='link_link'  data-target='". urlencode($link['expanded_url']). "'> View Tweets &raquo;</a> </li>";
                
             
            }
                
                
            echo "</ol></div><div class='span6 link_display_target'>";    
                

            
            echo "</div>";  
       ?>
        

    
</div> 

<?  include('fragments/footer.php'); ?>
        

