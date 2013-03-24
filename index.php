<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    include('twitter_scripts/twitter_connect.php');
?>
    
<div class='row'>
    <div class='span3 module'>
        <h2>Keywords</h2>
        <?  include('fragments/keywords.php') ?>
    </div>

    <div class='span3 module'>
        <h2>Most Retweeted</h2>
        <?  include('fragments/most_retweeted.php') ?>
    </div>

    <div class='span3 module'>
        <h2>Most Mentioned People</h2>
        <? include('fragments/influencers.php') ?>
    </div>

    <div class='span3 module'>
        <h2>Most mentioned thinktanks</h2>
        <? include('fragments/top_thinktanks.php') ?>
    </div>
</div> 

<?  include('fragments/footer.php'); ?>
        

