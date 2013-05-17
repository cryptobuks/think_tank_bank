<?
    include('fragments/header.php');
    include('twitter_scripts/functions/twitter_id_to_name.php');
    include('twitter_scripts/twitter_connect.php');

    $edit_query = "SELECT * FROM aliens";

    $people = $db->fetch($edit_query);

    echo "<ol>";
    foreach($people as $person) {
        $name                  = addslashes($person['name']);
        $organisation_type     = addslashes($person['organisation']);
        $twitter_id            = addslashes($person['twitter_id']);
        $twitter_image         = addslashes($person['image_url']);
        
        $query          = "INSERT INTO people (name_primary, organisation_type, twitter_id, twitter_image) VALUES ('$name','$organisation_type', '$twitter_id', '$twitter_image')";
         echo $query . "<br/>";
        $db->query($query);
        
     }
    echo "</ol>";

?>
