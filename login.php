<?php 
	
if($_POST[login])
{
	$sel = 'select * from users where username="'.$_POST[username].'"';
	$res = mysql_query($sel, $conn) or die(mysql_error());
	
	$u = mysql_fetch_assoc($res); 
	
	if(mysql_num_rows($res) == 0)
	{
		$error = 'No combination of that username / password exists. Please
		check your spelling and try again.'; 
	}
	else
	{
		if($_POST[password] != $u[password])
		{
			$error = 'You entered the wrong username / password. Please
			check your spelling and try again.';
		}
		else
		{
			$_SESSION[login] = $u; 
		
			echo '<meta http-equiv="refresh" content="1;url=./?action=main">
			<p>&nbsp;</p>
			<center><h3>Logging you in...</h3>
			<img src="images/waiting.gif"></center>			
			<p>&nbsp;</p>'; 
			exit; 
		}
	}
}

?>
<br>

<center>
<table><tr><td>
<div class="moduleBlue"><h1>Members Login</h1><div>
<p><?=$error?></p>

<form method=post>
	<center>
	<table><tr valign="top">
		<td>Username </td><td><input type="text" name="username" class="activeField">&nbsp;</td>
	</tr><tr>
		<td>Password </td><td><input type="password" name="password" class="activeField">&nbsp;</td>
	</tr><tr>
		<td align=center colspan=2><input type=image src="images/getAccessNow.png" name="login" width="200px">
		<input type=hidden name="login" value="Y"></td>
	</tr>
	</table>
	</center>
</form>
</div></div>
</td></tr></table>

<a href="mailto:<?=$adminEmail?>">Forgot password?</a>
</center>

<p>&nbsp;</p>

<p>&nbsp;</p>