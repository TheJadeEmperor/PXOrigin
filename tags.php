<?
$tags = urldecode($_GET[tags]); 

$sel = 'select *, date_format(postedOn, "%m/%d/%Y") as postedOn 
from posts where tags like "%'.$tags.'%" order by postedOn desc';
$res = mysql_query($sel, $conn) or die(mysql_error()); 

$numResults = mysql_num_rows($res);
if($numResults > 0)
{
    while($p = mysql_fetch_assoc($res))
    {
        $search .= '<tr>
        <td><p><a href="?post='.$p[id].'">'.$p[subject].'</a> <br/>
        On '.$p[postedOn].'</p> <br/></td>
        </tr>';
    }
    $search = '<table>'.$search.'</table>';
}
else {
	$search = 'Nothing found for those tags, please try again. ';
}

?>
<p>&nbsp;</p>

<form method=get>
    <input type=text name=tags size=30 value="Type keywords here" onclick="this.value=''"/>
    <input type=submit name=go value=" GO "> 
</form>

<p>&nbsp;</p>

<h2>All Results for "<?=$_GET[tags]?>" (<?=$numResults?>)</h2>
<br/><br/>
<?=$search?>