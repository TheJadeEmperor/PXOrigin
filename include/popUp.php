<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PXO Gallery</title>
</head>
<body>
<table>
<tr valign=middle>
<td>
<?
$dir = '../';

if ($handle = opendir($dir.'images/gallery'))	//read all images in directory
{ 	
	while (false !== ($file = readdir($handle)))
	{
		if($file != 'Thumbs.db' && $file != ".." && $file != "." && $file != $_GET[file])
			$count ++;
	}
closedir($handle);
}


list($path1, $path2, $thisImg) = explode('/', $_GET[file]);
$n = $thisImg[1];

$p = $n - 1;
$prevImg = substr_replace($thisImg, $p, 1, 1);
$prevImg = $path1.'/'.$path2.'/'.$prevImg;

if($p > 0)
{
	list($widthB, $heightB, $typeB, $attrB) = getimagesize($dir.$prevImg); 
//echo $prevImg;
	$popUp = "javascript:TINY.box.show({url:'include/popUp.php?file=".$prevImg."',width:".($widthB+80).",height:".$heightB."+10,openjs:'initPopupLogin',opacity:30})";
	echo '<a href="'.$popUp.'"><img src="images/arrowL.jpg"></a>';
}
 ?>
</td><td>
<?
list($width, $height, $type, $attr) = getimagesize($dir.$_GET[file]); 

if($height > 600)  
{
	$height = '600'; 
}

echo '<img src="'.$_GET[file].'" alt="PXO Image" height="'.$height.'">';
?>
</td><td>
<?
$p = $n + 1;
$nextImg = substr_replace($thisImg, $p, 1, 1);
$nextImg = $path1.'/'.$path2.'/'.$nextImg;

if($p < $count)
{
	list($widthB, $heightB, $typeB, $attrB) = getimagesize($dir.$nextImg); 

	$popUp = "javascript:TINY.box.show({url:'include/popUp.php?file=".$nextImg."',width:".($widthB+80).",height: 620,openjs:'initPopupLogin',opacity:30})";
	echo '<a href="'.$popUp.'"><img src="images/arrowR.jpg"></a>';
}
?>
</td>
</tr>
</table>				 
</body>
</html>