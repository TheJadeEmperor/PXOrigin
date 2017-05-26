<?php
include('adminCode.php');

$dbFields = array(
'fname' => '"'.$_POST[fname].'"',
'lname' => '"'.$_POST[lname].'"',
'username' => '"'.$_POST[username].'"',
'password' => '"'.$_POST[password].'"',
'email' => '"'.$_POST[email].'"',
'paypal' => '"'.$_POST[paypal].'"',
'membership' => '"'.$_POST[membership].'"',
'sponsor' => '"'.$_POST[sponsor].'"', 
'status' => '"'.$_POST[status].'"', 
'title' => '"'.$_POST[title].'"',
'country' => '"'.$_POST[country].'"',
'age' => '"'.$_POST[age].'"',
);

//update db 
if($_POST[update])
{
	$set = array();
	
	foreach($dbFields as $fld => $val)
	{
		array_push($set, $fld.'='.$val);
	}
	
	$theSet = implode(',', $set);
	
	$upd = 'update users set '.$theSet.' where id="'.$_GET[id].'"';
	mysql_query($upd, $conn) or die(mysql_error());
}


if($_GET[id])
{
	//user info
	$selU = 'select * from users where id="'.$_GET[id].'"';
	$resU = mysql_query($selU, $conn) or die(mysql_error());

	$u = mysql_fetch_assoc($resU);
	$mem[ $u[membership] ] = 'selected';
	
	//sponsor info
	$selS = 'select * from users where id="'.$u[sponsor].'"';
	$resS = mysql_query($selS, $conn) or die(mysql_error());
	
	$s = mysql_fetch_assoc($resS);
	
	
}
?>
<div class="moduleBlue"><h1>Update User Profile </h1><div>
<form method=post>
<table>
<tr title="User's unique registration ID - given upon registration - it cannot be changed">
	<td>User ID</td><td><?=$u[id]?>  <img src="<?=$helpImg?>" ></td>
</tr><tr title="username">
	<td>Username</td><td><input class=activeField name=username value="<?=$u[username]?>"> </td>
</tr><tr>
	<td>Password </td><td><input class=activeField name=password value="<?=$u[password]?>"></td>
</tr><tr>
	<td>First Name </td><td><input class=activeField name=fname value="<?=$u[fname]?>"></td>
</tr><tr>
	<td>Last Name </td><td><input class=activeField name=lname value="<?=$u[lname]?>"></td>
</tr><tr>
	<td>Contact Email</td><td><input class=activeField name=email value="<?=$u[email]?>" size=30></td>
</tr><tr>
	<td>Paypal Email </td><td><input class=activeField name=paypal value="<?=$u[paypal]?>" size=30 disabled></td>
</tr><tr title="User's membership type: N = New User | T = Trusted User | A = Admin">
	<td>Membership Type</td><td>
	<select class=activeField name=membership>
	<option <?=$mem[N]?> value="N">New User</option>
	<option <?=$mem[T]?> value="T">Trusted User</option>
	<option <?=$mem[A]?> value="A">Admin</option>
	</select>
	 
	<img src="<?=$helpImg?>"> </td>
</tr><tr>
	<td>Sponsor </td><td><input class=activeField  name=sponsor value="<?=$u[sponsor]?>" size=3 title="<?=$s[username].' | '.$s[fname].' '.$s[lname]?>"> &nbsp; <a href="?id=<?=$s[id]?>">View sponsor's profile</a></td>
</tr><tr>
	<td>Title </td><td><input class=activeField  name=title value="<?=$u[title]?>"></td>
</tr><tr>
	<td>Country </td><td><input class=activeField  name=country value="<?=$u[country]?>"></td>
</tr><tr>
	<td>Age </td><td><input class=activeField  name=age value="<?=$u[age]?>"></td>
</tr><tr>
	<td colspan=2 align=center><input type=submit class=activeField  name=update value="Update Profile"></td>
</tr>
</table>
</form>
<center><a href="users.php">Back to members list</a></center>
</div></div>