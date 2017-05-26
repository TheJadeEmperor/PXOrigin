<?php
include('adminCode.php');

set_time_limit(120);

//get emails from customer search 
$eCount = 1;
if(isset($_SESSION[sendTo]))
{
	foreach($_SESSION[sendTo] as $st)
	{
		if($eCount == sizeof($_SESSION[sendTo])) //last email
			$allEmails .= $st;	
		else
			$allEmails .= $st.'
'; 
		$eCount ++; 
	}
}


if($_POST[message])
{
	$_POST[message] = stripslashes($_POST[message]);
	$_POST[message] = str_replace("\"", '&quot;', $_POST[message]);
	$_POST[message] = str_replace('\'', '&#39;', $_POST[message]);
	
	echo '<fieldset>'.$_POST[message].'</fieldset><br>';
}

if($_POST['sendEmail'] && $_POST['message'] != '')
{
//	$uploaddir = 'upload';
	$key = 0;
	$tmp_name = $_FILES["userfile"]["tmp_name"][$key];
	$name = $_FILES["userfile"]["name"][$key];
	$sendfile = "$name";
	move_uploaded_file($tmp_name, $sendfile);
	
	$to = $_POST['to'];
	$addresses = array();
	
	$addresses = explode("\n", $to);
	//print_r($addresses);exit;
	
	$name = $_POST['who'];
	$email_subject = $_POST['subject'];
	$Email_msg = $_POST['message'];
	$Email_msg2 = str_replace("\n", "<br>", $Email_msg);;
	$email_from = $_POST['from'];

	$attachments = array();
	
	foreach ($addresses as $Email_to)
	{
		$mail = new PHPMailer();
	
		$mail->IsSMTP();                                   // send via SMTP
		$mail->Host     = $host; // SMTP servers
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		$mail->Username = $username;  // SMTP username
		$mail->Password = $password; // SMTP password
		
		$mail->From     = $email_from;
		$mail->FromName = $name;
		$mail->AddAddress($Email_to);  
		
		$mail->AddAttachment($sendfile);
		
		$mail->WordWrap = 50;                              // set word wrap
		$mail->IsHTML(true);                               // send as HTML
		
		$mail->Subject  =  $email_subject;
		$mail->Body     =  $Email_msg2;
		$mail->AltBody  =  $Email_msg;
		
		if(!$mail->Send())
		{
		   echo "Message was not sent <p>";
		   echo "Mailer Error: " . $mail->ErrorInfo;
		}
		
		echo "Message to $Email_to has been sent<br>";
		
	}
}

//variables
$fromEmail = $username;
//$allEmails = $username;


/*
$selE = 'select * from subscribers order by email';
$resE = mysql_query($selE, $conn) or die(mysql_error());

while($e = mysql_fetch_assoc($resE))
{
	if($e[email])
		$allEmails .= "\n".$e[email];
}
*/

$selN = 'select * from newsletter order by category';
$resN = mysql_query($selN, $conn) or die(mysql_error());

while($n = mysql_fetch_assoc($resN))
{
	$pick = '';	
	if($_POST[newsletter] == $n[category])  //this template
	{	
		$pick = 'selected';
		$news = formatNewsletter( $n );
//		$news = $n;
	}
		
	
	$newsOpt .= '<option value="'.$n[category].'" '.$pick.'>'.$n[category].'</option>';
}
?>

<table class="thelist"><tr><th>Send Mass Mail</th></tr></table>
<form method=post enctype="multipart/form-data">
<table>
<tr>
	<td><p>From Email Address</p></td><td><a rel="fromEmail"><img src='include/help.png' border=0></a></td>
	<td><b><?=$fromEmail?></b></td>
</tr><tr>
	<td><p>From Name</p></td><td><a rel="fromName"><img src='include/help.png' border=0></a></td>
	<td><input name=who size=30 maxlength=30 value="<?=$fromName?>"> ex: Administrator</td>
</tr><tr>
	<td><p>Email Template</p></td><td><a rel="template"><img src='include/help.png' border=0></a></td>
	<td><select name=newsletter onchange="submit();"><option></option><?=$newsOpt?></select></td>
</tr><tr>
	<td><p>Subject</p></td><td><a rel="subject"><img src='include/help.png' border=0></a></td>
	<td><input name=subject size=60 maxlength=60 value="<?=$news[subject]?>"></td>
</tr><tr valign="top">
	<td><p>Send To Email(s)</p></td>
	<td><p><a rel="sendTo"><img src='include/help.png' border=0></a></p></td>
	<td><p><textarea name="to" cols=70 rows=9><?=$allEmails?></textarea></p></td>
	<td><p>ex: <br>1@1.com<br>2@2.com<br>3@3.com</p></td>
</tr><tr valign="top">
	<td><p>Message</p></td>
	<td><p><a rel="message"><img src='include/help.png' border=0></a></p></td>
	<td><p><textarea name="message" cols=70 rows=15><?=$news[message]?></textarea></p></td>
</tr><tr valign="top">
	<td><p>Attach a file</p></td><td><p><a rel="upload"><img src='include/help.png' border=0></a></p></td>
	<td><p><input type=file name="userfile[]" class="bginput" size=30> &nbsp;
	<br>Max file size: 4 MB</p>
</tr><tr>
	<td colspan=3>	<input type=submit name=sendEmail value="Send mail"></td>
</tr>
</table>
 
<input type=hidden name=from value="<?=$fromEmail?>">
</form>

<div id="upload" class="balloonstyle">You can upload a file from your computer</div>

<div id="fromName" class="balloonstyle">Name of person sending the email</div>

<div id="fromEmail" class="balloonstyle">Email to send from</div>

<div id="template" class="balloonstyle">Choose a template, you can edit these by clicking on "Email Templates"
in the main menu</div>

<div id="subject" class="balloonstyle">Email subject, do NOT use HTML here</div>

<div id="sendTo" class="balloonstyle">Enter the email addresses of the recipients, with a line
in between each one - do NOT use commas</div>

<div id="message" class="balloonstyle">Enter the message to be sent - basic HTML allowed</div>