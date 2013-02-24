<?
include(__DIR__ . '/../ini.php');
include(__DIR__ . '/../twitter_scripts/twitter_connect.php');
include(__DIR__ . '/../fragments/header.php');

//get an array of thinktanks for the select drop down 
$thinktanks = $db->fetch("SELECT * FROM thinktanks");
 
if(isset($_POST['form_subimitted'])) { 
     $twitter_name  = $_POST['twitter_name'];
     $name          = $_POST['name'];
     $thinktank_id  = $_POST['thinktank'];
     echo "THINKTANK ID: " . $thinktank_id ;
     
     //first need to find Twitter ID
     if (!empty($twitter_name)) {  
         $info = $connection->get('users/show', array(
             'screen_name' => $twitter_name,
         )); 
         
         $user_id       = $info->id; 
         $image_url     = $info->profile_image_url; 
         $start_date    = time();
         echo "<p>TWITTER USER ID: $user_id</p>";
         $db_output  = $db->save_job($name, $thinktank_id, 'official twitter account', '', $image_url, '', true, $user_id, $image_url);

         print_R($db_output);
     }
}

?>

<form method='post' action='<?= $_SERVER['PHP_SELF']; ?>'>
    <label for='name'>Name</label>
    <input type='text' id='name' name='name'  />

    <label for='twitter_name'>Twitter Name</label>
    <input type='text' id='twitter_name' name='twitter_name'  />

    <label for='thinktank'>Thinktank</label>
    <select name='thinktank' id='thinktank'>
        <?
            foreach($thinktanks as $thinktank) { 
                $thinktank_id = $thinktank['thinktank_id'];
                $name = $thinktank['name'];
                echo "<option value='$thinktank_id'>$name</option>";
            }
        ?>
    </select>
    <br/>
    <input type='submit' id='form_subimitted' name='form_subimitted' value='save'>

</form>




<? include(__DIR__ . '/../fragments/footer.php') ?>