<? 

include('twitter_connect.php');

include('../header.php');

echo "<h1>Collect Twitter Activity</h1>";

$people = $db->fetch("SELECT * FROM people WHERE twitter_handle='' LIMIT 20");

?>
<script>

$(document).ready(function(){ 
    $(".handle_submit").click(function(){ 
        var id = $(this).attr("data-id");
        var twitter_handle = $(this).attr("data-twitter-handle");
        window.temp = this;

        $.get('/twitter/save_twitter_handle.php?id=' + id + '&twitter_handle=' + twitter_handle, function(data){ 
           $(window.temp).css('background-color', 'red'); 
        });
        
    
    });
    
    
});


</script>

<?

foreach($people as $person) {
    echo "<h1>".$person['name_primary']."<h1>";
    
    $search_string = "'" . $person['name_primary'] . "'";
    
    $data = $connection->get('users/search', array('q' => $search_string));
    $image = $db->fetch("SELECT * FROM people_thinktank WHERE person_id='" . $person['person_id'] . "' && image_url!='' LIMIT 10");
    
    echo "<p>" . $image[0]['role'] . "</p>";
    echo "<p>" . $image[0]['description'] . "</p>";
    
    $i = 0; 
    foreach($data as $result) { 
        if ($i < 5) {
            echo "<h3><a href='http://twitter.com/".$result->screen_name."' target='blank'>" . $result->name . "/" . $result->screen_name . "</a></h3>";
            
            echo "<p>" . $result->description . "</p>";
        
            echo "<img src='" . $result->profile_image_url . "' width='200' />";
            echo "<img src='" . $image[0]['image_url'] . "' width='200' />";
            echo "<br />";
            echo "<br/>";
            echo "<input type='button' class='handle_submit' data-id='". $person['person_id'] . "' data-twitter-handle='" . $result->screen_name ."' value='save' />"; 
            echo "<hr/>";
        }
        $i++; 
    }
    

    echo "<br/>--------------------------------------------------------------------------------------------------------<br/>"; 
}




?>



<? include('../footer.php'); ?>