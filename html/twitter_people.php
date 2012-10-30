<? include('header.php'); ?>

<h1>Collect Twitter Activity</h1>

<?

$people = $db->fetch("SELECT * FROM people WHERE twitter_handle !=''");

foreach($people as $person) {
    echo "<h3>".$person['twitter_handle']."<h3>";
    $data = file_get_contents("https://api.twitter.com/1/users/show.json?screen_name=".$person['twitter_handle'] );
    $data = json_decode($data, true);
    echo "<p>" . $data['followers_count'] . "<p>";
    $date       = time();
    $person_id  = $person['person_id'];
    $twitter_followers = $data['followers_count'];
    $db->query("INSERT INTO people_twitter_rank (person_id, twitter_followers, date) VALUES ('$person_id', '$twitter_followers', '$date')"); 
}




?>



<? include('footer.php'); ?>