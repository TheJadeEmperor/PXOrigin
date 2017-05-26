<?php
include('adminCode.php');

function addEditForm($s)
{
	if($_GET[e])
	{
		$disAdd = 'disabled';
		$statusSel[ $s[status] ] = 'selected';
		
		$statusType = array(
		'S' => 'S = Subscribed',
		'U' => 'U = Unsubscribed');
		
		foreach($statusType as $sta => $dis)
		{
			$statusOpt .= '<option value="'.$sta.'" '.$statusSel[$sta].'>'.$dis.'</option>';
		}
	}
	else
	{
		$disDel = $disEdit = 'disabled'; 
	}

	$properties = ' type=text class=input size=30';
	
	return '<form name=subscriber method=post>
	<table class=thelist cellspacing=0><tr><th colspan=3>Add / Edit / Subscriber</th></tr>
	<tr>
		<td>email</td><td><input '.$properties.' name=email value="'.$s[email].'"></td>
	</tr><tr>
		<td>joined</td><td><input '.$properties.' name=joined value="'.$s[joined].'"></td>
	</tr><tr>
		<td>origin</td><td><input '.$properties.' name=origin value="'.$s[origin].'"></td>
	</tr><tr>
		<td>status</td><td><select name=status>'.$statusOpt.'</select></td>
	</tr><tr>
		<td colspan=2 align=center><input type=submit name=add value=" Add Subscriber " '.$disAdd.'> 
		<input type=submit name=edit value=" Edit Subscriber " '.$disEdit.'><br><form method=post>
		<input type=submit name="delete" value="Delete Subscriber" onclick="return confirm(\'Are you sure? Deletions are irreversible!\')" '.$disDel.'>
		</td>
	</tr>
	</table></form>';

}



$tableName = 'users';
$perPage = 100; //default subscribers per page

if($_POST[perPage])
{
	$perPage = $_POST[perPage];
}
else
{
	$perPage = 100;
}

//set the current page (default is 1)
if($_GET[p])
	$_SESSION[p] = $_GET[p];
else
	$_SESSION[p] = 1;


/*
$dbFields = array(
'email'	=> '"'.$_POST[email].'"', 
'joined' => '"'.$_POST[joined].'"',
'origin' => '"'.$_POST[origin].'"',
'status' => '"'.$_POST[status].'"');


if($_POST[add])
{
	if(insertRecord($dbFields, $tableName))
		$msg = 'Successfully added subscriber.';  
	else
		die(mysql_error());
}
else if($_POST[edit])
{
	$set = array();
	foreach($dbFields as $fld => $val)
	{
		array_push($set, $fld.'='.$val);
	}
	
	$theSet = implode(',', $set);
	
	$upd = 'update '.$tableName.' set '.$theSet.' where email="'.$_GET[e].'"';
	if(mysql_query($upd, $conn))
		$msg = 'Successfully updated subscriber with query: '.$upd;
	else
		die(mysql_error());
}
else if($_POST[delete])
{
	$del = 'delete from subscribers where email="'.$_POST[email].'" limit 1';
	if(mysql_query($del, $conn))
		$msg = 'Successfully deleted subscriber with query "'.$del.'"';
	else
		die(mysql_error());
}
*/


$thisPage = $_SESSION[p];	


$total = 1; //total # of subscribers
$listCount = 1; //# of pages

$selS = 'select * from '.$tableName.' order by joinDate, email';
$resS = mysql_query($selS, $conn) or die(mysql_error());

while($m = mysql_fetch_assoc($resS))
{
	if($_GET[e] == $m[email]) //editing this current record
		$s = $m;
	
	if($total % $perPage == 0) 
	{
		$listCount++;
	}

	$subscribers[$listCount] .= '<tr><td><a href="updateProfile.php?id='.$m[id].'">'.$m[id].'</a> - <a href="updateProfile.php?id='.$m[id].'">'.$m[email].'</a> </td>
	<td align=center>'.$m[origin].'</td><td>'.$m[joinDate].'</td></tr>';
	
	$total++;
}


//calculate page numbers
$numPages = ceil($total / 100);



for($p = 1; $p <= $numPages; $p++)
{
	if($thisPage == $p)
		$page .= '<font size=3><a href="?p='.$p.'">'.$p.'</a></font> ';
	else
		$page .= '<a href="?p='.$p.'">'.$p.'</a> ';
}


//calculate prev page
if($thisPage == 1)
	$prev = 1;
else
	$prev = $thisPage - 1;

	
//calculate next page
if($thisPage == $numPages)
	$next = $numPages;
else
	$next = $thisPage + 1;
	
	
$prevPage = '<a href="?p='.$prev.'"><< Prev</a>';
$nextPage = '<a href="?p='.$next.'">Next >></a>';	

$pageNav = '<tr><td colspan=4 align=center>'.$prevPage.' '.$page.' '.$nextPage.'</td></tr>';

//echo $_SESSION[p];

//subscriber options
$perPage = array(
'50', '100', '150', '200');

foreach($perPage as $pp)
{
	$sel = '';
	if($_POST[perPage] == $pp)
		$sel = 'selected';
	$ppOpt .= '<option value="'.$pp.'" '.$sel.'>'.$pp.'</option>';
}

$subOpt = '<form method=POST><div class=thelist><h2>Subscriber Options</h2>
Subscribers Per Page <select name="perPage" onchange=submit();>'.$ppOpt.'</select>
</div></form>';

if($msg)
	echo '<fieldset>'.$msg.'</fieldset>';

?>	
<table>
<tr valign=top>
	<td>
	<table class=thelist cellspacing=0 cellpading=0><tr><th colspan=4>Subscribers List - Total: <?=$total?></th> 
	<?=$pageNav ?>
<tr><td>
	<table class=thelist cellspacing=0 cellpading=0>
	<tr>
		<th>Email</th><th>Origin</th><th>Joined</th>
	</tr>
		<?=$subscribers[$thisPage]?>
	</table>
</td>
</tr>
	<?=$pageNav?></table></td>
	<td><?=$subOpt?><br></td>
</tr>
</table>