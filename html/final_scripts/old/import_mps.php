<? 

include('../twitter/twitter_connect.php');

include('../header.php');

echo "<h1>Import MPs</h1>";
/* THIS FILE TO DISCOVER THE NETWORK */ 


        echo "<h1>NEW</h1>";
        $cursor = -1;
        while ($cursor!=0){
            $data = $connection->get('lists/members', array('slug' =>  'uk-national-journalists', 'owner_screen_name'=>'journalismnews', 'cursor'=> $cursor));
            //print_r($data);
  
            
            foreach($data->users as $id) {
            
                    print_r($id);
                    $name = addslashes($id->name);
                    $twitter_id = $id->id;
                    $organisation = 'journo';
                
                    $db->query("INSERT INTO aliens (name, twitter_id, organisation) VALUES ('$name', '$twitter_id', '$organisation')");
                
                

            }
            
            $cursor = $data->next_cursor_str;
         
        }
        
?>



<? include('../footer.php'); ?>