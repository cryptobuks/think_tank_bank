<?

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once($root .'/ini.php');


@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);



if(isset($_GET['page'])) { 
    $page_no = $_GET['page'] * 20 ;
}
else { 
    $page_no = 0; 
}



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
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <style>
    body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
    }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="/final/">Think tanks</a>
      
          
          <div class="nav-collapse collapse">
           
            <ul class="nav">
            
              <? if ($page_no == 0) { ?>   
              <li class="active"><a href="/final/">1</a></li>
              <? } else { ?>
              <li ><a href="/final/">1</a></li>
              <?
              }
                $count= $rank_query = $db->fetch("SELECT * FROM people_rank LIMIT 230");
                
                $number_of_pages = count($count) /20;
                
                
              
                for($i = 1 ; $i< $number_of_pages; $i++){ 
                  
                    if ($i == $page_no/20) { 
                  ?>
                    <li class="active"><a href="/final/?page=<?= $i ?>"><?= $i+1 ?></a></li>
                  <?
                  }
                  else { 
                      ?>
                      <li><a href="/final/?page=<?= $i ?>"><?= $i+1 ?></a></li>
                    <?
                  }  
                } 
              ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">