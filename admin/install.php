<?php
include('adminCode.php');

$sales = 'create table sales( 
payerEmail varchar(100), 
contactEmail varchar(100),
amount varchar(20),
plan varchar(1),
itemNumber varchar(50),
datetime datetime, 
fname varchar(50),
lname varchar(50),
status varchar(1),
notes text 
)';

$settings = 'create table settings (
opt varchar(20),
setting varchar(50)
)';

$notes = 'create table notes (
id varchar(20),
notes text) ';


$users = 'create table users (
id int(10) primary key auto_increment, 
fname varchar(40),
lname varchar(40),
email varchar(100),
paypal varchar(100),
txn_id varchar(100),
joinDate datetime, 
username varchar(20),
password varchar(20),
membership varchar(1), 
sponsor int(10),
commission varchar(1), 
money varchar(6),
points int(10)
)';

$news = 'create table newsletter (
category varchar(20), 
subject varchar(100),
message text
)';

$memberspages = 'create table memberspages (
url varchar(20),
header varchar(30),
footer varchar(30),
file varchar(30)
)';

$posts = 'create table posts (
id int(10) primary key auto_increment, 
subject varchar(100), 
postedOn datetime, 
postedBy varchar(30), 
post text, 
live varchar(1)
)';

$comments = 'create table comments (
id int(10) primary key auto_increment, 
replyID int(10), 
postedOn datetime, 
postedBy varchar(30), 
post text
)';


mysql_query($notes, $conn) or print(mysql_error()); 
mysql_query($sales, $conn) or print(mysql_error()); 
mysql_query($settings, $conn) or print(mysql_error()); 
mysql_query($users, $conn) or print(mysql_error()); 
mysql_query($news, $conn) or print(mysql_error()); 
mysql_query($memberspages, $conn) or print(mysql_error()); 
mysql_query($posts, $conn) or print(mysql_error()); 
mysql_query($comments, $conn) or print(mysql_error()); 


/* SMTP & Admin Settings */
$dbInsert = array(
'fromEmail' => $fromEmail, 
'fromName' => $fromName,
'smtpHost' => $smtpHost,
'smtpPass' => $smtpPass, 
'adminEmail' => $adminEmail,
'adminUser' => 'username',
'adminPass' => 'password', 
'websiteName' => '',
'businessName' => '',
'websiteURL' => '',
);

foreach($dbInsert as $opt => $setting)
{
	$sel = 'select opt from settings where opt="'.$opt.'"';
	$res = mysql_query($sel, $conn) or print(mysql_error()); 
	
	if(mysql_num_rows($res) == 0)
	{
	 	$ins = 'insert into settings (opt, setting) values ("'.$opt.'", "'.$setting.'")';
		mysql_query($ins, $conn) or print(mysql_error()); 
	}
}

//insert comments 

$dbInsert = array(
'main' => 'these are your urgent notes',
'imp' => 'these are your important notes' );

foreach($dbInsert as $opt => $setting)
{
	$sel = 'select * from notes where id="'.$opt.'"';
	$res = mysql_query($sel, $conn) or print(mysql_error()); 
	
	if(mysql_num_rows($res) == 0)
	{
	 	$ins = 'insert into notes (id, notes) values ("'.$opt.'", "'.$setting.'")';
		mysql_query($ins, $conn) or print(mysql_error()); 
	}
}


?>