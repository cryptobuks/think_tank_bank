<?

include('../header.php'); 

if(isset($_GET['tag'])){
    
    $tag = addslashes($_GET['tag']); 
    $sql="INSERT INTO tags (tag_text) VALUES ('$tag')";
    $db->query($sql);
}

echo "<h1>Tags</h1>"; 
echo "<ul>";

$tags = $db->get_tags();

foreach($tags as $tag) {
    echo "<li>" . $tag['tag_text'] . "</li>";
} 

echo "</ul>";
 
?>

<form action="index.php" method="get">
New Tag: <input type="text" name="tag" />
<input type="submit" value='Save tag' />
</form>
 
<br/>
<br/>

<? include('../footer.php');  ?>