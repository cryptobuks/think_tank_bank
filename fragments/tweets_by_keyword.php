<?

include('../twitter_scripts/functions/twitter_id_to_name.php');
include('../twitter_scripts/twitter_connect.php');
 

if(!empty($_GET['query_string'])) {
    
    $query_string = mysql_real_escape_string(urldecode($_GET['query_string']));

    $old = time() - (60 * 60 * 24 * 2);
    $new = time() - (60 * 60 * 24 * 0);

    $query= "SELECT * FROM tweets
        JOIN people ON people.twitter_id = tweets.user_id
        
        JOIN people_thinktank ON people_thinktank.person_id = people.person_id
        JOIN thinktanks ON thinktanks.thinktank_id = people_thinktank.thinktank_id
        WHERE 
        text LIKE '%$query_string%'
        ORDER BY time DESC LIMIT 10";
    $result = array();
    $result['query'] = $query;
    $result['results'] = $db->fetch($query);
    $result['query_string'] = $query_string;
}

else { 
    $result = array('ERROR'=>'no query string');
}    

output_json($result); 

?>