<?
include('adminCode.php'); 

$id = $_GET[id]; 

if($_POST[del]) 
{
	$del = 'delete from comments where id="'.$_POST[commentID].'"';
	$res = mysql_query($del, $conn) or die(mysql_error());

	$msg = 'Successfully deleted comment.'; 
}

if($msg)
	echo '<font color=red>'.$msg.'</font>'; 


$selP = 'select subject from posts where id="'.$id.'"';
$resP = mysql_query($selP) or die(mysql_error()); 

$p = mysql_fetch_assoc($resP);

echo '<div class="moduleBlue"><h1>All Comments for this Post<br>'.$p[subject].'</h1><div>';

$selC = 'select * from comments where replyID="'.$id.'"';
$resC = mysql_query($selC) or die(mysql_error()); 

while($c = mysql_fetch_assoc($resC))
{
	echo '<div class="border">'.shortenText($c[post], 100).'</div>
	Written by <a>'.$c[postedBy].'</a>
	<form method=post><input type=submit name=del value="Delete Comment" onclick="return confirm(\'Are you sure?\')">
	<input type=hidden name=commentID value="'.$c[id].'">
	</form>
	<p>&nbsp;</p>';
}
?>
</div></div>