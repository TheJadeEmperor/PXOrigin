<?php
/*gallery.php
Displays a gallery of images based on hoverbox.css
Requirements:
	A js function called popUp and a file called popUp.php	
	Two directories of images, called big and small
		small - thumbnails of images
		big - actual size of images
	Small and big folders must have matching image files
	Files must be named (1).ext, (2).ext, and so on...
	$directory - pass in the directory relative to popUp.php (not the file itself)
*/

//display page numbers at top and bottom
function pageNumber($numPages, $n, $page)
{
	//num_pages = total number of pages
	//n = number of images
	//page = current page number
	
	if($page == '')	$page = 1;	//declare first page
	
	//Go to first page
	$pageNumbers .= '<a href="#" title="First Page"> << First </a>';
	
	//Go to previous page
	if($page == 1)
		$pageNumbers .= '<< Prev ';//nothing before first page
	else
		$pageNumbers .= '<a href="#" title="Previous Page"><< Prev</a> ';

	for($p = 1; $p <= $numPages; $p++)//$p = iterator
	{
		if($page == $p)	//current page
			$pageNumbers .= '<a href="#" title="Page '.$p.'">
			<font size=5><b>'.$p.'</b></font></a> ';
		else	//not current page
			$pageNumbers .= '<a href="#" title="Page '.$p.'">'.$p.'</a> ';
	}//for
	
	//Go to next page
	if($page == $numPages)
		$pageNumbers .= ' Next >>';//nothing after last page
	else
		$pageNumbers .= ' <a href="#" title="Next Page">Next >></a>';
	//last page
	$pageNumbers .= ' <a href="#" title="Last Page"> Last >> </a>';
	
	return $pageNumbers;
}//function


function gallery($directory)//
{	 
	global $context; 
	$dir = $context[dir];
	$altText = 'PXO Image'; 
	
	$s = 1;	//small images
	if ($handle = opendir($directory.'/small'))	//read all files in directory
	{ 	
		//List all the files
		while (false !== ($file = readdir($handle)))
		{
			if($file != 'Thumbs.db' && $file != '..' && $file != '.')
			{	
				$small[$s] = $file;
				$s++;				//increment counter
			}
		}//while
		closedir($handle);
	}//if
	
	if($_POST[num_images])
	{
		$_GET[n] = $_POST[num_images];
		$sel[ $_GET[n] ] = 'selected';
	}
	else if($_GET[n])
	{	
		$sel[ $_GET[n] ] = 'selected';
	}
	else
	{
		$sel[100] = 'selected';	//default images per page
		$_GET[n] = 100;
	}//

 
	//show drop down list and signature
	$galleryContent .= '<div class="moduleBlue" id="gallery"><h1>User Content | Image Gallery</h1>
	<table>
	<tr valign="top">
		<td>
			<form method="post">
			Images per page: <select name="num_images" onchange="submit();">
			<option value="10" '.$sel[10].'>10</option>
			<option value="20" '.$sel[20].'>20</option>
			<option value="50" '.$sel[50].'>50</option>
			<option value="70" '.$sel[70].'>70</option>
			<option value="100" '.$sel[100].'>100</option>
			</select>
			</form>
		</td><td width="10px"></td>
		<td>Click on thumbnail to view full sized image in new window.<br>
		Click on the image again to close the window.</td>
	</tr>
	</table>';

	//total images
	$total = $s - 1;
	$num_pages = 1;

	if($_GET[n] > 0)	//determine number of pages
		$num_pages = ceil($total/$_GET[n]);
	else
		$_GET[n] = $total;//one page

	$galleryContent .= '<center>'.pageNumber($num_pages, $_GET[n], $_GET[p]).'<br><br><br>
	
	<table><tr valign="top"><td><ul class="hoverbox">';
	
	if($_GET[p] == '')//first page
	{	//first and last image of each page
		$first = 1;
		$last = $_GET[n];

		if($last > $total)
			$last = $total;
	}//if
	else	//all other pages
	{	//first and last image of each page
		$first =  ($_GET[n] * ($_GET[p] - 1)) + 1; 
		$last = $_GET[n] * $_GET[p]; 	
		if($last > $total)
			$last = $total;
	}//else
	
	for($w = $first; $w <= $last; $w++)
	{	
		foreach($small as $thumbnail)
		{
			list($name, $ext) = explode('.', $thumbnail); 
			
			$imgDir = str_replace('../', '', $directory);
					
			if($name.'.'.$ext == "($w).jpg" || $name.'.'.$ext == "($w).png")
			{				
				$thisImg = $directory.'/small/'.$thumbnail;
				$picture = $directory.'/'.$thumbnail;
				
				list($widthT, $heightT, $typeT, $attrT) = getimagesize($thisImg); 
				list($widthB, $heightB, $typeB, $attrB) = getimagesize($picture); 
				
				if($heightT > $widthT)
					$class = 'tall';
				else
					$class = 'preview'; 
				
				$popUp = "javascript:TINY.box.show({url:'include/popUp.php?file=".$picture."',width:".($widthB+80).",height:".$heightB."+10,openjs:'initPopupLogin',opacity:30})";
				
				$galleryContent .= '<li><a href="'.$popUp.'">
				<img src="'.$thisImg.'" alt="'.$altText.'" title="'.$altText.'">
				<img src="'.$thisImg.'" class="'.$class.'" alt="'.$altText.'" title="'.$altText.'">
				</a></li>';	
			}			
		}
	}
	
	$galleryContent .= '</ul></td>
	</tr></table><br>'.pageNumber($num_pages, $_GET[n], $_GET[p]).'<br><br>
	Gallery script developed by <a href="mailto:louie.benjamin@gmail.com&subject=Website Gallery" 
	title="BL Web Solution">BL Websolutions</a></center><br></div>';
	
	return $galleryContent;
} 
?>