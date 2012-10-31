<?

include('header.php'); 

$messages = $db->fetch("SELECT * FROM `log` WHERE type='error' || type='notice' ORDER BY date desc LIMIT 5000");


?>

<h1>Errors &amp; Notices</h1>

<?

foreach($messages as $message) { 
        $type       = $message['type']; 
        $content    = $message['content'];         
        $date       = date("F j, Y, g:i a", $message['date']);                     
        ?>
        <p class='grid_1 <?=$type ?> log'><?=$type ?></p>
        <p class='grid_8'><?=$content ?></p>
        <p class='grid_3'><?=$date ?></p>
        <br class='clearfix' />
        
        <?
    }
    
?>



<? include('footer.php');  ?>