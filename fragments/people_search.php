<?

include_once( __DIR__ . '/../ini.php');

@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$name = addslashes(urldecode($_GET['name']));

$people_query = "SELECT *
    FROM `people`
    WHERE role!='' && role!='report_author_only' && role!='official twitter acc'
    && name_primary LIKE '%$name%'
    LIMIT 20";
    

$people = $db->fetch($people_query);
echo "<h3>Person search: '". stripslashes($name) ."'</h3>";
echo "<ul>";
foreach($people as $person) {

    
        echo "<li class='tweet_listing'>
            <img class='twitter_image' src='" . $person['twitter_image'] ."'/>
            <a data-id='" . $person['person_id'] ."' class='person_link'><strong>".$person['name_primary']. "</strong></a>
            (<a class='thinktank_link' data-thinktank-name='".$person['thinktank_name']."'><strong>". $person['thinktank_name'] ."</strong></a>)
            
             </li>\n";
    
}

echo "</ul> <br class='clearfix' />";



?>