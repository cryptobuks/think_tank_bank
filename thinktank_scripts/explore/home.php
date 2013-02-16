


<div class='alpha grid_4'>
    <h2>By thinktank</h2>
    <ol>
    <?
    $results = $db->search_thinktanks();
    foreach($results as $result) { ?>
            <li><a href='thinktanks/<?=$result['computer_name']; ?>'> <?=$result['name']; ?> </a></li> </p>
    <? } ?>
    </ol>
</div>    

<div class='alpha grid_4'>
    <h2>Search Reports</h2>
    <input type='text' val='' id='search_query' /> 
    <input type='button' id='btn_search' value='&nbsp;search&nbsp;' name='search' /> 

    <h2>Search People</h2>
    <input type='text' val='' id='search_query' /> 
    <input type='button' id='btn_search' value='&nbsp;search&nbsp;' name='search' /> 
</div>