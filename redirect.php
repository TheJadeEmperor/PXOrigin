<?php
switch($_GET[action])
{
	case 'easyHits':
		$url = 'http://a.easyhits4u.com/splash8.php?ref=theemperor';
		$name = 'EasyHits4U.com';
		break;
	case 'trafficWitch':
		$url = 'http://www.trafficwitch.com/splash/splash3.php?rid=57565';
		$name = 'TrafficWatch';
		break;
	case 'whirlwind':
		$url = 'http://www.whirlwindtraffic.com/?rid=11720';
		$name = 'Whirlwind Traffic';
		break;
	default:
	case 'trafficwave':
		$url = 'http://www.trafficwave.net/members/theemperor';
		$name = 'TrafficWave';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="5;url=<?=$url?>">
</head>
<body>

<center>
<table width=420px height="100%" cellpadding=5>
<tr valign=middle>
<td>
	<table width=420px cellpadding=5 style='border: 1px solid black; font-size: 12px;'>
	<tr><td align=center>
		<font face=verdana>
		<p><b>** Redirecting you to <?=$name?> **</b></p>
		<p>Please wait...</p>
		<img src="images/waiting.gif" alt="Waiting">
		</font>
	</td></tr>
	</table>
</td>
</tr>
</table>
</center>

</body></html>