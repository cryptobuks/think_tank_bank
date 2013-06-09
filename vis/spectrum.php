<?
    include('../fragments/header.php');

    @$url = explode("/",$_GET['url']);
    $db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);
    
    $query = "SELECT * FROM thinktanks"; 
    $results = $db->fetch($query);
    
    $spectrum_data = array();
    
    foreach($results as $thinktank) { 
        $thinktank_name = addslashes($thinktank['name']);
        
        $followers_query = "SELECT *,
        COUNT(DISTINCT follower_person.person_id) as counter,
        follower_person.thinktank_name AS follower_person_thinktank_name,
        follower_person.person_id AS follower_person_person_id
        FROM `people_followees`
        JOIN people AS follower_person ON people_followees.follower_id = follower_person.twitter_id
        JOIN people AS followee_person ON people_followees.followee_id = followee_person.twitter_id
        WHERE followee_person.thinktank_name = '$thinktank_name' 
        && (follower_person.thinktank_name='Con')
       
        GROUP BY follower_person_thinktank_name
        ORDER BY counter DESC
        LIMIT 10";
        
        $followers_con = $db->fetch($followers_query);
        
        $followers_query = "SELECT *,
        COUNT(DISTINCT follower_person.person_id) as counter,
        follower_person.thinktank_name AS follower_person_thinktank_name,
        follower_person.person_id AS follower_person_person_id
        FROM `people_followees`
        JOIN people AS follower_person ON people_followees.follower_id = follower_person.twitter_id
        JOIN people AS followee_person ON people_followees.followee_id = followee_person.twitter_id
        WHERE followee_person.thinktank_name = '$thinktank_name'
        && (follower_person.thinktank_name='Lab')
        GROUP BY follower_person_thinktank_name
        ORDER BY counter DESC
        LIMIT 10";
        
        $followers_lab = $db->fetch($followers_query);
        
        
        
        $followers_array = array('lab'=> $followers_lab, 'con'=> $followers_con);
        
        if(!empty($followers_array['lab'][0]) && !empty($followers_array['con'][0])) { 
            $spectrum_data[]= $followers_array;
           
        }
        
    
        
      
       
        
    }
    
  
    
    $json_data = json_encode($spectrum_data);
    
    
?>
 
    
    
<div class='row'>
    <div class='span12'>
        <div id='spectrum'>
        </div>
    </div>
</div> 

<?  include('../fragments/footer.php'); ?>
        

<script>
    json = <?=$json_data?>; 
    
    $(document).ready(function(){
        $.each(json, function(key,value){ 
            console.log(value);
            var lab = parseInt(value['lab'][0].counter);
            var con = parseInt(value['con'][0].counter);
            var total = con + lab;
            var con_frac = con/total;
            var lab_frac = lab/total;
            var pos = ((con - lab) * 20) + 50;
            var vert = (key*30)+20;
            $('#spectrum').append('<div class="item" style="position:absolute; border:1px solid black; top:'+(( vert))+'px; margin-left:800px; width:200px; left:'+ pos+'px">' + value['con'][0].thinktank_name + "("+lab+" /"+con+" )</div>")
        });
    });
    
    
</script>