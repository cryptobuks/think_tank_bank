<?
    include('../ini.php');
    @$url = explode("/",$_GET['url']);
    $db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

    $page_no = 0;
    
    $person_id = $_GET['person_id']; 
        
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
          <a class="brand" href="/people/">Think tanks</a>
      
          
          <div class="nav-collapse collapse">
           
            <ul class="nav">
            
              <li class="active"><a href="/people/">Home</a></li>

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
                $query ="SELECT * FROM people_followees WHERE followee_id='" . $person[0]['twitter_id'] . " ' && network_inclusion >0 ORDER BY id ASC ";
                
                $followers = $db->fetch($query);
                //print_r($followers);
                //$query ="SELECT * FROM people_followees WHERE follower_id='".$person[0]['twitter_id']."'";
                //$follows = $db->fetch($query);                
                
                $publications = $db->fetch("SELECT * FROM people_publications WHERE person_id='".$person[0]['person_id']."'");  
                $interactions = $db->fetch("SELECT * FROM people_interactions WHERE target_id='".$person[0]['twitter_id']."'");                
               
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
                            foreach ($followers as $follower) { 
                        
                                if ($follower['network_inclusion'] == 2) { 
                                    $query = "SELECT * FROM people WHERE twitter_id ='".$follower['follower_id'] ."'";
                                    $list_info = $db->fetch($query);                             
                                    
                                    $twitter_follower_number = $db->fetch("SELECT * FROM people_twitter_rank WHERE person_id ='".$person[0]['person_id']     ."' ORDER BY date DESC LIMIT 1");
                                    $temp_array['follower_numbers'] = $twitter_follower_number[0]['twitter_followers'];
                                    $temp_array['name'] = $list_info[0]['name_primary'];
                                    $temp_array['network_inclusion'] = $follower['network_inclusion'];
                                    $sorted_array[] = $temp_array;
                                }
                        
                                if ($follower['network_inclusion'] == 4) { 
                                    $query = "SELECT * FROM alien_cache WHERE twitter_id ='".$follower['follower_id'] . "'";
                                    //echo $query;
                                    $list_info = $db->fetch($query); 
                                    
                                    $temp_array['follower_numbers'] = $list_info[0]['followers_count'];
                                    $temp_array['name'] = $list_info[0]['name'];
                                    $temp_array['network_inclusion'] = $follower['network_inclusion'];
                                    $sorted_array[] = $temp_array;
                                }                         
                            }
                            
                            function cmp_by_followerNumber($a, $b) {
                              return $b["follower_numbers"] - $a["follower_numbers"];
                            }

                            usort($sorted_array, "cmp_by_followerNumber");
                            
                            foreach($sorted_array as $sorted) {                            
                                 
                                if ($sorted['network_inclusion'] == 4) {
                                    echo "<p><strong>" . $sorted['name']. " ( ". $sorted['follower_numbers'] . " )</strong></p>";
                                }
                                else { 
                                    echo "<p><strong>" . $sorted['name']. " ( ". $sorted['follower_numbers'] . ")</strong></p>";
                                }
                            }
                        ?>
                
                    </div>
                    <!--
                    <div class='span2'>
                    
                        <?  
                            foreach ($follows as $follow) { 
                        
                                if ($follow['network_inclusion'] == 2) { 
                                    $query = "SELECT * FROM people WHERE twitter_id ='".$follow['followee_id'] ."'";
                                    $list_info = $db->fetch($query);                             
                                    echo "<p><strong>" . $list_info[0]['name_primary']. "</strong></p>";
                                }
                        
                                if ($follow['network_inclusion'] == 1) { 
                                    $query = "SELECT * FROM alien_cache WHERE twitter_id ='".$follow['followee_id'] . "'";
                                    //echo $query;
                                    $list_info = $db->fetch($query); 
                                    echo "<p><strong>" . $list_info[0]['name']. " </strong></p>";
                                    //echo "<p>" . $list_info[0]['description']. "</p>";
                           
                                }                         
                            } 
                        ?>
                
                    </div>   
                    -->

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
                                    $alien_query = "SELECT * FROM alien_cache WHERE twitter_id = '".$interaction['originator_id']."'";
                                    
                                    $alien_result = $db->fetch($alien_query);
                                    echo "<p><strong> ".$alien_result[0]['name']."</strong> ". $interaction['text']. "</p>";
                                }
                                else { 
                                    echo "<p><strong> ".$person[0]['name_primary']."</strong> ". $interaction['text']. "</p>";
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
