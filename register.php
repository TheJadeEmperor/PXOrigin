<?php
function validUserName($user, $conn)
{
	$sel = 'select username from users where username="'.$user.'"';
	$res = mysql_query($sel, $conn) or die(mysql_error());
	
	if(mysql_num_rows($res) > 0)
		return false; 
	else
		return true;
}


if($_POST[register])
{
	if($_POST['fname'] == '')
	{
		$errorMsg = 'First name cannot be blank<br>';
	}
	
	if($_POST['lname'] == '')
	{
		$errorMsg .= 'Last name cannot be blank<br>';
	}
	
	if($_POST['username'] == '')
	{
		$errorMsg .= 'Username cannot be blank<br>';
	}
	else
	{
		if(!validUserName($_POST['username'], $conn))
		{
			$errorMsg .= 'Username already taken. Please use another one.<br>';
		}
	}
	
	if($_POST[password] == '')
	{
		$errorMsg .= 'Password cannot be blank<br>';
	}
	else
	{
		if(strlen($_POST[password]) < 7)
			$errorMsg .= 'Password must be 8 characters long<br>';
	}
	
	if($_POST[email] == '')
	{
		$errorMsg .= 'Email cannot be blank<br>';
	}
	
	if($errorMsg == '') //enter new user into db
	{
		if($s[id] == '') //if no sponsor
			$s[id] = 1; //default sponsor is admin
	
		$dbFields = array(
		'fname' => '"'.$_POST[fname].'"',
		'lname' => '"'.$_POST[lname].'"',
		'username' => '"'.$_POST[username].'"',
		'password' => '"'.$_POST[password].'"',
		'email' => '"'.$_POST[email].'"',
		'sponsor' => '"'.$s[id].'"',
		'joinDate' => '"'.date('Y-m-d', time()).'"');
		
		$fields = $values = array();
		
		foreach($dbFields as $fld => $val)
		{
			array_push($fields, $fld);
			array_push($values, $val);
		}
		
		$theFields = implode(',', $fields);
		$theValues = implode(',', $values);
		
		$ins = 'insert into users ('.$theFields.') values ('.$theValues.')';
		$res = mysql_query($ins, $conn) or die(mysql_error());
		
		$errorMsg = '<font color=red>You have successfully registered! A confirmation email
		has been sent to you. <a href="?action=login">Please log in now</a>.</font>';
		
		sendWelcomeEmail($conn);
		
		$disField = 'disabled'; 
	}
	else
	{
		$errorMsg = '<font color=red>'.$errorMsg.'</font>';
	}
}


?>
<center>
<p>&nbsp;</p>


<div class="moduleBlue"><h1>Join <?=$businessName?> Today!</h1>
<div>
<h3>By joining PXO, I understand that: </h3>

<ul align="left">
<li><p>I understand that a membership with <?=$businessName?> is completely free and I am under
	no obligation to buy or sign up for anything</p></li>

<li><p>I understand that I will not be spammed nor will my email address be shared with third party
services</p></li>

<li><p>I agree not to spam or advertise the comments of this website and follow all rules set forth
	by the administrator</p></li>
   
<li><p>I agree to receive emails & announcements from time to time from the administrator of this site</p></li>
</ul>
</div>
</div>

<p>&nbsp;</p>

<table><tr><td>
<div class="moduleBlue" id="register">
<h1>Register for Free Account</h1>
<form method=post action="#register">

<table cellpadding="5" class="register">
<tr>
	<td colspan=2><p><?=$errorMsg?></p></td>
</tr><tr>
	<td>First Name</td><td><input class=activeField name=fname value="<?=$_POST[fname]?>" size=40 <?=$disField?>></td>
</tr><tr>
	<td>Last Name</td><td><input class=activeField type=text name=lname value="<?=$_POST[lname]?>" size=40></td>
</tr><tr>
	<td>Username</td><td><input class=activeField type=text name=username value="<?=$_POST[username]?>" size=40></td>
</tr><tr>
	<td>Password</td><td><input class=activeField type=text name=password value="<?=$_POST[password]?>" size=40></td>
</tr><tr>
	<td>Email Address</td><td><input class=activeField type=text name=email value="<?=$_POST[email]?>" size=40></td>
</tr><tr>
	<td>Sponsor</td><td><?=$s[username]?></td>
</tr><tr>
	<td colspan=2 align=center><input type=submit name=register value="Register Now" <?=$disField?>></td>
</tr>
</table>
<input type=hidden name=sponsor value="<?=$u[id]?>"> 
</form>
</div>
</center>
</td></tr></table>