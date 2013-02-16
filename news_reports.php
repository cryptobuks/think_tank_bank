<?

include('header.php'); 

$thinktanks = $db->fetch("SELECT * FROM thinktanks LIMIT 15");

$accountKey = 'h1w583Sm2BWleVp3jGdBdP8VTIje88OlDbd8xTnOfEg=';

foreach ($thinktanks as $thinktank) {
    // get search results
    
    $q= '"'.  urlencode($thinktank['name']) . '" think tank';
    
    $articles = array();
     for($i=0; $i<18; $i=$i+20) {
        $result = sitesearch ($q, $_SERVER['HTTP_HOST'], $accountKey , $i);
        $articles = array_merge($result['d']['results'], $articles);
    }
    
    $unique_articles =  $articles;//array_unique($articles, SORT_REGULAR); 
    
    $number = count($unique_articles);    

    echo "<h2>" . $thinktank['name'] . "</h4>";
    
    echo "<ol>" ;
    
    
    foreach($unique_articles as $article) {
        $date         = strtotime($article['Date']);
        $today        = time();
        $time_horizon = 60*60*24*6;
     
        if($date > ($today -$time_horizon) ) {
            echo "<li>"; 
            echo " <h4><a href='".$article['Url']."'>".$article['Title']. " (". $article['Source'] .")" .'</a></h4>';
            echo " <p>".$article['Description'].'</p>';
            echo " <p>".date("F j, Y", $date).'</p>';
            echo "</li>";
        }
        
    } 
    echo "</ol>"; 
    $thinktank_id   =  $thinktank['thinktank_id'];
    $date           =  time();
    
    $db->query("INSERT INTO thinktank_news_mentions (thinktank_id, number, date) VALUES ('$thinktank_id', '$number', '$date')"); 
    
}    



function sitesearch ($query, $site, $accountKey, $count=0){
// code from http://go.microsoft.com/fwlink/?LinkID=248077
   
  $context = stream_context_create(array(
  'http' => array(
    'request_fulluri' => true,       
    'header'  => "Authorization: Basic " . base64_encode($accountKey . ":" . $accountKey)
  ) 
  )); 
  $ServiceRootURL =  'https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/News?Market=%27en-GB%27&NewsSortBy=%27Date%27&NewsCategory=%27rt_Politics%27&';
  $WebSearchURL = $ServiceRootURL . '$format=json&Query=';  

  $request = $WebSearchURL . urlencode("'$query'"); // note the extra single quotes
  if ($count) $request .= "&\$top=40&\$skip=$count"; // note the dollar sign before $top--it's not a variable!
  return json_decode(file_get_contents($request, 0, $context), true);
}


include('footer.php'); 

?>