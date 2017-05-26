<?php
include('include/settings.php'); 

if(isset($_SESSION[login]))
{	
	if($_SESSION[login][id] == '0')
	{
		$sel = 'select * from settings where opt="adminUser" || opt="adminPass"';
		
		$res = mysql_query($sel, $conn) or die(mysql_error());
		
		while($s = mysql_fetch_assoc($res))
		{
			if($s[opt] == 'adminUser')
				$u[fname] = $u[username] = $s[setting];
		}
		
		$u[id] = 0;
	}
	else
	{
		$sel = 'select * from users where username="'.$_SESSION[login][username].'"
		and id="'.$_SESSION[login][id].'"';
		$res = mysql_query($sel, $conn) or die(mysql_error());

		$u = mysql_fetch_assoc($res);
	}
}



//default template
$header = 'mainHeader.php';
$footer = 'mainFooter.php';
$action = $_GET[action];

if($_GET[post])
{
	include($header); 
	include('post.php');
	include($footer); 
	exit; 
}

if($_GET[tags])
{
    include($header); 
    include('tags.php');
    include($footer); 
    exit; 
}


switch($action)
{
case 'userContent':
case 'talesof':
case 'aboutUs':
	include($header);
	include($_GET[action].'.html');
	include($footer); 
	break;	
case 'viewProfile': 
	include($header);
	include($_GET[action].'.php');
	include($footer); 
	break;

case 'post':
	include($header);
	include('post.php');
	include($footer); 
	break;	
case 'logout':
	include($header);
	include('members/logout.php');
	include($footer); 
	break;
case 'profile':
	include($header);
	include('members/updateProfile.php');
	include($footer); 
	break;
case 'contact':
	include($header);
	include('contact.html');
	include($footer); 
	break;
case 'login':
	include($header);
	include('login.php');
	include($footer); 
	break;
case 'register':
	include($header);
	include('register.php');
	include($footer); 
	break; 
case 'main':
	include($header);
	include('main.php');
	include($footer); 
	break; 
default:
	if($prelaunch)
	{
		if(date('Y-m-d', time()) < $prelaunchDate)
		{
			include($header);
			include('prelaunch.html');
			include($footer); 
		}
		else
		{
			include($header);
			include('main.php');
			include($footer); 
		} 
	}
	else
	{
		include($header);
		include('main.php');
		include($footer); 
	}
}

?>