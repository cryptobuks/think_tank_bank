<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    
?>
    

<div class='row-fluid'>
    
    
    
       
       <?
            $link_query = "SELECT *, COUNT(DISTINCT twitter_id) as number_of_mentions 
            FROM `links` 
            WHERE links.time > $old
            GROUP BY expanded_url 
            HAVING number_of_mentions > 2
            ORDER BY number_of_mentions DESC 
            LIMIT 10
            "; 

            
            $links = $db->fetch($link_query);
            
            echo "<div class='span6'>";
            
            foreach($links as $link) { 
                
                $id = md5($link['expanded_url']);
                
                $title = getUrlTitle($link['expanded_url']);
                if (empty($title)) { 
                    $title = $link['expanded_url'];
                }
                echo "<h4><a data-title='$id' href='". $link['expanded_url']. "'>" . $title ." </a> </h4>";
                
             
            }
                
            echo "</div><div class='span6'>";    
                
            foreach($links as $link) { 
                
                $id = md5($link['expanded_url']);
                
                
                echo "<ul data-tweets='$id'>";
                
                $person_query = "SELECT * FROM links 
                JOIN people ON links.twitter_id = people.twitter_id 
                WHERE `expanded_url` = '" . $link['expanded_url'] . "'
                ORDER BY time ASC";
                
                $people = $db->fetch($person_query);
                
                foreach($people as $person) { 
    
                    $tweet_query = "SELECT * FROM tweets 
                    WHERE `time`  = '" . $person['time'] . "'
                    LIMIT 1" ;

                    $tweet = $db->fetch($tweet_query);
                    
                    ?>
                    <blockquote class="twitter-tweet"><p>Search API will now always return "real" Twitter user IDs. The with_twitter_user_id parameter is no longer necessary. An era has ended. ^TS</p>&mdash; Twitter API (@twitterapi) <a href="https://twitter.com/twitterapi/status/<?= $tweet[0]['tweet_id'] ?>" data-datetime="2011-11-07T20:21:07+00:00">November 7, 2011</a></blockquote>
                <?    
                }

                echo "</ul><br/>";
            }
            
            echo "</div>";  
       ?>
        

    
</div> 

<?  include('fragments/footer.php'); ?>
        

