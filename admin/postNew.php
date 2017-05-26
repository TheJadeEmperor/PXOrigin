<?
include('adminCode.php'); 


if($_GET[id]) //image directory
	$postDir = '../posts/'.$_GET[id].'/';

if($handle = opendir($postDir)) //read all images
{
	while (false !== ($file = readdir($handle)))	//list all the files
	{
		if($file != 'Thumbs.db' && $file != '..' && $file != '.')
		{
			$count++;
		}
	}
}


if($_POST[save])
{
	//insert into db 
	$dbFields = array(
	'subject' => '"'.addslashes($_POST[subject]).'"', 
	'postedBy' => '"'.$_SESSION[login][id].'"',
	'post' => '"'.addslashes($_POST[elm1]).'"',
	'postedOn' => 'now()', 
	'tags' => '"'.addslashes($_POST[tags]).'"', 
	'url' => '"'.addslashes($_POST[url]).'"', 
	'status' => '"'.$_POST[status].'"'
	); 

	$fields = $values = array();

	foreach($dbFields as $fld => $val)
	{
		array_push($fields, $fld);
		array_push($values, $val); 
	}

	$theFields = implode(',', $fields);
	$theValues = implode(',', $values); 

	$ins = 'insert into posts ('.$theFields.') values ('.$theValues.') ';
	$res = mysql_query($ins, $conn); 
    
    //directory for images of this post
    mkdir($postDir);
	
	header('Location: postNew.php?id='.mysql_insert_id());
}
else if($_POST[update])
{
	$dbFields = array(
	'subject' => '"'.addslashes($_POST[subject]).'"', 
	'post' => '"'.addslashes($_POST[elm1]).'"',
	'tags' => '"'.addslashes($_POST[tags]).'"', 
	'url' => '"'.$_POST[url].'"',
	'status' => '"'.$_POST[status].'"'
	);
	
	$theSet = $set = array(); 
	
	foreach($dbFields as $fld => $val)
	{
		array_push($set, $fld.'='.$val);
	}
	
	$theSet = implode(',', $set);
	
	$upd = 'update posts set '.$theSet.' where id='.$_GET[id];
	$resU = mysql_query($upd, $conn) or die(mysql_error());
	
	if($_POST[replace])
		echo $_POST[replace];
	
	//handle uploaded files
	$key = 0;
	$tmp_name = $_FILES["userfile"]["tmp_name"][$key];
	$name = $_FILES["userfile"]["name"][$key];
	
	if($_POST[replace])
		$name = $_POST[replace];
	else
		$name = ($count+1).'.jpg';
	
	if(!file_exists($postDir))
		mkdir($postDir);
	
	$sendfile = "../posts/".$_GET[id]."/$name";
	move_uploaded_file($tmp_name, $sendfile);
}

if($_GET[id])
{
	$id = $_GET[id];

	$sel = 'select * from posts where id='.$id;
	$res = mysql_query($sel, $conn) or die(mysql_error());
	
	$p = mysql_fetch_assoc($res);
	$p[post] = stripslashes($p[post]);
	$p[subject] = stripslashes($p[subject]); 
	$active[$p[status]] = 'selected';
	
	$disAdd = 'disabled';
}
else
	$disEdit = 'disabled';

?>
<!-- TinyMCE -->
<script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,spellchecker",

		// Theme options
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen, spellchecker",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

        spellchecker_languages : "+English=en,Swedish=sv",
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
</script><!-- /TinyMCE -->
</head>
<body>
    
<div class="moduleBlue"><h1>Links</h1>
<div>       
    <a href="postAll.php">All Posts</a>
</div>
</div>

    
<form method="post" enctype="multipart/form-data">
<div class="moduleBlue"><h1>Add / Edit Post</h1>
<div>		
	<table>
	<tr>	
		<td> Subject: </td>
		<td><input class="activeField" name=subject size=80 value="<?=$p[subject]?>"></td>
	</tr>
	<tr>
		<td> Tags: </td>
		<td><input class="activeField" name=tags size=80 value="<?=$p[tags]?>"></td>
	</tr>
	<tr>
        <td> URL: </td>
        <td><input class="activeField" name=url size=80 value="<?=$p[url]?>"></td>
    </tr>
    <tr>
		<td> Active: </td>
		<td><select name=status>
			<option <?=$active['']?> value="">Yes</option>
			<option <?=$active['I']?> value="I">No</option> </td>
	</tr>
	</table>
	 
	<textarea id="elm1" name="elm1" rows="30" cols="80">
	<?=$p[post]?>
	</textarea><br>
 
	<!-- Some integration calls -->
	<a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show]</a>
	<a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide]</a>
	<a href="javascript:;" onmousedown="alert(tinyMCE.get('elm1').getContent());">[Get contents]</a>
	<a href="javascript:;" onmousedown="alert(tinyMCE.get('elm1').selection.getContent());">[Get selected HTML]</a>
	<a href="javascript:;" onmousedown="alert(tinyMCE.get('elm1').selection.getContent({format : 'text'}));">[Get selected text]</a>
	<a href="javascript:;" onmousedown="alert(tinyMCE.get('elm1').selection.getNode().nodeName);">[Get selected element]</a>
	<a href="javascript:;" onmousedown="tinyMCE.execCommand('mceInsertContent',false,'<b>Hello world!!</b>');">[Insert HTML]</a>
	<a href="javascript:;" onmousedown="tinyMCE.execCommand('mceReplaceContent',false,'<b>{$selection}</b>');">[Replace selection]</a>

	<br /><br />
	<center>
	<input type="submit" <?=$disAdd?> name="save" value=" New Post " />
	<input type="submit" <?=$disEdit?> name="update" value=" Save Post " />
	</center>
	
	<div style="padding:10px;">
	<p>Attach an image</p>
	<p><input type=file <?=$disEdit?> name="userfile[]" class="activeField" size=30> &nbsp;
	<br>Max file size: 4 MB</p>
	
	Current images <br>
	<?
	if($_GET[id])
	$postDir = '../posts/'.$_GET[id].'/';

	if($handle = opendir($postDir))
	{
		while (false !== ($file = readdir($handle)))	//List all the files
		{
			if($file != 'Thumbs.db' && $file != '..' && $file != '.')
			{
				$imgTable .= '<tr><td><a href="'.$postDir.$file.'" target=_blank>'.$file.'</a></td>
				<td>'.$websiteURL.'/posts/'.$id.'/'.$file.'</td>
				<td align="center"><input type=checkbox name=replace value="'.$file.'"></td></tr>';
			}
		}
		
		echo $imgTable = '<table class=moduleBlue cellspacing=0><tr>
		<th>Image File</th>
		<th>Image URL</th>
		<th>Replace Image?</th>
		</tr>'.$imgTable.'</table>';
	}
	?>
	
	</div>
</div>
</div>
</form>