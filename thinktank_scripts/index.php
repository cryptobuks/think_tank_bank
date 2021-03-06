<? include('header.php'); ?>

<h1>Think Tank Bank</h1>

<div class='alpha grid_4'>
    <h2>Utilities</h2>
    <ul>
        <li><a href='manual_gather.php'>Manage data scrape</a></li>
        <li><a href='/news_mentions_scripts/'>News Scan</a></li>        
        <li><a href='errors.php'>View errors &amp; notices</a></li>
        <li><a href='log.php'>View log</a></li>
        <li><a href='tags/'>Manage Tags</a></li>

    </ul>
</div>

<div class='alpha grid_4'>
    <h2>Data by thinktank</h2>
    <ol>
    <?
    $results = $db->search_thinktanks();
    foreach($results as $result) { ?>
            <li><a href='explore/thinktanks/<?=$result['computer_name']; ?>'> <?=$result['name']; ?> </a></li> </p>
    <? } ?>
    </ol>
</div>    

<div class='alpha grid_4'>
    <h2>Search Reports</h2>
    <input type='text' val='' id='search_query' /> 
    <input type='button' id='btn_search' value='&nbsp;search&nbsp;' name='search' /> 

</div>


<? include('footer.php'); ?>