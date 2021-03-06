<?php
/* connect to database, returns resource */
function database($host, $user, $pw, $dbName)
{
	global $conn; 
	
	if(is_int(strpos(__FILE__, 'C:\\')))	//connect to database remotely (local server)
	{ 
		$conn = mysql_connect($host, $user, $pw) or die(mysql_error().' ('.__LINE__.')');
	}
	else //connect to database directly (live server)
	{
		$conn = mysql_connect('localhost', $user, $pw) or die(mysql_error().' ('.__LINE__.')');
	}
	
	mysql_select_db($dbName) or die(mysql_error());
	
	return $conn;
}

/*
 * $opt = array(
 * 	'tableName' => $tableName,
 * 	'dbFields' => array(
 * 		'fld' => $val)
 * )
 * */

function dbInsert($opt)
{
	global $context; 
	
	$fields = $values = array();
	foreach($opt[dbFields] as $fld => $val)
	{
		array_push($fields, $fld);
		
		if($val == 'now()') //mysql timestamp
			array_push($values, $val); 
		else
			array_push($values, '"'.addslashes($val).'"');
	}
	
	$theFields = implode(',', $fields);
	$theValues = implode(',', $values);
	
	$ins = 'insert into '.$opt[tableName].' ('.$theFields.') values ('.$theValues.')';
	return mysql_query($ins, $context[conn]) or die(mysql_error().' '.$ins);
}

/*
 * $opt = array(
 * 	'tableName' => $tableName,
 * 	'cond' => $cond)
 * */

function dbSelect($opt)
{
	global $context; 
	
	$sel = 'select * from '.$opt[tableName]; 
	
	if($opt[cond])
		$sel .= ' '.$opt[cond]; 
	
	$res = mysql_query($sel, $context[conn]) or die(mysql_error()); 
	
	while($rows = mysql_fetch_assoc($res))
	{
		foreach($rows as $fld => $val) 	//remove slashes 
		{
			$rows[$fld] = stripslashes($val);  
		}
		$mysql[] = $rows;		
	}
	
	return $mysql;
}


/*
 * $opt = array(
 * 	'tableName' => $tableName, 
 * 	'dbFields' => array(
 * 		'fld' => $val),
 * 	'cond' => $cond
 *  );
 */
function dbUpdate($opt)
{
	global $context; 
	
	if(!isset($opt[cond]))
		$opt[cond] = 'limit 1'; //prevent updating of all entries 
	
	$set = array(); 
	foreach($opt[dbFields] as $fld => $val)
	{
		array_push($set, $fld.'="'.addslashes($val).'"'); 
	}
	
	$theSet = implode(',', $set); 
	
	$upd = 'update '.$opt[tableName].' set '.$theSet.' '.$opt[cond]; 
	$u = mysql_query($upd, $context[conn]) or die(mysql_error().' '.$upd); 
	
	return $u;  
}


?>