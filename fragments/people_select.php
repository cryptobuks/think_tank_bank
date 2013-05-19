<?

$old = time() - (60 * 60 * 24 * 1);
$new = time() - (60 * 60 * 24 * 0);

$people_query = "SELECT *, (tweets.rts - (people.ave_rts *3)) as rate
    FROM `tweets`
    JOIN people ON people.twitter_id = tweets.user_id
    WHERE  time > $old && time < $new
    && people.thinktank_name !='' && role!='' && role!='report_author_only' && role!='official twitter acc' && is_rt=0
    ORDER BY rate DESC LIMIT 16";

$people = $db->fetch($people_query);

$DEFAULT_ID = $people[0]['person_id'];


echo "<ul>";
foreach($people as $person) {
    echo "<li class='tweet_listing'>
        <img class='twitter_image' src='" . $person['twitter_image'] ."'/>
        <a data-id='" . $person['person_id'] ."' class='person_link'><strong>".$person['name_primary']. "</strong></a>
        (<a href=''><strong>". $person['thinktank_name'] ."</strong></a>)
        <p>" . $person['text'] . "</p>
        <p><i class='icon-refresh'></i> " . $person['rts'] . " (".$person['name_primary']. " averages ".$person['ave_rts']. " retweets per tweet)</p>
         </li>\n";
}
echo "</ul>";

?>