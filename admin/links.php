<?php
include('adminCode.php');
$tableName = 'links';

function isLegalName($string) //check for illegal characters in a field
{
	$illegal = array('#', '$', '?', '&', '!', ' ');
	
	foreach($illegal as $check)
	{
		if(is_int(strpos($string, $check)))
		{
			return false;
		}
	}
	
	return true;
}

$dbFields = array(
'name' => '"'.$_POST[name].'"',
'url' => '"'.$_POST[url].'"',
'title' => '"'.$_POST[title].'"',
'img' => '"'.$_POST[img].'"',
);

if($_POST[add])
{
	if(isLegalName($_POST[name]))
	{
		if(insertRecord($dbFields, $tableName))
			$msg = 'Record successfully added.'; 
		else
			$msg = mysql_error();
	}
	else
		$msg = 'Illegal characters in name';
}
else if($_POST[edit])
{
	if(isLegalName($_POST[name]))
	{
	 	$set = array();
		foreach($dbFields as $fld => $val)
		{
			array_push($set, $fld.'='.$val);
		}
	
		$theSet = implode(',', $set);
		
		$upd = 'update '.$tableName.' set '.$theSet.' where name="'.$_GET[name].'"';
		$res = mysql_query($upd, $conn) or die(mysql_error());
		$msg = $upd;
	}
	else
		$msg = 'Illegal characters in name';
}
else if($_POST[clear])
{
	unset($_GET);
}


$getL = 'select * from links order by name';
$resL = mysql_query($getL, $conn) or die(mysql_error().__LINE__);

while($l = mysql_fetch_assoc($resL))
{
	if($_GET[name] == $l[name])
		$rec = $l;
	
	$theList .= '<tr title="'.$l[title].'"><td align="left"><a href="?name='.$l[name].'">'.$l[name].'</a><br>
	<a href="'.$l[url].'">'.$l[url].'</a><br><a href="'.$l[img].'">'.$l[img].'</a></td></tr>';
}

if($_GET[name])
{
	$disAdd = 'disabled';
}
else
{
	$disEdit = 'disabled';
}

$properties = 'type="text" class="input"';

$addEditForm = '<table class=thelist cellspacing=0><tr><th>Add / Edit Link</th></tr>
<tr><td>
	<form method=POST>
	<table>
	<tr title="Name of this link">
		<td>name:</td><td><input '.$properties.' name="name" value="'.$rec[name].'"></td>
	</tr><tr title="website url">
		<td>link:</td><td><input '.$properties.' name="url" value="'.$rec[url].'" size="40"></td>
	</tr><tr title="image url">
		<td>img:</td><td><input '.$properties.' name="img" value="'.$rec[img].'" size="40"></td>
	</tr><tr title="Title text">
		<td>title:</td><td><input '.$properties.' name="title" value="'.$rec[title].'"></td>
	</tr><tr>
		<td colspan="2" align="center"><input type="submit" name="add" value="Add" '.$disAdd.'>
		<input type="submit" name="edit" value="Edit" '.$disEdit.'>
		<input type="submit" name="clear" value="Clear"></td>
	</tr>
	</table>
	</form>
</td></tr></table>';


if($msg)
	echo '<fieldset>'.$msg.'</fieldset><br>';
	 

echo '<table>
<tr valign=top>
	<td>'.$addEditForm.'</td>
	<td width="10px"></td><td>
	<table>'.$theList.'</table><br><br>
</td>
</tr>
</table>';   ?>