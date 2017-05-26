<?php
include('adminCode.php');

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

/////////////////////////////
$tableName = 'newsletter';
/////////////////////////////

$dbFields = array(
'category' => '"'.$_POST[category].'"',
'subject' => '"'.$_POST[subject].'"', 
'message' => '"'.addslashes($_POST[message]).'"' );

if($_POST[add])
{
	if($_POST[category] == '' || $_POST[subject] == '')
	{
		$msg = 'Fields cannot be blank.';
	}
	else
	{
		if(!isLegalName($_POST[category]))
		{
			$msg = 'Illegal characters in category field.';
		}
		else
		{
			$fields = $values = array();
			foreach($dbFields as $fld => $val)
			{
				array_push($fields, $fld);
				array_push($values, $val);
			}
			
			$theFields = implode(',', $fields);
			$theValues = implode(',', $values);
			
			$ins = 'insert into '.$tableName.' ('.$theFields.') values ('.$theValues.')';
			$res = mysql_query($ins, $conn) or die(mysql_error());
		}
	}
}
else if($_POST[edit])
{
	if(!isLegalName($_POST[category]))
	{
		$msg = 'Illegal characters in category field.';
	}
	else
	{
		$set = array();
		
		foreach($dbFields as $fld => $val)
		{
			array_push($set, $fld.'='.$val);
		}
		
		$theSet = implode(',', $set);
		
		$upd = 'update '.$tableName.' set '.$theSet.' where category="'.$_GET[cat].'"';
		$res = mysql_query($upd, $conn) or die(mysql_error());
	}
}

if($_GET[cat]) //editing a newsletter
{
	$selN = 'select * from '.$tableName.' where category="'.$_GET[cat].'"';	
	$resN = mysql_query($selN, $conn) or die(mysql_error());
	$n = mysql_fetch_assoc($resN);
	
	if($_POST[dateAdd])
	{
		$dbFields = array(
		'category' => '"'.$_POST[category].'"',
		'dateField' => '"'.$_POST[dateField].'"' );
		
		if(!isLegalName($_POST[category]))
		{
			$msg = 'Illegal characters in category field.';
		}
		else
		{
			if(insertRecord($dbFields, 'days_to_send'))
				$msg = 'Successfully entered date: '.$_POST[dateField];
			else
				$msg = mysql_error();
		}
	}
	else if($_POST[del])
	{	
		$del = 'delete from days_to_send where dateField="'.$_POST[deletethis].'" and category="'.$_GET[cat].'"';
		mysql_query($del, $conn) or die(mysql_error());
	}

	$disAdd = 'disabled';
}
else
{
	$disEdit = 'disabled';
}

$addEditButtons = '<input type=submit name=add value=" Add Newsletter " '.$disAdd.'>
<input type=submit name=edit value=" Edit Newsletter " '.$disEdit.'>
<a href="'.$_SERVER[PHP_SELF].'"><input type=button value=" Clear "></a>';

$calendarSchedule = '<a onclick="javascript:popUp(\'timeline.php\', 450, 350);">Schedule</a>';

$addEdit = '<div class=thelist><h2>Add / Edit Newsletter</h2>
<form method=POST> 
<table> 
<tr>
	<td>category</td><td><input type=text name=category value="'.$n[category].'" class="input">
	'.$addEditButtons.'</td>
</tr><tr>
	<td>subject</td><td><input type=t
	ext name=subject value="'.$n[subject].'" class="input" size=50></td>
</tr><tr>
	<td colspan=2>message (html code) <br><textarea cols=70 rows=30 name=message>'.stripslashes($n[message]).'</textarea></td>
</tr><tr>
	<td colspan=2 align=center>'.$addEditButtons.'</td>
</tr>
</table>';


$selN = 'select * from newsletter order by category';
$resN = mysql_query($selN, $conn) or die(mysql_error());

while($t = mysql_fetch_assoc($resN))
{
	if($_GET[cat] == $t[category])
		$theList .= '<a href="?cat='.$t[category].'"><strong>'.$t[category].'</strong></a><br>';
	else
		$theList .= '<a href="?cat='.$t[category].'">'.$t[category].'</a><br>';
}

if($msg)
	$msg = '<fieldset><legend align=center>Message</legend>'.$msg.'</fieldset>';

echo $msg.'<table>
<tr valign=top>
	<td>'.$theList.'</td><td>'.$addEdit.'</td><td>'.$dateList.'</td>
</tr></table>'; 


?>