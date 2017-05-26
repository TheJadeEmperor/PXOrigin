<?php
include('adminCode.php');

if($_POST[update])
{
	$upd = 'update settings set setting="'.$_POST[adminUser].'" where opt="adminUser"';
	mysql_query($upd, $conn) or die(mysql_error());
	
	$upd = 'update settings set setting="'.$_POST[adminPass].'" where opt="adminPass"';
	mysql_query($upd, $conn) or die(mysql_error());
}

$selA = 'select * from settings where opt="adminUser" || opt="adminPass"';
$resA = mysql_query($selA, $conn) or print(mysql_error());

while($a = mysql_fetch_assoc($resA))
{
	if($a[opt] == 'adminUser')
		$adminUser = $a[setting];
	else
		$adminPass = $a[setting];
}
?>

<form method=post>
<table class=thelist>
<tr>
	<th colspan=2 align=center>Admin Account</th>
</tr><tr>
	<td>Username</td><td><input type=text class=activeField name=adminUser value="<?=$adminUser?>" size=30></td>
</tr><tr>
	<td>Password</td><td><input type=text class=activeField name=adminPass value="<?=$adminPass?>" size=30></td>
</tr><tr>
	<td colspan=2 align=center><input type=submit name=update value=" Update Admin Account "></td>
</tr>
</table>
</form>