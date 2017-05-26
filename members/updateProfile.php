<?php
function validUserName($user, $conn)
{
	$sel = 'select username from users where username="'.$user.'"';
	$res = mysql_query($sel, $conn) or die(mysql_error());
	
	if(mysql_num_rows($res) > 1)
		return false; 
	else
		return true;
}

function checkImg($file)
{
	list($name, $ext) = explode('.', $file);
	
	$valid = array('jpg', 'JPG', 'png', 'PNG', 'GIF', 'gif');
	
	if(in_array($ext, $valid))
		return true;
	else
		return false; 
}


function upload()
{
	$targetFolder = 'members/profile';
	$imgName = $_SESSION[login][id].'.jpg';
	$imgPath = $targetFolder.'/'.$imgName;
		
	//the path to move the image to
	$basename = basename( $_FILES['uploadedfile']['name'] );
		
	$targetPath = $targetFolder . $basename; 
	 
	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $targetPath)) 
	{		
		if(checkImg($basename))
		{
			list($width, $height, $type, $attr) = getimagesize( $targetPath );
		
			if( file_exists($imgPath) )
			{
				unlink($imgPath);
				$message = $imgName.' already exists. File has been replaced.<br>';
			}
			
			if( rename( $targetPath, $imgPath) )
				$message .= 'The file '.basename( $_FILES['uploadedfile']['name']).' has been successfully
		   		uploaded and renamed to '.$imgName;
			else 
				$message = 'Failed to rename image.';
		}
		else
		{
			$message = 'Invalid image file!';
			unlink($targetPath);
		}
	} 
	else
	{
		$message = 'There was an error uploading the file, please try again!';
	}
	
	return $message;
}
 

if($_FILES['uploadedfile']['name'] != '')		//handle uploaded images
{
	$uploadMessage =  upload();
	$uploadMessage = '<font color=red>'.$uploadMessage.'</font>';
}


if($_POST[update])
{
	$dbFields = array(
		'bio' => $_POST[bio],
		'password' => $_POST[password], 
		'fname' => $_POST[fname],
		'lname' => $_POST[lname],
		'email' => $_POST[email],
		'age' => $_POST[age],
		'country' => $_POST[country],
		'games' => $_POST[games]); 
	
	$set = array();
	
	foreach($dbFields as $fld => $val)
	{
		array_push($set, $fld.'="'.addslashes($val).'"');
	}
	
	$theSet = implode(',', $set);
	
	//do error checking 
	if($_POST[password] == '')
	{
		$msg = 'Password cannot be blank <br>';
	}
	else
	{
		if(strlen($_POST[password]) < 4)
		{
			$msg = 'Password must be 4 characters long or more<br>';
		}
	}
	
	if($msg == '') 
	{
		$upd = 'update users set '.$theSet.' where id="'.$_SESSION[login][id].'"';
		$res = mysql_query($upd, $conn) or die(mysql_error());
	
		//$_SESSION[login][username] = $_POST[username];
		
		$msg = 'Profile successfully updated';
	}
	
	$msg = '<font color=red>'.$msg.'</font>';
}


$sel = 'select * from users where id="'.$_SESSION[login][id].'"';
$res = mysql_query($sel, $conn) or die(mysql_error());

$u = mysql_fetch_assoc($res);

$u[bio] = stripslashes($u[bio]);

switch($u[membership])
{
	default:
	case 'N':
		$membership = 'New User';
		break;
	case 'T':
		$membership = 'Trusted User';
		break;
	case 'A':
		$membership = 'Admin';
		break; 
}
?> 
<h1>Update Member Profile</h1>

<div class="moduleBlue"><h1>Member Profile</h1><div>
<?=$msg?>
<form method=post>
<table>
<tr valign=top title="This is your username - alphanumeric characters">
	<td width="120px">Username </td>
	<td><a href="#"><img src="include/help.png" border=0></a></td>
	<td><input type=text class=input name=username value="<?=$u[username]?>" size=30 disabled></td>
</tr><tr valign=top title="This is your account password - make sure you remember it!">
	<td>Password </td>
	<td><a href="#"><img src="include/help.png" border=0></a></td>
	<td><input type=password class=activeField name=password value="<?=$u[password]?>"><br>Alpha-numeric characters, must be at least 4 characters long.</td>
</tr><tr title="Your current status on the website">
	<td>Status</td>
	<td><a href="#"><img src="include/help.png" border=0></a></td>
	<td><?=$membership?> </td>
</tr><tr>
	<td>First Name </td><td></td>
	<td><input type=text class=activeField name=fname value="<?=$u[fname]?>"></td>
</tr><tr>
	<td>Last Name </td><td></td>
	<td><input type=text class=activeField name=lname value="<?=$u[lname]?>"></td>
</tr><tr title="Your preferred email address you wish to be contacted at">
	<td>Contact Email </td>
	<td><a href="#"><img src="include/help.png" border=0></a></td>
	<td> <input type=text class=activeField name=email value="<?=$u[email]?>" size=30> </td>
</tr><tr title="Your age">
	<td>Age </td>
	<td> </td>
	<td> <input type=text class=activeField name=age value="<?=$u[age]?>" size=30> </td>
</tr><tr title="Your country of origin">
	<td>Country </td>
	<td> </td>
	<td> <input type=text class=activeField name=country value="<?=$u[country]?>" size=30> </td>
</tr><tr title="Your favorite games">
	<td>Favorite Games </td>
	<td> </td>
	<td> <input type=text class=activeField name=games value="<?=$u[games]?>"> </td>
</tr><tr>
	<td colspan=3 align=center> <input type=submit name=update value="Update Profile"> </td>
</tr>
</table>
<input type=hidden name=sponsor value="<?=$u[sponsor]?>">
</div></div>

<script type="text/javascript" src="admin/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "simple",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->

<br>
<div class="moduleBlue"><h1>Personal Bio</h1><div>
<p>Enter your personal bio: <br><textarea cols=70 rows=10 id=elm1 name=bio><?=$u[bio]?></textarea></p>
<center><input type=submit name=update value="Update Profile"></center>
</div></div>
</form>


<br>
<div class="moduleBlue"><h1 id="upload">Profile Picture</h1><div>
<p><?=$uploadMessage?></p>

<form enctype="multipart/form-data" action="#upload" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="500000" />
Choose a file to upload <input name="uploadedfile" type="file" size="35" />
<br>Max file size 500KB <input type="submit" value="Upload File"/>
</form>

<? 
$imgFile = 'members/profile/'.$u[id].'.jpg';
if(file_exists($imgFile))
	echo '<p>Your profile picture already exists. Uploading will overwrite the current image.</p>
	
	<p><img src="'.$imgFile.'"></p>';
?>
<p><b>*Note:</b> You are responsible for the integrity of your images. Please do NOT upload
pornographic images or images of "questionable" content. If you do, your account may be
suspended.</p>
</div></div>

<br>
<center><a href="?action=viewProfile&id=<?=$u[id]?>">View public profile</a></center>