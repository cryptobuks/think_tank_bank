<? include ('header.php'); ?>

<h1>Gather Data From Thinktanks </h1>

<?

$results = $db->search_thinktanks();


foreach($results as $result) { ?>

   
            <div class='grid_12 alpha'>
                <h2><?=$result['name']; ?></h2>
            </div>
            
            <div class='grid_6'>
                <p>
                    <input type='button' data-type='people' data-name='<?=$result['computer_name']; ?>' data-id='<?=$result['thinktank_id']; ?>'  class='btn_gather' value='People' >
                    <input type='button' data-debug='more' data-type='people' data-name='<?=$result['computer_name']; ?>' data-id='<?=$result['thinktank_id']; ?>'  class='btn_gather' value='People More' >
                    <input type='button' data-debug='less' data-type='people' data-name='<?=$result['computer_name']; ?>' data-id='<?=$result['thinktank_id']; ?>'  class='btn_gather' value='People Less' >
                    
                </p>
                <iframe id='people_<?=$result['thinktank_id']; ?>' class='results_iframe' > </iframe>
            </div>
            
            <div class='grid_6'>
                <p><input type='button' data-type='publication' data-name='<?=$result['computer_name']; ?>' data-id='<?=$result['thinktank_id']; ?>' class='btn_gather' value='Publications' ></p>
                <iframe id='publication_<?=$result['thinktank_id']; ?>' class='results_iframe' > </iframe>
            </div>
        
    <? } 

include ('footer.php'); 

?>