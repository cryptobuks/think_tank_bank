<? 

require_once('twitter_connect.php');


include('../header.php');

echo "<h1>Get a permanent token</h1>";


  $data = $connection->get('users/show', array('screen_name' =>'jimmytidey'));
print_R($data);

?>



<? include('../footer.php'); ?>