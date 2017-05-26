<?php
include('adminCode.php');

if($_POST[add])
{
	$dbFields = array(
	'url' => '"'.$_POST[url].'"',
	'file' => '"'.$_POST[file].'"',
	'header' => '"'.$_POST[header].'"',
	'footer' => '"'.$_POST[footer].'"');
	
	$fields = $values = array();
	
	foreach($dbFields as $fld => $val)
	{
		array_push($fields, $fld);
		array_push($values, $val);
		
		if($_POST[$fld] == '')
			$error .= 'Fill in the required fields<br>';
	}
	
	$theFields = implode(',', $fields);
	$theValues = implode(',', $values);
	
	$selM = 'select url from memberspages where url="'.$_POST[url].'"';
	$resM = mysql_query($selM, $conn);
	
	if(mysql_num_rows($resM) > 0)
		$error .= 'There is already a page with that url<br>';
	
	if(!$error)
	{
		$ins = 'insert into memberspages ('.$theFields.') values ('.$theValues.')';
		mysql_query($ins, $conn) or die(mysql_error()); 
		
		$error = '<b>Member page successfully added</b>';
	}
}
else if($_POST[edit])
{
	$dbFields = array(
	'url' => '"'.$_POST[url].'"',
	'file' => '"'.$_POST[file].'"',
	'header' => '"'.$_POST[header].'"',
	'footer' => '"'.$_POST[footer].'"');
	
	$set = array();
	
	foreach($dbFields as $fld => $val)
	{
		array_push($set, $fld.'='.$val);
	}
	
	$theSet = implode(',', $set);
	
	$upd = 'update memberspages set '.$theSet.' where url="'.$_GET[url].'"';
	mysql_query($upd, $conn) or die(mysql_error()); 
	
	$error = '<b>Member page successfully updated</b>';
}
else if($_POST[clear])
{
	unset($_GET);
	unset($_POST);
	 
}


$error = '<font color=red>'.$error.'</font>';


$sel = 'select * from memberspages order by url';
$res = mysql_query($sel, $conn) or die(mysql_error());

while($m = mysql_fetch_assoc($res))
{
	if($_GET[url] == $m[url]) //update this one
	{
		$p = $m; 
	}

	
	$list .= '<tr><td><a href="?url='.$m[url].'">'.$m[url].'</a></td><td>'.$m[file].'</td>
	<td>'.$m[header].'</td><td>'.$m[footer].'</tr>';
}

if($_GET[url])
	$disAdd = 'disabled';
else
	$disEdit = 'disabled';


?>

<?=$error?>
<form method=post>
<table class=thelist>
<tr>
	<th colspan=2 align=center>Add / Update Member Page</th>
</tr><tr>
	<td>URL</td><td><input type=text name=url value="<?=$p[url]?>" size="30" class="activeField"> url </td>
</tr><tr>
	<td>Source File</td><td><input type=text name=file value="<?=$p[file]?>" size="30" class="activeField"> file</td>
</tr><tr>
	<td>Header File</td><td><input type=text name=header value="<?=$p[header]?>" size="30" class="activeField"> header</td>
</tr><tr>
	<td>Footer File</td><td><input type=text name=footer value="<?=$p[footer]?>" size="30" class="activeField"> footer</td>
</tr><tr>
	<td align=center colspan=2><input <?=$disAdd?> type=submit name=add value="Add Page">
		<input <?=$disEdit?> type=submit name=edit value="Edit Page"> 
		<a href="memberPages.php"><input type="button" value="Clear Form"></a></td>
</table>
</form>

<p>&nbsp;</p>

<table class="thelist" cellspacing=0 cellpadding=10>
<tr><th>URL</th><th>Source File</th><th>Header File</th><th>Footer File</th>
<?=$list?>
</table>