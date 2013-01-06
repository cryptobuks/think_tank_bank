<?
    include('../ini.php');
    @$url = explode("/",$_GET['url']);
    $db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bootstrap, from Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
    body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
    }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

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
          <a class="brand" href="#">Project name</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
        <h1>People</h1>
        
        <?
            $rank_query = "SELECT * FROM people_rank ORDER BY rank DESC LIMIT 100";
            $ranks = $db->fetch($rank_query);
            
            foreach($ranks as $rank) { 
            $person = $db->fetch("SELECT * FROM people WHERE person_id='".$rank['person_id']."'");
            
            if (!empty($person[0]['twitter_id'])) {
                $query ="SELECT * FROM people_followees WHERE followee_id='".$person[0]['twitter_id']."'";
                $followers = $db->fetch($query);
                
                $query ="SELECT * FROM people_followees WHERE follower_id='".$person[0]['twitter_id']."'";
                $follows = $db->fetch($query);                
                
                $publications = $db->fetch("SELECT * FROM people_publications WHERE person_id='".$rank['person_id']."'");  
                $interactions = $db->fetch("SELECT * FROM people_interactions WHERE target_id='".$person[0]['twitter_id']."'");                
                
            }
        ?>
        
        <div class='row'>
            <div class='row_container'>
                <div class='span2'>
                    <h2>Score: <?= $rank['rank'] ?></h2>
                    <h4><?=$person[0]['name_primary'] ?></h4>
                    <?
                        $jobs = $db->fetch("SELECT * FROM people_thinktank WHERE person_id = '".$rank['person_id']."'"); 
                        foreach($jobs as $job) { ?>
                            <?
                                $thinktank = $db->fetch("SELECT * FROM thinktanks WHERE thinktank_id = '".$job['thinktank_id']."'"); 
                            
                        
                            ?>    
                            
                            <p><strong><?=$thinktank[0]['name'] ?></strong></P>    
                            <p><?=$job['role'] ?></P>
                            <hr/>
                        <? } ?>
                </div>  
            
                <div class='span2'>
                    <h2>Followers</h2>
                    <?  
                        foreach ($followers as $follower) { 
                        
                            if ($follower['network_inclusion'] == 2) { 
                                $query = "SELECT * FROM people WHERE twitter_id ='".$follower['follower_id'] ."'";
                                $list_info = $db->fetch($query);                             
                                echo "<p><strong>" . $list_info[0]['name_primary']. "</strong></p>";
                            }
                        
                            if ($follower['network_inclusion'] == 1) { 
                                $query = "SELECT * FROM alien_cache WHERE twitter_id ='".$follower['follower_id'] . "'";
                                //echo $query;
                                $list_info = $db->fetch($query); 
                                echo "<p><strong>" . $list_info[0]['name']. " (Twitter network)</strong></p>";
                                //echo "<p>" . $list_info[0]['description']. "</p>";
                            }                         
                        } 
                    ?>
                
                </div>
                <div class='span2'>
                    <h2>Follows</h2>
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

                <div class='span3'>
                    <h2>Publications</h2>
                    <?
                        foreach($publications as $publication) { 
                            $publicaiton_info = $db->fetch("SELECT * FROM publications WHERE publication_id='".$publication['publication_id']."'");
                            echo "<p><a href='" . $publicaiton_info[0]['url'] ."'>" . $publicaiton_info[0]['title'] . "</a></p>";
                        }
                
                    ?>
                
                </div>
                                   
                <div class='span3'>
                    <h2>Mentions</h2>
                    <?
                        foreach($interactions as $interaction) { 
                            $person_query = "SELECT * FROM people WHERE twitter_id = '".$interaction['originator_id']."'";
                            $person = $db->fetch($person_query); 
                            echo "<p><strong> ".$person[0]['name_primary']."</strong>". $interaction['text']. "</P>";
                        } 
                
                    ?>
                </div>                
            <div>
        </div>
        
        <? } ?>
        
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.8.0.min.js"><\/script>')</script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>

  </body>
</html>
