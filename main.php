<?php
if(isset($_SESSION[login])) {
	echo '<h1>Welcome Back, '.$u[fname].'</h1>';
}


$selC = 'select count(*) as count from posts';
$resC = mysql_query($selC, $conn) or die(mysql_error()); 

$c = mysql_fetch_assoc($resC); 

$totalPosts = $c[count];
$perPage = 10; 

$numPages = ceil($totalPosts / $perPage);  

$lastPost = $_GET[page] * $perPage; 
$firstPost = $lastPost - ($perPage - 1);

if($firstPost < 0)
    $firstPost = 0; 


echo '<p>&nbsp;</p>
<h1>Latest Posts from PXO</h1>';

//show the latest 10 active posts
$sel = 'select *, date_format(postedOn, "%m/%d/%Y %h:%i %p") as published, p.id as id, u.id as uid 
from posts p left join users u on p.postedBy = u.id where (p.status<>"I" or p.status is null) 
order by postedOn desc limit '.$firstPost.', 10';

$res = mysql_query($sel, $conn) or die(mysql_error());

while($p = mysql_fetch_assoc($res))
{
	$id = $p[id]; 
	$subject = stripslashes($p[subject]); 
	$published = $p[published]; 
	$url = $p[url];
    
    if($url)
        $postLink = './?post='.$url;
    else   
        $postLink = './?post='.$id;
    
	echo '<table>
	<tr valign="top">
	<td>
		<a href="'.$postLink.'"><img src="posts/'.$id.'/1.jpg" alt="'.$subject.'" title="'.$subject.'" 
		width="225px" height="140px"></a>
	</td><td width=10px></td>
	<td>
		<a href="'.$postLink.'"><h3>'.$subject.'</h3></a><br>By <a href="?action=viewProfile&id='.$p[uid].'">'.$p[username].'</a>
		<br><br>Published on '.$published.'
	</td>
	</tr>
	</table><br><div class="separator"></div><br>';  
}

echo '<div class="pagination">
<table><tr><td>
<ul><li><a>Page >></a></li>';
for($n = 1; $n <= $numPages; $n++) {
    echo '<li><a href="./?action=main&page='.$n.'">'.$n.'</a></li>';
}
echo '</ul>
</td></tr></table>';

?>