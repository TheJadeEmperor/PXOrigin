<?

if($_POST[addComment])
{
    if($_POST[antispam] == 'yes' || $_POST[antispam] == 'YES')
    {
        $dbFields = array(
        'replyID' => '"'.$id.'"',
        'post' => '"'.addslashes($_POST[post]).'"',
        'postedBy' => '"'.$_SESSION[login][id].'"',
        'postedOn' => 'now()');
        
        $fields = $values = array();

        foreach($dbFields as $fld => $val)
        {
            array_push($fields, $fld);
            array_push($values, $val);
        }
        
        $theFields = implode(',', $fields);
        $theValues = implode(',', $values);
        
        $ins = 'insert into comments ('.$theFields.') values ('.$theValues.')';
        $res = mysql_query($ins, $conn) or die(mysql_error());
        
        $msg = 'Comment succesfully posted';
    }
    else
    {
        $msg = 'Please answer the anti-spam question correctly';
    }
    
    $msg = '<font color=red>'.$msg.'</font>';
}

if($_GET[post])
{
    $id = $_GET[post];
    
}
else {
    $id = $_GET[p];
}

if(is_numeric($id)) //post #
{
    $cond = 'p.id="'.$id.'"';
}
else {
    $cond = 'p.url="'.$id.'"';
}


//general settings
$websiteURL = 'http://www.pxorigin.org/';

//get post details
$sel = 'select *, date_format(postedOn, "%m/%d/%Y %h:%i %p") as postedOn, p.id as id, u.id as uid 
from posts p left join users u on p.postedBy = u.id 
where '.$cond.' limit 1';
$res = mysql_query($sel, $conn) or die(mysql_error());

$p = mysql_fetch_assoc($res);
    
$id = $p[id]; 
$p[post] = stripslashes($p[post]);
$subject = $p[subject] = stripslashes($p[subject]);
$postLink = $websiteURL.'?post='.$p[url];

if($p[subject]=='')
    $p[subject] = '[No Subject]';

if($p[post]=='')
    $p[post] = '[No content]'; 
    
$keywords = explode(', ', $p[tags]);
$tagArray = array();

foreach($keywords as $piece)
{
    array_push($tagArray, '<b><a href="./?tags='.urlencode($piece).'" title="'.$piece.'">'.$piece.'</a></b>'); 
}
    
$tags = implode(', ', $tagArray); 

$contentPost = '<br><a href="'.$postLink.'" class="postTitle" title="'.$p[subject].'">
<h2>'.$p[subject].'</h2></a>
<p>By <a href="?action=viewProfile&id='.$p[uid].'">'.$p[username].'</a> on '.$p[postedOn].'</p>
<br /> 

<div style="float: left;"><a href="http://twitter.com/share" class="twitter-share-button" 
data-url="'.$postLink.'" data-count="horizontal" data-via="xoPXOox - '.$subject.'">Tweet</a>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>

<div style="float: left; margin: 0; width: 76px; overflow: hidden;"><iframe src="http://www.facebook.com/plugins/like.php?href='.$postLink.'&amp;layout=button_count&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px; width: 150px;" allowTransparency="true"></iframe></div>
    
<p>&nbsp;</p> <p>&nbsp;</p>';
   
//show comments
$selC = 'select *, date_format(c.postedOn, "%m/%d/%Y %h:%i %p") as postedOn from comments 
c left join users u on c.postedBy=u.id where replyID="'.$id.'"';
$resC = mysql_query($selC, $conn) or die(mysql_error());

$num = mysql_num_rows($resC); 

while($c = mysql_fetch_assoc($resC))
{
	$c[post] = stripslashes($c[post]);
	$c[subject] = stripslashes($c[subject]);

	$contentComments .= '<div style="border: 1px solid gray; padding: 10px; background-color: #efefef;">'.$c[post].'
	<div style="text-align: right">By <a href="#">'.$c[username].'</a> on '.$c[postedOn].'</div></div>
	<br><br>';
}

$contentPost .= $p[post].'<p>&nbsp;</p> <p>&nbsp;</p> 
    
    <table width="100%" id="comments">
    <tr valign="top">
    <td align="left"><p>Tags: '.$tags.' </p></td>
    <td align="right" width="150px"><p>Comments ('.$num.') </p></td>
    </tr>
    </table> <br><hr><p>&nbsp;</p>'; 

echo $contentPost; 

echo $contentComments;

//if logged in, add comments
if(isset($_SESSION[login]))
{
	echo '<p>'.$msg.'</p><p>Add comment as '.$_SESSION[login][username].'</p><form method=post>
	
	<textarea name=post rows=7 cols=50></textarea>
	<br>
	Are you human? (type yes)<input name=antispam class="activeField">
	<br>
	<center><input type=submit name=addComment value="Add Comment"></center>
	</form>';
}
else
{
	echo '<p>You must be logged in to add comments</p>';
}
?>