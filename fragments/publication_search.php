<?

include_once( __DIR__ . '/../ini.php');

@$url = explode("/",$_GET['url']);
$db = new dbClass(DB_LOCATION, DB_USER_NAME, DB_PASSWORD, DB_NAME);

$name = addslashes(urldecode($_GET['name']));

$publications_query = "SELECT *
    FROM `publications`
    JOIN thinktanks on publications.thinktank_id =  thinktanks.thinktank_id
    WHERE title LIKE '%$name%'
    ORDER BY publication_date DESC
    LIMIT 10
    ";
    

$publications = $db->fetch($publications_query);
echo "<h3>Publication search: '". stripslashes($name) ."'</h3>";
echo "<ul>";
foreach($publications as $publication) {
    
        if ($publication['publication_date'] > 0) { 
            $date_string = "(" . date("F j, Y" ,$publication['publication_date']) . ")";
        }
        else { 
            $date_string = '';
        }
        echo "<li class='tweet_listing'>
            <img class='twitter_image' src='" . $publication['image_url'] ."'/>
            <p><a data-id='" . $publication['url'] ."' class='person_link'><strong>".$publication['title']. "</strong></a></p>
            <p><a class='thinktank_link' data-thinktank-name='".$publication['name']."'><strong>". $publication['name'] ."</a></strong> ".$date_string."</a></p>
            
             </li>\n";
    
}

echo "</ul>";

?>