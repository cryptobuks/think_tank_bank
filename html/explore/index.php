
<? include ('../header.php'); ?>

<h1>Think Tank Explorer</h1>

<?

if (@empty($url[0]) || !isset($url[0])) { 
   include('home.php');
}

else if (@$url[0] == 'thinktanks' && @$url[2] == 'people') { 
   include('thinktanks_people.php');
}

else if (@$url[0] == 'thinktanks' && @$url[2] == 'publications') { 
   include('thinktanks_publications.php');
}


else if (@$url[0] == 'search') { 
    include('search.php'); 
}

else if (@$url[0] == 'thinktanks') { 
    include('thinktanks.php'); 
}

include ('../footer.php'); 

?>