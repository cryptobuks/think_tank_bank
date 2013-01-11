<?
    include('../ini.php');
    @$url = explode("/",$_GET['url']);
    $db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);
    if(isset($_GET['page'])) { 
        $page_no = $_GET['page'] * 20 ;
    }
    else { 
        $page_no = 0; 
    }    
    
    
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
          <a class="brand" href="/people/">Think tanks</a>
      
          
          <div class="nav-collapse collapse">
           
            <ul class="nav">
            
              <li class="active"><a href="/final/">1</a></li>
              
              <?
                $count= $rank_query = $db->fetch("SELECT * FROM people_rank LIMIT 230");
                
                $number_of_pages = count($count) /20;
              
                for($i = 1 ; $i< $number_of_pages; $i++){ 
                  ?>
                    <li class="active"><a href="/final/?page=<?= $i ?>"><?= $i+1 ?></a></li>
                  <?  
                } 
              ?>
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
                <h3>Retweets</h3>
            </div>            
        
        </div>
        <?
            $rank_query = "SELECT * FROM people_rank ORDER BY rank DESC LIMIT $page_no, 20";
            $ranks = $db->fetch($rank_query);
            
            foreach($ranks as $rank) { 
            $person = $db->fetch("SELECT * FROM people WHERE person_id='".$rank['person_id']."'");
            
            if (!empty($person[0]['twitter_id'])) {
                $query ="SELECT * FROM people_followees WHERE followee_id='" . $person[0]['twitter_id'] . " ' ";
                
                $followers = $db->fetch($query);
                
                $query_quotient = "SELECT *, count(*)
                FROM people_followees
                INNER JOIN aliens ON people_followees.follower_id = aliens.twitter_id
                WHERE  people_followees.followee_id=".$person[0]['twitter_id']." GROUP BY aliens.organisation 
                ORDER BY count(*) DESC";
                
                $quotients = $db->fetch($query_quotient);
                
                $thinktank_quotient_query = "SELECT *, count(*) FROM `people_followees` WHERE followee_id=".$person[0]['twitter_id']." && network_inclusion=2" ;
                $thinktank_quotient = $db->fetch($thinktank_quotient_query);
                
                
                $publications = $db->fetch("SELECT * FROM people_publications WHERE person_id='".$rank['person_id']."'");  
                //$interactions = $db->fetch("SELECT * FROM people_interactions WHERE target_id='".$person[0]['twitter_id']."'");                
                
            }
        ?>
        
        <div class=' listing_row row'>
            <div class='row_container  '>
                <div class='row_height '>
                    <div class='span3'>
                    
                        <h4><a href='/people/single.php?person_id=<?=$person[0]['person_id'] ?>'><?=$person[0]['name_primary'] ?></a></h4>
                        
                        <? if (!empty($person[0]['twitter_image'])) { 
                            $picture = 'done';
                        ?>
                        <p><img src='<?=$person[0]['twitter_image'] ?>' /></p>
                            
                        <? } else {$picture = '';} ?>
                        
                        
                        <p>Followers in network: <?= count($followers) ?></p>
                        <!-- <p>Retweets in network: <?= count($interactions) ?></p> -->
                        <?
                            $jobs = $db->fetch("SELECT * FROM people_thinktank WHERE person_id = '".$rank['person_id']."'"); 
                            foreach($jobs as $job) { ?>
                                <?
                                    $thinktank = $db->fetch("SELECT * FROM thinktanks WHERE thinktank_id = '".$job['thinktank_id']."'"); 
                                ?>    
                            
                                <p><strong><?=$thinktank[0]['name'] ?></strong></p>    
                                <p><?=$job['role'] ?></p>
                                <?
                                    if ($picture == '') { ?>
                                         <p><img src='<?=$job['image_url'] ?>' /></p>
                                    <? }
                                
                                ?>
                                <hr/>
                            <? } ?>
                    </div>  
            
                    <div class='span3'>
                            
                        <?  
                    
                         $sorted_array = array();

                         foreach($quotients as $quotient) { 
                             echo "<p>".$quotient['organisation']. '--'.$quotient['count(*)']."</p>";
                         }
                         
                         echo "<p> Thinktanks: --".$thinktank_quotient[0]['count(*)']."</p>";
                         ?>
                         <script type="text/javascript">
                               google.load("visualization", "1", {packages:["corechart"]});
                               google.setOnLoadCallback(drawChart);
                               function drawChart() {
                                 var data = google.visualization.arrayToDataTable([
                                   ['Task', 'Hours per Day'],
                                   ['Work',     11],
                                   ['Eat',      2],
                                   ['Commute',  2],
                                   ['Watch TV', 2],
                                   ['Sleep',    7]
                                 ]);

                                 var options = {
                                   title: 'My Daily Activities'
                                 };

                                 var chart = new google.visualization.PieChart(document.getElementById('chart_div_<?=$person[0]['person_id'] ?>'));
                                 chart.draw(data, options);
                               }
                             </script>
                             <div id="chart_div_<?=$person[0]['person_id'] ?>" style="width: 200px; height: 200px;"></div>
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
                        /*
                            foreach($interactions as $interaction) { 
                                $person_query = "SELECT * FROM people WHERE twitter_id = '".$interaction['originator_id']."'";
                                $person = $db->fetch($person_query); 
                                if(empty($person)) { 
                                    $alien_query = "SELECT * FROM alien_cache WHERE twitter_id = '".$interaction['originator_id']."'";
                                    
                                    $alien_result = $db->fetch($alien_query);
                                    echo "<p><strong> ".$alien_result[0]['name']."</strong> ". $interaction['text']. "</p>";
                                }
                                else { 
                                    echo "<p><a href='/people/single.php?person_id=". $person[0]['person_id'] ."'><strong> ".$person[0]['name_primary']."</strong> </a>". $interaction['text']. "</p>";
                                }
                            } 
                        */
                        ?>
                    </div>
                    <br class='clearfix' />       
                </div>
                           
            </div>
            <p class='toggle'><i class="icon-double-angle-down icon-3x"></i></p> 
        </div>
        
        <? } ?>
        
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.8.0.min.js"><\/script>')</script>
    <script src="js/main.js"></script>
    
    

  </body>
</html>
