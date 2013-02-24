<?
include(__DIR__ . '/../ini.php');
include(__DIR__ . '/../twitter_scripts/twitter_connect.php');
include(__DIR__ . '/../fragments/header.php');

$aliens = $db->fetch('SELECT * FROM aliens LIMIT 850, 50');

foreach($aliens as $alien) { 
    $alien_id = $alien['twitter_id'];
    $alien_name = $alien['name'];
    
    echo "<p>$alien_name ($alien_id)</p>";
    
    $info = $connection->get('users/show', array(
        'user_id' => $alien_id,
    )); 
    
    $twitter_handle = $info->screen_name; 
    $image_url      = $info->profile_image_url;
    
    $query = "UPDATE aliens SET twitter_handle='$twitter_handle', image_url='$image_url' WHERE twitter_id='$alien_id'";
    $db->query($query);
    echo $query;
    echo "<hr/>";
} 



?>





<? include(__DIR__ . '/../fragments/footer.php') ?>