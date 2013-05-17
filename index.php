<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    include('twitter_scripts/twitter_connect.php');
?>
    
<div class='row'>
    <div class='span3 module'>
        <h2>Fastest Rising Thinktanks</h2>
        <?  include('fragments/thinktank_select.php') ?>
    </div>
    
    <div class='span3 module'>
        <h2>Fastest Rising People</h2>
        <?  include('fragments/people_select.php') ?>
    </div>

    
</div> 

<?  include('fragments/footer.php'); ?>
        

