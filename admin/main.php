<?php
include('adminCode.php');

$dbFields = array(
'fromEmail' => $_POST['fromEmail'], 
'fromName' => $_POST[fromName], 
'smtpHost' => $_POST[smtpHost], 
'smtpPass' => $_POST[smtpPass],
'adminEmail' => $_POST[adminEmail],
'adminFrom' => $_POST[adminFrom]
);

if($_POST[update])
{
	foreach($dbFields as $fld => $set)
	{
		$upd = 'update settings set setting="'.$set.'" where opt="'.$fld.'"';
		mysql_query($upd, $conn) or die(mysql_error());
	}
}


$selS = 'select * from settings order by opt';
$resS = mysql_query($selS, $conn) or die(mysql_error());

while($s = mysql_fetch_assoc($resS))
{
	$val[$s[opt]] = $s[setting];
}


$properties = 'class="activeField" size="40"';

$emailSettings =  '<table>
<tr title="fromEmail">
	<td>From Email: </td><td><input '.$properties.' name=fromEmail value="'.$val[fromEmail].'"></td>
</tr><tr title="fromName">
	<td>From Name: </td><td><input '.$properties.' name=fromName value="'.$val[fromName].'"></td>
</tr><tr title="smtpHost">
	<td>SMTP Host: </td><td><input '.$properties.' name=smtpHost value="'.$val[smtpHost].'"></td>
</tr><tr title="smtpPass">
	<td>SMTP Password: </td><td><input '.$properties.' name=smtpPass value="'.$val[smtpPass].'"></td>
</tr>
</table>';


?>
<form method=POST>
<table>
<tr valign=top>
<td>
	<div class=moduleBlue><h1>SMTP Email Settings (For mass emails)</h1><div>
	<?=$emailSettings ?>
	</div></div>
</td><td>
	<div class=moduleBlue><h1>Admin Email / Support Email</h1><div>
	<table>
	<tr valign=top>
		<td>Admin Email: </td><td><input <?=$properties?> name=adminEmail value="<?=$val[adminEmail]?>"></td>
	</tr>
		<td>From Name: </td><td><input <?=$properties?> name=adminFrom value="<?=$val[adminFrom]?>"></td><tr>
	</tr>
	</table>
	</div></div>
</td>
</tr><tr>
<td colspan=2 align=center><input type=submit name=update value=" Save Settings "></td>
</tr>
</table>
</form>
<?

if($_POST[notesUrgent])
{
	$upd = 'update notes set notes="'.addslashes($_POST[notesUrgent]).'" where id="main"';
	$res = mysql_query($upd, $conn) or print(mysql_error());
}

if($_POST[notesImp])
{
	$upd = 'update notes set notes="'.addslashes($_POST[notesImp]).'" where id="imp"';
	$res = mysql_query($upd, $conn) or print(mysql_error());
}


$selN = 'select * from notes where id="main"';
$resN = mysql_query($selN, $conn) or print(mysql_error());
$n = mysql_fetch_assoc($resN);

$selI = 'select * from notes where id="imp"';
$resI = mysql_query($selI, $conn) or print(mysql_error());
$i = mysql_fetch_assoc($resI);

if(file_exists('error_log'))
{
	$log = '<a href="error_log">View error_log</a>';
}
?>
<p>&nbsp;</p>

<table><tr valign=top><td>
<form method=POST><fieldset>
<legend>Admin Notes: Urgent</legend>
<textarea name=notesUrgent rows=20 cols=50><?=stripslashes($n[notes])?></textarea>
<br><input type=submit name="Submit">
</fieldset></form>
</td><td>

<form method=POST><fieldset>
<legend>Admin Notes: Important</legend>
<textarea name=notesImp rows=20 cols=50><?=stripslashes($i[notes])?></textarea>
<br><input type=submit name="Submit">
</fieldset></form>
</td></tr></table>

<?=$log?>