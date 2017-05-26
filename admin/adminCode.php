<?php
session_start();
$dir = '../';
include($dir.'include/settings.php');

if($_SESSION[login] == '')//if not logged in, redirect back to login page
	header('Location: '.$dir);


$menuSite = array(
'bar' => array(
	'link' => '../',
	'title' => 'Site Content'),
'item' => array(  
	'Manage Posts' => array(
		'link' => 'javascript:void',
		'title' => 'Show Posts',
		'sub_menu' => array(
			'New Post' => array(
				'link' => 'postNew.php',
				'title' => 'Make new post' ),
			'Show All Posts' => array(
				'link' => 'postAll.php',
				'title' => 'Show all posts'), 
		)		
	),
	'Website Help' => array(
		'link' => 'help.php',
		'title' => 'Help with using this site'
	),
	'Install Script' => array(
		'link' => 'install.php',
		'title' => 'Install script - first time use only', 
	),

	/*'Site Pages' => array(
		'link' => 'javascript:void',
		'title' => 'Site Pages',
		'sub_menu' => array(
			'Member Pages' => array(
				'link' => 'memberPages.php',
				'title' => 'Member Pages' ),
		)
	),*/
)
);


$menuMembers = array(
'bar' => array(
	'link' => 'users.php',
	'title' => 'Member Management'),
'item' => array(
	'Manage Members' => array(
		'link' => 'users.php',
		'title' => 'Show entire members list'),
	'Email Templates' => array(
		'link' => 'newsletter.php',
		'title' => ''),
	'Send Mass Emails' => array(
		'link' => 'sendEmail.php',
		'title' => 'Send mass emails')
)
);


$menuSettings = array(
'bar' => array(
	'link' => 'main.php',
	'title' => 'Settings and Options'),
'item' => array(
	'MySQL Query' => array(
		'link' => 'query.php',
		'title' => 'Website Settings'),
	'Database List' => array(
		'link' => 'databaseList.php',
		'title' => 'Database List'),
	'Logout' => array(
		'link' => 'logout.php', 
		'title' => 'Logout'),
)
);

$helpImg = $dir.'include/help.png';

 

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head><title>'.$businessName.' Admin Area</title>
<link href="'.$dir.'include/admin.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="'.$dir.'include/jquery.js">
<script type="text/javascript">
function popUp(URL, width, height)
{
	window.open(URL, "", "menubar=no, scrollbars=yes, resizable=yes, left=0, top=0, width="+width+", height="+height);
}

function show(x)
{
	document.getElementById(x).style.display="block";
}

function hide(y)
{
	document.getElementById(y).style.display="none";
}
</script>
</head>
<body>
<center>
<table>
<tr valign="top">
<td align="left" width="180px">
	'.showMenu($menuSite).'<br>'.showMenu($menuMembers).'<br> '.showMenu($menuSettings).'<br> 
'; ?>
</td><td align="left">