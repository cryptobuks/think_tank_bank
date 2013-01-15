<? 

include('twitter_connect.php');

include('../header.php');

echo "<h1>Cache ALiens</h1>";

$people = $db->fetch("SELECT * FROM alien_cache LIMIT 200, 1000");
$chunks = array_chunk($people, 100); 


foreach ($chunks as $chunk) { 
    $query_array = array();
    
    foreach($chunk as $chunklet) { 
        $query_array[] = $chunklet['twitter_id'];
    }
    
    $query_string = implode(',',$query_array);
    $list = $connection->get('users/lookup', array('user_id' =>  $query_string));
    
   
    
    
    foreach($list as $data) { 
        $twitter_id = addslashes($data->id);
        $name = addslashes($data->name);
        $screen_name = addslashes($data->screen_name);
        $description = addslashes($data->description);
        $followers_count = $data->followers_count;    
        echo "<br/>";

        if (empty($is_it_in)) {
            $query = "UPDATE alien_cache SET name='$name', screen_name='$screen_name', description='$description', followers_count='$followers_count' WHERE twitter_id= '$twitter_id'";
            echo $query;
            $db->query($query);
            echo "<br/>";
           
        }


        echo "<br/>";
    }
    
}    


?>



<? include('../footer.php'); ?>