<? 

include('twitter_connect.php');

include('../header.php');

echo "<h1>Add Photos</h1>";

$people = $db->fetch("SELECT * FROM people WHERE twitter_id != '' && twitter_id != '-' LIMIT 400,100 ");
$chunks = array_chunk($people, 100); 


foreach ($chunks as $chunk) { 
    $query_array = array();
    
    foreach($chunk as $chunklet) { 
        $query_array[] = $chunklet['twitter_id'];
    }
    
    $query_string = implode(',',$query_array);
    $list = $connection->get('users/lookup', array('user_id' =>  $query_string));
    
    foreach($list as $data) {

        $twitter_image = addslashes($data->profile_image_url);
        $twitter_id = addslashes($data->id);

        echo "<br/>";

        if (empty($is_it_in)) {
            $query = "UPDATE people SET twitter_image='$twitter_image' WHERE twitter_id= '$twitter_id'";
            echo $query;
            $db->query($query);
            echo "<br/>";
           
        }


        echo "<br/>";
    }
    
}    


?>



<? include('../footer.php'); ?>