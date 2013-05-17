<?  

    //this script takes people who work at think tanks 
    //and adds the name of the thinktank to the person record
    //this avoids three different joins further down the line... 
    
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    include('twitter_scripts/twitter_connect.php');

    $edit_query ="SELECT * FROM people 
        JOIN people_thinktank ON people_thinktank.person_id = people.person_id 
        JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id";

    $people = $db->fetch($edit_query);

    echo "<ol>";
    foreach($people as $person) {
        
        print_r($person);
        $id         = addslashes($person['person_id']);
        $thinktank  = addslashes($person['name']);
        $role  = addslashes($person['role']);
        
        //$twitter_image    = addslashes($person['image_url']);
        
        $query          = "UPDATE people SET organisation_type='thinktank', role='$role', thinktank_name='$thinktank' WHERE person_id='$id'";
        echo $query . "<br/>";
        $db->query($query);
        
     }
    echo "</ol>";

?>
