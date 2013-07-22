<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    $thinktank_id = @$_GET['id']; 
?>
    

<div class='row-fluid'>

    <div class='span6 thinktank_listing' >
        
        <ul>
        <?  
            if (empty($thinktank_id)) { 
                $query = "SELECT * from thinktanks ORDER BY name"; 
                $thinktanks = $db->fetch($query);
                foreach($thinktanks as $thinktank) { 
                    echo "<li><a href='thinktanks.php?id=" . $thinktank['thinktank_id'] . "'> " . $thinktank['name'] . "</a></li>";
                }
            } else {
                $query = "SELECT * from thinktanks WHERE thinktank_id = ". $thinktank_id; 
                
                $thinktanks = $db->fetch($query);
                
                echo "<h1>" . $thinktanks[0]['name'] . "</h1>";
                
                $people_query = "SELECT * FROM people WHERE thinktank_name ='" . $thinktanks[0]['name'] . "' && role!='report_author_only§' ";
               
                $people = $db->fetch($people_query);
                
                echo "<ul>"; 
                
                foreach($people as $person) {
                    echo "<li><img class='tt_image' src='". $person['twitter_image'] . "' />" . $person['name_primary'] . " -- " . $person['role'] . "</li><br/>";
                }
                
                echo "</ul>"; 
            }
        ?>
        </ul>
    </div>    
    

    <div class='span6' id='thinktank_inspector'>
    
    </div>
    
</div> 

<?  include('fragments/footer.php'); ?>
        

