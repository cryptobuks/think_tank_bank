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
            ORDER BY number_of_mentions DESC 
            LIMIT 5
            "; 
            
            $links = $db->fetch($link_query);

            
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
                
                foreach($people as $person) { ?>
                    <blockquote class="twitter-tweet"><p>Search API will now always return "real" Twitter user IDs. The with_twitter_user_id parameter is no longer necessary. An era has ended. ^TS</p>&mdash; Twitter API (@twitterapi) <a href="https://twitter.com/twitterapi/status/<?= $link['tweet_id'] ?>" data-datetime="2011-11-07T20:21:07+00:00">November 7, 2011</a></blockquote>
                <?    
                }

                echo "</ul><br/>";
            }
            
            
       ?>
        
    </div>
    
    <div class='span4' >
     
    </div>
    
</div> 

<?  include('fragments/footer.php'); ?>
        

