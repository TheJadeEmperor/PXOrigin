<?php
$dir = '../';
include($dir.'include/functions.php');

if(isset($_SESSION[login]))
{	
	$sel = 'select * from users where username="'.$_SESSION[login][username].'"
	and id="'.$_SESSION[login][id].'"';
	$res = mysql_query($sel, $conn) or die(mysql_error());

	$u = mysql_fetch_assoc($res);

}
else
{
	header('Location: ../?action=login');
} 


//get url's and pages from memberspages
$selM = 'select * from memberspages order by url';
$resM = mysql_query($selM, $conn) or die(mysql_error());

while($m = mysql_fetch_assoc($resM))
{
	//$menu .= '<a href="?action='.$m[url].'">'.$m[url].'</a>';
	
	if($_GET[action] == $m[url])
	{
		$header = $m[header];
		$mfile = $m[file];
		$footer = $m[footer];
	}
}

//set default templates
if(empty($header))
	$header = '../header.php';
if(empty($footer))
	$footer = '../footer.php';
if(empty($mfile))
	$mfile = 'main.html';

include($header); 
include($mfile);
include($footer);

//add views here



/*
if($_GET[dl]) //download bonuses
{
	header("Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: binary");
	header("Content-Description: File Transfer");

	$fparts = explode("/", $itemLocation);
	$filename = $fparts[count($fparts)-1];
	header("Content-Disposition: attachment; filename=$filename");
	@readfile($itemLocation);

	exit;
}
*/
?>