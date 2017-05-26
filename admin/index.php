<?php
session_start();
$dir = '../';
include($dir.'include/settings.php');
 

if($_POST[login])
{
	$selU = 'select * from users where username="'.$_POST[username].'"';
	$resU = mysql_query($selU, $conn) or die(mysql_error());
	
	$u = mysql_fetch_assoc($resU);
	
	if($u[membership] == 'A')
	{
		if($_POST[username] == $u[username] && $_POST[password] == $u[password])
		{
			 
			$_SESSION[login] = $u;
			header('Location: ./main.php');
		}
		else 
		{
			$err = 'Wrong credentials';
		}
	}
	else
	{
		$err = 'You do not have permission to acces this area.';
	}
}//if
?>
<center>
<p><?=$websiteName ?> Admin Login</p>

<font color=red><?=$err ?></font>
<br><br><form method=POST>
<table width=100%>
<tr>
	<td width=40%></td><td>
		<fieldset><legend>Control Panel</legend>
		<table>
		<tr>
			<td>Username:</td><td><input type=text name="username"/></td>
		</tr><tr>
			<td>Password:</td><td><input type=password name="password"/></td>
		</tr><tr>
			<td colspan="2" align="center"><input type=submit name=login value=" Login to Admin Panel "></td>
		</tr>
		</table>
		</fieldset>
	</td><td width=40%></td>
</tr>
</table></form>

<a href="<?=$websiteURL?>">Return to website</a>
</center>