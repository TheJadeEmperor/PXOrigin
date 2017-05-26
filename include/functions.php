<?php
/* ##############################################################
showPost($id)
	show the post contents, given the post's id

formatNewsletter($n)
	properly format newsletter after retrieving it from database

showMenu($menu)
	display the admin menu
	
embedYoutube($)
	displays embedded youtube video
	
database()

################################################################*/


function showPost($id)
{
	global $conn; 
	
	//general settings
	$websiteURL = 'http://www.pxorigin.org/';
	
	//get post details
	$sel = 'select *, date_format(postedOn, "%m/%d/%Y %h:%i %p") as postedOn, p.id as id, u.id as uid 
	from posts p left join users u on p.postedBy = u.id 
	where p.id="'.$id.'" limit 1';
	$res = mysql_query($sel, $conn) or die(mysql_error());
	
	$p = mysql_fetch_assoc($res);
	
	$id = $p[id]; 
	$p[post] = stripslashes($p[post]);
	$p[subject] = stripslashes($p[subject]);
	$postLink = $websiteURL.'?post='.$p[id];
	
	if($p[subject]=='')
		$p[subject] = '[No Subject]';
	
	if($p[post]=='')
		$p[post] = '[No content]'; //
		
	echo '<br><a href="./?post='.$id.'" class="postTitle" title="'.$p[subject].'">
	<h2>'.$p[subject].'</h2></a>
	<p>By <a href="?action=viewProfile&id='.$p[uid].'">'.$p[username].'</a> on '.$p[postedOn].'</p>
	<br>
	<div style="float: left;"><a href="http://twitter.com/share" class="twitter-share-button" 
	data-url="'.$postLink.'" data-count="horizontal" data-via="xoPXOox - '.$p[subject].'">Tweet</a>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div> 

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=249692761719598";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>

<iframe src="http://www.facebook.com/plugins/like.php?app_id=249692761719598&amp;href='.$postLink.'&amp;send=false&amp;layout=button_count&amp;width=80&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&ref=topmenu" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:21px;" allowTransparency="true"></iframe>';

        echo "<p>&nbsp;</p>
    <span class='st_twitter_vcount' displayText='Tweet'></span><span  class='st_email_vcount' 
    displayText='Email'></span><span  class='st_facebook_vcount' displayText='Facebook'></span>
    <span  class='st_sharethis_vcount' displayText='ShareThis'></span>";
    
    echo '<span class="st_fblike_vcount" st_title="'.$subject.'" st_url="'.$postLink.'" displayText="share"></span>

	<p>&nbsp;</p>
	<p>&nbsp;</p>'; 
	
	echo $p[post].'<br> 
	
<table width="100%" id="comments">
    <tr valign="top">
    <td align="left"><p>Tags: '.$tags.' </p></td>
    <td align="right" width="150px"><p>Comments ('.$num.') </p></td>
    </tr>
    </table> <hr><p>&nbsp;</p>'; 
}


function showMenu($menu)
{
	$menuContent = '<div class="adminMenu" title="'.$menu[bar][title].'">
	<a href="'.$menu[bar][link].'"><h2>'.$menu[bar][title].'</h2></a><ul id="menu">';
	
	foreach($menu[item] as $name => $value)
	{
		$menuContent .= '<li><a href="'.$value[link].'" title="'.$value[title].'" '.$value[extra].'>'.$name.'</a>';

		if(sizeof($value[sub_menu]) > 0)
		{
			$menuContent .= '<ul>';
			foreach($value[sub_menu] as $sub => $val)
			{
				$menuContent .= '<li><a href="'.$val[link].'" title="'.$val[title].'" '.$val[extra].'>
				:: '.$sub.' ::</a></li>';
			}//foreach
			$menuContent .= '</ul>';
		}//if
		$menuContent .= '</li>';
	}//foreach
	return $menuContent.'</ul></div>';
}//function


function embedYoutube($src, $width, $height)
{
	return '<object width="'.$width.'" height="'.$height.'"><param name="movie" 
	value="http://www.youtube.com/v/'.$src.'=en_US&fs=1&"></param>
	<param name="allowFullScreen" value="true"></param>
	<param name="allowscriptaccess" value="always"></param>
	<embed src="http://www.youtube.com/v/'.$src.'&hl=en_US&fs=1&" type="application/x-shockwave-flash" 
	allowscriptaccess="always" allowfullscreen="true" width="'.$width.'" height="'.$height.'"></embed></object>';
}


function shortenText($text, $limit)
{
	//$limit = number of characters you want to display
	$new = $text.' ';
	$new = substr($new, 0, $limit);
	
	if(strlen($text) > $limit)
		$new = $new.'...';
	return $new;
}//function


function randomChar()
{
	$letters = array(1 => "a", "b", "c", "d", "e", "f", "g", "h" ,"i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
	"A", "B", "C", "D", "E", "F", "G", "H" ,"I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
	"0","1","2","3","4","5","6","7","8","9");
	$index = Key($letters);
	$element = Current($letters);
	$index = rand(1,62);
	$random_letter = $letters[$index];
	return $random_letter;
}


function genString()
{
	//create random hash
	$number = 5;
	for ($i = 0; $i < $number; $i++)
	{
	    $hash = $hash.(randomChar());
	}
	return $hash;
}


function sendWelcomeEmail($conn)
{
	$sel = 'select * from settings';
	$res = mysql_query($sel, $conn) or die(mysql_error());
	
	while($s = mysql_fetch_assoc($res))
	{
		switch($s[opt])
		{
		case 'adminEmail':
			$adminEmail = $s[setting];
			break;
		case 'websiteURL':
			$websiteURL = $s[setting];
			break; 
		case 'businessName':
			$businessName = $s[setting];
			break;
		case 'websiteName':
			$websiteName = $s[setting];
			break;
			}
	}
	 
	$businessName = 'PXO';
	$websiteURL = 'http://pxorigin.org'; 
	
	$headers = "From: ".$adminEmail."\n";
	$headers .= "Content-type: text/html;";		
	$subject = "Welcome to ".$businessName;
	$theMessage = "<p>Dear ".$_POST[fname].", </p> 
	
	<p>Thank you for signing up for a free membership to ".$businessName.". 
	Below are your account details: </p>
	
	<p>Your account details are: <br>
	Username: ".$_POST[username]."<br>
	Password: ".$_POST[password]."<br>
	Email: ".$_POST[email]."  </p>
	
	<p>Keep this email for future reference. Never share your password with anybody. </p>
	
	<p>".$_POST[fname].", we're excited to have you on board. Please visit us often as we update our
	site frequently. To login to your account, simply visit us at the link below: 
	<a href='".$websiteURL."'>".$websiteURL."</a></p>";
	
	mail($_POST[email], $subject, $theMessage, $headers);
	mail($adminEmail, $subject, $theMessage, $headers);
}


function FileName($dir)//gets the full directory and returns the file name
{
	$slash = strrpos($dir, '/'); //find the last slash in the directory

	if($slash == false)//if unable to find the forward slash
		$slash = strrpos($dir, '\\');//find the backslash (for localhost)

 	return substr($dir, $slash + 1, strlen($dir));//get the file name
}//function


function formatNewsletter($n)
{
	$fields = array('subject', 'message');
	
	foreach($fields as $fld)
	{
		$n[$fld] = stripslashes($n[$fld]);
	}
	
	return $n;
}

session_start();

//settings
global $conn; 

?>