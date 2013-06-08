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

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body>
      
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            
            <div class='container'>
                <div class='row'>
                    <div class='span2'>
                        <a class="brand" href="/">Think tank bank</a>
                    </div>
                    <div class='offset3 span7' id='search'>
                        <input type='text' autocomplete="off" id='search_text' value='search' />
                        <select id='search_type'>
                            <option value='person'>Person</option>
                            <option value='publication'>Publication</option>
                        </select>
            
                        <input type='button' value='search' id='search_submit' />
                    </div> 
                </div>       
            </div>    
        </div>
    </div>      



    <div class="container">