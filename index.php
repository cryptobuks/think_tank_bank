<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    
?>
    
<script src="/js/main.js"></script>    
    
<div class='row'>
    <div class='span5 module'>
        
        <ul class="nav nav-tabs" id="myTab">
            <li><a data-target='#thinktank'  data-toggle="tab" >Trending Thinktanks</a></li>
            <li><a data-target="#rts"  data-toggle="tab"  >Most Retweets</a></li>
            <li><a data-target="#hashtags"  data-toggle="tab"  >Hashtags</a></li>
            
    
            <!--<li><a data-target="#search"  data-toggle="tab" >Search for people</a></li>-->
        </ul>

        <div class="tab-content">
            
            <div class="tab-pane" id="thinktank">
                <?  include('fragments/thinktank_select.php') ?>
            </div>
            
            <div class="tab-pane " id="rts">
                <?  include('fragments/people_select.php') ?>
            </div>
            
            <div class="tab-pane " id="hashtags">
                <?  include('fragments/hashtags_select.php') ?>
            </div>
            
                        
        </div>
    </div>
    
    <div class='span7' >
        <div id='content_target'>
          
        </div>    
    </div>


    
</div> 

<?  include('fragments/footer.php'); ?>
        

