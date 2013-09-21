<?

include_once( __DIR__ . '/../ini.php');

@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);



if(isset($_GET['page'])) { 
    $page_no = $_GET['page'] * 20 ;
}
else { 
    $page_no = 0; 
}

$old = strtotime('last monday');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Thinktanks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <style>
    body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
    }
    </style>
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/js/jquery-1.8.0.min.js"><\/script>')</script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body>
      
      <div class="container">
          <div class="navbar">
            <div class="navbar-inner">
              <a class="brand" href="/">Think Tank Digest</a>
              <ul class="nav">
                
                <li class='<? if  ($_SERVER['PHP_SELF']  == '/index.php') {echo "active";} ?>'><a href="/">Home</a></li>
                <li class='<? if  ($_SERVER['PHP_SELF']  == '/links.php') {echo "active";} ?>'><a href="links.php">Link Analysis</a></li>
                <li class='<? if  ($_SERVER['PHP_SELF']  == '/thinktanks.php') {echo "active";} ?>'><a  href="thinktanks.php">Think Tanks</a></li>
                
              </ul>
                <p class='weekdate'>Tweets counted since last <? echo date('D, F j', $old)  ?></p>
            </div>
          </div>
     </div>



    <div class="container">