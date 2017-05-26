<?
if(isset($_SESSION[login]))
{
	$topMenu = '<ul class="topMenu">
	<li><a>Logged in as '.$_SESSION[login][username].'</a></li>
	<li><a href="?action=profile">Edit Profile</a></li>
	<li><a href="?action=viewProfile&id='.$_SESSION[login][id].'">View Public Profile</a></li>
	<li><a href="?action=logout">Logout</a></li></ul><br />';
}
else {
	$topMenu = '<ul class="topMenu">
	<li><a>Not logged in</a></li>
	<li><a href="?action=register"> Register </a></li>
    <li><a href="javascript:TINY.box.show({url:\'popuplogin.html\',width:300,height:160,openjs:\'initPopupLogin\',opacity:30})">Login</a></li>
    </ul><br />';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PXOrigin.org</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="PXO - Project Xeno Origin - Everything about RPG's" />
<meta name="keywords" content="pxo, project xeno origin, rpg games" />
<meta property="og:image" content="http://pxorigin.org/images/icon.png" />
<link rel="shortcut icon" type="image/png" href="http://pxorigin.org/images/icon.png">

<script type="text/javascript" src="include/popup.js"></script>
<script type="text/javascript" src="include/jquery.js"></script> 
<script type="text/javascript" src="include/jquery.nivo.slider.pack.js"></script>

<script type="text/javascript">var switchTo5x=true;</script><script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:'67a0d44d-0b79-44c1-bb19-7f31f475d6fe'});</script>

<script type="text/javascript">
//<![CDATA[
function popUp(url) {
	window.open(url, "PXO Image", "location=1,status=1,scrollbars=1,width=920,height=730");
}

$(window).load(function() {
    $('#slider').nivoSlider({
     pauseTime: 7000, // How long each slide will show
    }); 
}); 
//]]>
</script>
<link rel="stylesheet" href="default.css" type="text/css" media="screen" />
<link rel="stylesheet" href="include/css/popup.css" type="text/css" />
<link rel="stylesheet" href="include/css/pxo.css" type="text/css" />
</head>
<body>
<div class="topBar">
<div class="topContainer">
    <?=$topMenu?> 
    <br />
        <div class="slider-wrapper theme-default">
        <div class="ribbon"></div>
        <div id="slider" class="nivoSlider">
            <img src="images/banner1.jpg" alt="PXO Banner" />
            <img src="images/banner2.jpg" alt="PXO Banner" />
            <img src="images/banner3.jpg" alt="PXO Banner" />
        </div>
        <div id="htmlcaption" class="nivo-html-caption">
        </div>
    </div>
   
    <ul class="dropdown">
        <li class="itemGlow"><a href="<?=$dir?>?action=main"> Main </a></li>
        <li class="itemGlow"><a href="?action=aboutUs"> About Us </a></li> 
        <li class="itemGlow"><a href="?action=contact"> Contact </a></li> 
        <li class="itemGlow"><a href="?tags=search"> Search </a></li> 
       
        <li class="itemGlow"><a href="?action=register"> Register </a></li>
        <li class="itemGlow"><a href="javascript:TINY.box.show({url:'popuplogin.html',width:300,height:160,openjs:'initPopupLogin',opacity:30})">Login</a></li>
        
        <li>&nbsp;&nbsp;</li>
        <li>    
        <form action="" method=get>
            <input type=text name=tags size=27 value="Type keywords here" onclick="this.value=''"/>
            <input type=submit name=go value=" GO "> 
        </form>
        </li>

    </ul><!--dropdown-->
</div>
</div>
<div id="wrapper">
<div id="container">
<div class="mainContent">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td align="center">
    <center>
    <script type="text/javascript" src="http://adhitzads.com/456291"></script>
    </center>	
<!--	<ul class="dropdown">
		<li><a href="<?=$dir?>?action=main"> Main </a></li>
		<li><a href="?action=aboutUs"> About Us </a></li> 
		<li><a href="?action=contact"> Contact </a></li> 
		<li><a href="#"> Podcast </a>
			<ul>
				<li>Coming Soon...</li>
			</ul>
		</li> 
		<li>
			&nbsp;<a href="http://www.facebook.com/PXOrigin" target=_blank><img src="images/facebook.png" class="fade"></a> 
			&nbsp;
			<a href="https://twitter.com/#!/xoPXOox" target=_blank><img src="images/twitter.png"></a>
			&nbsp;
			<a href="http://www.youtube.com/user/PXOrigin?feature=mhee" target=_blank><img src="images/youtube.png" class="fade"></a>
			&nbsp;
			<a href="./rss.php" target=_blank><img src="images/rss.png" class="fade"></a> 
			&nbsp; 
		</li> 
		<li><a href="?action=register"> Register </a></li>
		<li><a href="javascript:TINY.box.show({url:'popuplogin.html',width:300,height:160,openjs:'initPopupLogin',opacity:30})">Login</a></li>
</ul>-->
</td></tr></table> 
<? 
$sel = 'select id, subject, url from posts where status<>"I" order by postedOn desc limit 4';
$res = mysql_query($sel, $conn); 

while($p = mysql_fetch_assoc($res))
{
    //featured posts box
	$content .= '<td>'.featuredPost($p[id]).'</td>'; 
    
    //footer links
    $latestPosts .= '<li><a href="./?post='.$p[url].'">&raquo; '.shortenText($p[subject], 20).'</a></li>';
} 

echo '<table class="featured" width="922px"><tr valign=top>'.$content.'</tr></table>';

function featuredPost($id)
{
	global $conn; 
	$selP = 'select * from posts where id="'.$id.'"';
	$resP = mysql_query($selP, $conn); 
	
	$p = mysql_fetch_assoc($resP);
	$p[subject] = stripslashes($p[subject]);
	
	$selC = 'select count(*) from comments where replyID="'.$id.'"';
	$c = mysql_fetch_assoc( mysql_query($selC, $conn) );
	
	$count = $c['count(*)'];
	
	$content = '<div class="featuredPost" style="background-image: url(posts/'.$id.'/1.jpg)">
	<a href="./?post='.$p[url].'">
	<div style="height: 105px;"></div>
	<div class="title">'.shortenText($p[subject], 50).'</div></a>
	</div>';
	
	return $content; 
}

?>
<center>
<table width="922px">
<tr valign="top"><td align="left" width="650px">  