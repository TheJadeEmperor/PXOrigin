<?php

if($_GET[id])
{
	$id = $_GET[id];

	$selU = 'select * from users where id="'.$_GET[id].'"';
	$resU = mysql_query($selU, $conn); 
	
	$u = mysql_fetch_assoc($resU);
	
	switch($u[membership])
	{
		case 'A':
			$membership = 'Admin';
			break;
		case 'T':
			$membership = 'Trusted User';
			break;
		case 'N':
		default:
			$membership = 'New User';
	}
	
	if($u[id] == 1)
		$u[title] = '<img src="images/founder.png">';
		
	$u[bio] = stripslashes($u[bio]);

	$profileContent = '<div class="moduleBlue"><h1>View User Profile</h1><div>
	<table>
	<tr valign=top>
		<td><img src="members/profile/'.$id.'.jpg" alt="'.$u[username].'"></td>
	</tr><tr>
		<td>
			<table>
			<tr valign=top>
				<td>Title</td><td>'.$u[title].'</td>
			</tr><tr valign=top>
				<td>Username</td><td>'.$u[username].'</td>
			</tr><tr valign=top>
				<td>Email Address</td><td>'.$u[email].'</td>
			</tr><tr valign=top>
				<td>Membership Type </td><td>'.$membership.'</td>
			</tr><tr valign=top>
				<td>Country</td><td>'.$u[country].'</td>
			</tr><tr valign=top>
				<td>Age</td><td>'.$u[age].'</td>
			</tr><tr valign=top>
				<td>Favorite Game(s)</td><td>'.$u[games].'</td>
			</tr>
			</table>
		</td>
	</tr><tr>
		<td style="padding: 5px;"><p>About Me:</p> '.$u[bio].'</td>
	</tr>
	</table>
	</div></div>';
}
else
{
	$profileContent = 'That user does not exist';
}

echo '<br>'.$profileContent;
?>