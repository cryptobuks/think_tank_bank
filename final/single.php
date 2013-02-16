<?
    include('../ini.php');
    @$url = explode("/",$_GET['url']);
    $db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

    $page_no = 0;
    
    $person_id = $_GET['person_id']; 
    
    function cmp_by_followerNumber($a, $b) {
        if ($a['network_inclusion']!= 4 && $b['network_inclusion']==4) { 
            $ret_val = -1; 
        } 
        
        else if ($b['network_inclusion']!= 4 && $a['network_inclusion']==4) { 
            $ret_val = 1; 
        }
        
        else {
            $ret_val = $b["follower_numbers"] - $a["follower_numbers"];
        }
        
        return $ret_val;
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
    
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body class='single'>

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
            
              <li class="active"><a href="/final/">Home</a></li>

            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
        <h1>People</h1>
        
        <div class='headings row'>
            <div class='span3'>
                
            </div>
            
            <div class='span3'>
                <h3>Followers</h3>
            </div>
            <!--
            <div class='span2'>
                <h2>Following</h2>
            </div>
            -->
            <div class='span3'>
                <h3>Publications</h3>
            </div>    

            <div class='span3'>
                <h3>Mentions</h3>
            </div>            
        
        </div>
        <?

            $person = $db->fetch("SELECT * FROM people WHERE person_id='".$person_id."'");
            
            if (!empty($person[0]['twitter_id'])) {
                $query ="SELECT * FROM people_followees WHERE followee_id='" . $person[0]['twitter_id'] . " ' ";
                
                $followers = $db->fetch($query);
                
                //print_r($followers);
                //$query ="SELECT * FROM people_followees WHERE follower_id='".$person[0]['twitter_id']."'";
                //$follows = $db->fetch($query);                
                
                $publications = $db->fetch("SELECT * FROM people_publications WHERE person_id='".$person[0]['person_id']."'");  
                $interactions = $db->fetch("SELECT * FROM people_interactions WHERE target_id='".$person[0]['twitter_id']."'");                
                
                $query_quotient = "SELECT *, count(*)
                    FROM people_followees
                    INNER JOIN aliens ON people_followees.follower_id = aliens.twitter_id
                    WHERE  people_followees.followee_id=".$person[0]['twitter_id']." GROUP BY aliens.organisation 
                    ORDER BY count(*) DESC";
                
                $thinktank_quotient_query = "SELECT *, count(*) FROM `people_followees` WHERE followee_id=".$person[0]['twitter_id']." && network_inclusion=2" ;
                $thinktank_quotient = $db->fetch($thinktank_quotient_query);
                
                
                $quotients = $db->fetch($query_quotient);
            }
        ?>
        
        <div class=' listing_row row'>
            <div class='row_container  '>
                <div class='row_height '>
                    <div class='span3'>
                    
                        <h4><?=$person[0]['name_primary'] ?></h4>
                        
                        <?
                            $jobs = $db->fetch("SELECT * FROM people_thinktank WHERE person_id = '".$person[0]['person_id']."'"); 
                            foreach($jobs as $job) { ?>
                                <?
                                    $thinktank = $db->fetch("SELECT * FROM thinktanks WHERE thinktank_id = '".$job['thinktank_id']."'"); 
                                ?>    
                            
                                <p><strong><?=$thinktank[0]['name'] ?></strong></p>    
                                <p><?=$job['role'] ?></p>
                                <hr/>
                            <? } ?>
                    </div>  
            
                    <div class='span3'>
                        <?  
                    
                        $sorted_array = array();
                        $colors_array = array();
                        $sorted_array[] = "['Organisation', 'Number']";
                        
                        foreach($quotients as $quotient) { 
                            //echo "<p>".$quotient['organisation']. '--'.$quotient['count(*)']."</p>";
                            $name = $quotient['organisation'];
                                                                                 
                            $number = $quotient['count(*)']; 
                            $sorted_array[]= "['$name', $number]";
                             
                            if($name== 'Con') { 
                                $colors_array[]= "'#370cf5'";
                            }
                            
                            else if($name== 'Lab') { 
                                $colors_array[]= "'#f50c17'";
                            }
                            
                            else if($name == 'LibDem') { 
                                $colors_array[]= "'#fdbb30'";
                            }
    
                            else if($name == 'Journalist') { 
                                $colors_array[]= "'#70e3e7'";
                            }
                                                        
                            else { 
                                $colors_array[]= "'#ccc'";
                            }
                         }
                         
                         
                         $name = 'Thinktanks'; 
                         $number = $thinktank_quotient[0]['count(*)'];
                         
                         $sorted_array[]= "['$name', $number]";
                         $colors_array[]= "'#a20b9d'"; 
                         $javascript_array = implode(',', $sorted_array);
                         
                         
                         
                      
                         ?>
                         <script type="text/javascript">
                               google.load("visualization", "1", {packages:["corechart"]});
                               google.setOnLoadCallback(drawChart);
                               function drawChart() {
                                 var data = google.visualization.arrayToDataTable([
                                    <?= $javascript_array ?>
                                 ]);

                                 var options = {
                                   width: 300,
                                   height: 300,
                                   title: 'Followers by grouping',
                                   colors: [<?= implode(',', $colors_array); ?>]
                                 };


                                 var chart = new google.visualization.PieChart(document.getElementById('chart_div_<?=$person[0]['person_id'] ?>'));
                                 chart.draw(data, options);
                               }
                             </script>
                             <div id="chart_div_<?=$person[0]['person_id'] ?>" style="width: 300px; height: 300px;"></div>
                                                 
                    
  
                        <?
                        foreach ($followers as $follower) { 

                            if ($follower['network_inclusion'] == 2) { 
                                $query = "SELECT * FROM people WHERE twitter_id ='".$follower['follower_id'] ."'";
                                $list_info = $db->fetch($query);                             
                                
                                echo "<p>" . $list_info[0]['name_primary'] . " -- Thinktank</p>"; 
                            }

                            if ($follower['network_inclusion'] == 1) { 
                                $query = "SELECT * FROM aliens WHERE twitter_id ='".$follower['follower_id'] . "'";
                                
                                $list_info = $db->fetch($query);
                                echo "<p>" . $list_info[0]['name'] . " - ".  $list_info[0]['organisation'] . "</p>"; 
                            }                         
                        }
                        ?>
                    </div>

         

                    <div class='span3 pubs'>
                    
                        <?
                            foreach($publications as $publication) { 
                                $publicaiton_info = $db->fetch("SELECT * FROM publications WHERE publication_id='".$publication['publication_id']."'");
                                echo "<p><a href='" . $publicaiton_info[0]['url'] ."'>" . $publicaiton_info[0]['title'] . "</a></p>";
                            }
                
                        ?>
                
                    </div>
                                   
                    <div class='span3 mentions'>
                    

                         <?

                             foreach($interactions as $interaction) { 
                                 $person_query = "SELECT * FROM people WHERE twitter_id = '".$interaction['originator_id']."'";
                                 $person = $db->fetch($person_query); 
                                 if(empty($person)) { 
                                     $alien_query = "SELECT * FROM aliens WHERE twitter_id = '".$interaction['originator_id']."'";

                                     $alien_result = $db->fetch($alien_query);
                                     echo "<p><strong> ".$alien_result[0]['name']."</strong> ". $interaction['text']. "</p>";
                                 }
                                 else { 
                                     echo "<p><a href='/final/single.php?person_id=". $person[0]['person_id'] ."'><strong> ".$person[0]['name_primary']."</strong> </a>". $interaction['text']. "</p>";
                                 }
                             } 

                         ?>

                    </div>
                    <br class='clearfix' />       
                </div>
                           
            </div>
            
        </div>
        
 
        
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.8.0.min.js"><\/script>')</script>
    <script src="js/main.js"></script>

  </body>
</html>
