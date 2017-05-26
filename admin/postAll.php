<?php
include('adminCode.php'); 

if($_POST[delete])
{
	if(sizeof($_POST[id]) > 0)
	foreach($_POST[id] as $deleteThis)
	{	
		$del = 'delete from posts where id="'.$deleteThis.'"';
		$res = mysql_query($del, $conn);
	}
}

$sel = 'select *, date_format(postedOn, "%m/%d/%Y %h:%i %p") as posted, p.id as id, u.id as uid from posts p left join users u 
on p.postedBy = u.id order by postedOn desc'; 
$res = mysql_query($sel, $conn) or die(mysql_error());

while($p = mysql_fetch_assoc($res))
{
	$selC = 'select count(*) from comments where replyID="'.$p[id].'"';
	$resC = mysql_query($selC, $conn) or die(mysql_error());

	$c = mysql_fetch_assoc($resC);
	
	$subject = stripslashes($p[subject]); 
	

	$theList .= '<tr valign="top">
	<td><a href="postNew.php?id='.$p[id].'">'.$p[id].'</a></td>
	<td><a href="postNew.php?id='.$p[id].'">'.shortenText($subject, 30).'</a></td><td>'.$p[posted].'</td>
	<td><a href="comments.php?id='.$p[id].'" title="'.$c['count(*)'].'">'.$c['count(*)'].'</a></td>
	<td><a href="updateProfile.php?id='.$p[uid].'">'.$p[username].'</a></td>
	<td><input type=checkbox name=id[] value="'.$p[id].'"> </td>
	</tr>';
}


?>



<form method=post>
<table class=moduleBlue cellspacing=0 cellpadding=2><tr><th>Post ID </th><th>Subject</th><th>Date & Time</th><th>Comments</th>
<th>Author</th><th>Delete</th></tr>
<?=$theList?>
<tr><td></td><td><input type=submit name=delete value="Delete Post" onclick="return confirm('** Deletions are irreversible. Are you sure you want to proceed? **');"></td></tr>
</table>
</form>

<p>&nbsp;</p>

<div class=moduleBlue><h1><a href="help.php#post">Click here for help</a></h1>
<div>This is a list of all posts made</div></div>
