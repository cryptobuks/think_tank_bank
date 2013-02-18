<?
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    include($root. '/ini.php');
    set_include_path('/home/96975/domains/think-tanks.jimmytidey.co.uk/html/');
    include($root . '/twitter_scripts/twitter_connect.php'); 
    include($root . '/twitter_scripts/functions/interactions.php'); 
    include($root . '/twitter_scripts/functions/alien_interactions.php'); 
    include($root . '/twitter_scripts/functions/people_interactions.php'); 
?> 


<h1>Recurring Task</h1>

<?
//fetch and display task list 
$tasks = $db->fetch('SELECT * FROM cron_manage');
foreach($tasks as $task) {
    if ($task['pointer'] == 1) {
        echo "<p><strong>" . $task['task'] . "</strong></p>";
    }
    else { 
        echo "<p>" . $task['task'] . "</p>";
    }
}

//run current task 


foreach($tasks as $task) {
    if ($task['pointer'] == 1) {
        if($task['task'] == 'alien_interactions') { 
            alien_interactions($db, $connect);
        }
    }
}    
        



?>







<? include (ROOT . "/fragments/footer.php"); ?>