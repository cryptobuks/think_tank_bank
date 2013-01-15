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
        <? if ($page_no == 0) {
            $time = time() - (60 * 60 *24);  
            $top_tweets         = $db->fetch("SELECT * FROM tweets JOIN people ON people.twitter_id = tweets.user_id JOIN people_thinktank ON people_thinktank.person_id = people.person_id JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id  WHERE exclude != '1' && time > $time  ORDER BY rts DESC LIMIT 5");
            $top_influencers    = $db->fetch("SELECT * , count( DISTINCT originator_id )
            FROM `people_interactions`
            JOIN people ON people.twitter_id = people_interactions.target_id
            JOIN people_thinktank ON people_thinktank.person_id = people.person_id
            JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id
            WHERE exclude !=1 && `time` > $time
            GROUP BY target_id
            ORDER BY count( DISTINCT originator_id ) DESC
            LIMIT 10 ");
            
            ?>
            <div class='row'>
                <div class='span6'>
                    <h1>Most Retweeted</h1>
                    <ol>
                    <?
                    foreach($top_tweets as $top_tweet) { 
                        echo "<li><strong><a href='/final/single.php?person_id=".$top_tweet['person_id'] ."'>".$top_tweet['name_primary']. "</a> (" .$top_tweet['name'].")</strong> ". $top_tweet['text']. "<br/><em class='highlight'>Retweeted ".$top_tweet['rts']." times</em></li>";
                                   
                    }
                    ?>
                    </ol>
                </div>

                <div class='span6'>
                <h1>Currently Influential</h1>
                <ol>
                <?
                foreach($top_influencers as $top_influencer) { 
                    $influenced = $db->fetch("SELECT * FROM people_interactions WHERE target_id = ". $top_influencer['target_id'] . " GROUP BY originator_id");
                    
                    echo "<li><strong><a href='/final/single.php?person_id=".$top_influencer['person_id'] ."'>".$top_influencer['name_primary']. "</a> (" .$top_influencer['name'].")</strong> Mentioned by: ";
                    $mention_array = array();
                    foreach  ($influenced as $influ) { 
                       $person = $db->fetch("SELECT * FROM people WHERE twitter_id = ". $influ['originator_id']); 
                        if(empty($person)) { 
                            $alien_query = "SELECT * FROM aliens WHERE twitter_id = '".$influ['originator_id']."'";

                            $alien_result = $db->fetch($alien_query);
                            $mention_array[] = $alien_result[0]['name'];
                        }
                        else { 
                            $mention_array[] = $person[0]['name_primary'];
                        }
                    }        
                    
                    echo implode($mention_array, ", ");
                    
                    echo "</li>";       
            
                }
                ?>
                </ol>
                </div>
            </div>
        
        <? } ?>
        
        <h2>Overall rankings</h2>
        
        <div class='headings row'>
            <div class='span3'>
                
            </div>
            
            <div class='span5'>
                <h3>Followers</h3>
            </div>



            <div class='span4'>
                <h3>Mentions</h3>
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
                $interactions = $db->fetch("SELECT * FROM people_interactions WHERE target_id='".$person[0]['twitter_id']."'");                
                
            }
        ?>
        
        <div class=' listing_row row'>
            <div class='row_container  '>
                <div class='row_height '>
                    <div class='span3'>
                    
                        <h4><a href='/final/single.php?person_id=<?=$person[0]['person_id'] ?>'><?=$person[0]['name_primary'] ?></a></h4>
                        
                        <? if (!empty($person[0]['twitter_image'])) { 
                            $picture = 'done';
                        ?>
                        <p><img src='<?=$person[0]['twitter_image'] ?>' /></p>
                            
                        <? } else {$picture = '';} ?>
                        
                        
                        <p>Followers in network: <?= count($followers) ?></p>
                        <p style='font-size:10px'>(Think tankers, Journalists &amp; MPs)</p>
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
            
                    <div class='span5'>
                            
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
                                   width: 500,
                                   height: 300,
                                   title: 'Followers by grouping',
                                   colors: [<?= implode(',', $colors_array); ?>]
                                 };


                                 var chart = new google.visualization.PieChart(document.getElementById('chart_div_<?=$person[0]['person_id'] ?>'));
                                 chart.draw(data, options);
                               }
                             </script>
                             <div id="chart_div_<?=$person[0]['person_id'] ?>" style="width: 500px; height: 300px;"></div>
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



                                   
                    <div class='span4 mentions'>
                    
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
