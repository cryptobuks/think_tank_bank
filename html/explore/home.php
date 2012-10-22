
<input type='text' val='' id='search_query' /> 
<input type='button' id='btn_search' value='&nbsp;search&nbsp;' name='search' /> 

<br/><br/>

<?
$results = $db->search_thinktanks();

foreach($results as $result) { ?>

    <div class='row'>    
        <p><a href='/explorer/thinktanks/<?=$result['computer_name']; ?>'> <?=$result['name']; ?> </a> </p>
    </div>
<?}

?>

