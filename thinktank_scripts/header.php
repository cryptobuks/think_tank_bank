<? 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once($root .'/ini.php');

@$url = explode("/",$_GET['url']);

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="/thinktank_scripts/css/normalize.css">
        <link rel="stylesheet" href="/thinktank_scripts/css/main.css">
        <link rel="stylesheet" href="/thinktank_scripts/css/960.css">

        <script src="/thinktank_scripts/js/vendor/modernizr-2.6.1.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.0.min.js"><\/script>')</script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

    </head>
    <body>
        <?
            $get_body_class = $_SERVER["PHP_SELF"];  
            $get_body_class = explode("/", $get_body_class);
        ?>
    <div id='container' class='container_12 <?= $get_body_class[1] ?>'  >   
        
        