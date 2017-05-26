<?php
include('include/settings.php');

function entities($content)
{
    $entity = array(
    '&' => '&amp;',
    ); 
    
    foreach($entity as $find => $replace)
    {
        $content = str_replace($find, $replace, $content);     
    }
    
    return $content; 
}

$string = '& <<';
//echo entities($string); 

header("Content-Type: application/rss+xml"); 
echo "<?xml version='1.0' encoding='UTF-8'?>
<rss version='2.0'
	xmlns:atom='http://www.w3.org/2005/Atom'
	xmlns:dc='http://purl.org/dc/elements/1.1/'
	xmlns:content='http://purl.org/rss/1.0/modules/content/'
	xmlns:admin='http://webns.net/mvcb/'
	xmlns:image='http://purl.org/rss/1.0/modules/image/'
	xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#'>\n";

	echo "   <channel>\n";
	echo "   <category>RPG</category>\n";		
	echo "   <language>en-us</language>\n";
	echo "   <title>PXO Posts Feed</title>\n";
	echo "   <description>Project Xeno Origin - RSS Feed - Subscribe to receive the latest updates from the gaming world</description>\n";
	echo "   <link>http://pxorigin.org</link>\n";
	echo "   <generator>Project Xeno Origin</generator>\n";

    echo "<copyright>Copyright PXO 2011-2013</copyright>\n";
	echo "   <image>\n";
	echo "   	<title>PXO</title>\n";
	echo "   	<link>http://pxorigin.org/images/banner1.jpg</link>\n";
	echo "   	<url>http://pxorigin.org/images/banner1.jpg</url>\n";
	echo "   </image>\n";
	
	
	$sel = 'select *, date_format(postedOn, "%m/%d/%Y %h:%m") as pubDate from posts 
    where status<>"I" order by id desc';
	$res = mysql_query($sel, $conn) or die(mysql_error()); 
	
    
	while($p = mysql_fetch_assoc($res))
	{
		$id = $p[id];
		$subject = stripslashes($p[subject]); 
		$post = stripslashes($p[post]);
        
        $subject = entities($subject);
        $post = entities($post); 
	
		echo "<item>
		<title>".$subject."</title>
		<category></category>
		<link>http://pxorigin.org/?post=".$p[url]."</link>
		<guid>http://pxorigin.org/?post=".$p[url]."</guid>
	 	<pubDate>".$p[pubDate]."</pubDate>
		<description><![CDATA[<table>
        <tr valign='top'>
          <td>
          <a href='http://pxorigin.org/?post=".$p[url]."'>
              <img src='http://pxorigin.org/posts/".$p[id]."/1.jpg' width='225px' height='140px'>
          </a>
          </td>
          <td>".shortenText($post, 150)."</td>
        </tr>
        </table>
        ]]></description>
		<image>http://pxorigin.org/posts/".$p[id]."/1.jpg</image>
		<keywords>".$subject."</keywords>
		</item>";  
	}
    
    /**/
	
	//close feed
	echo "</channel>\n";
	echo "</rss>\n";
?>