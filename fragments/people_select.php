<?

$old = time() - (60 * 60 * 24 * 2);
$new = time() - (60 * 60 * 24 * 0);

$people_query = "SELECT *, tweets.rts - (people.ave_rts) - (people.ave_tweets * 0.5) as rate
    FROM `tweets`
    JOIN people ON people.twitter_id = tweets.user_id
    WHERE  time > $old && time < $new
    && role!='' && role!='report_author_only' && role!='official twitter acc' && is_rt=0 && tweets.rts >= people.ave_rts
    ORDER BY rate DESC
    LIMIT 20";

$people = $db->fetch($people_query);

$DEFAULT_ID = $people[0]['person_id'];

$excluded_people = array('not a numnber'); //prevent it from returning 0 for first array element

echo "<ul>";
foreach($people as $person) {

    if (!array_search($person['user_id'], $excluded_people)) {
        echo "<li class='tweet_listing'>
            <img class='twitter_image' src='" . $person['twitter_image'] ."'/>
            <a data-id='" . $person['person_id'] ."' class='person_link'><strong>".$person['name_primary']. "</strong></a>
            (<a class='thinktank_link' data-thinktank-name='".$person['thinktank_name']."'><strong>". $person['thinktank_name'] ."</strong></a>)
            <p>" . $person['text'] . "</p>
            <p><i class='icon-refresh'></i><strong>" . $person['rts'] . "</strong> &nbsp; ".$person['name_primary']. " averages ".$person['ave_rts']. " RTs, </p>
             </li>\n";
    }
    
    $excluded_people[] = $person['user_id'];
}

echo "</ul>";

?>