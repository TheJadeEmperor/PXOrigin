<?php
include('adminCode.php');
 
$saveThis = array('perPage', 'search');

foreach($saveThis as $field)
{
	if($_POST[$field] && $_POST[$field] != $_SESSION[$field])
		$_SESSION[$field] = $_POST[$field];
}

if($_GET[p])
	$currentPage = $_GET[p];
else
	$currentPage = 1;


$fieldList = array(
'fname', 
'lname', 
'email',
'paypal',
'membership',
'username' );

if($_SESSION[search])
{
	foreach($fieldList as $fld)
	{
		if($_POST[$fld] != '')
		{
			$selS .= $fld.'="'.$_POST[$fld].'" and ';
		}
	}

	if($_POST[before] == '')
		$_POST[before] = date('Y-m-d', time());
		
	if($_POST[after] == '')
		$_POST[after] = '2011-01-01';
	
	$selS .= ' joinDate >= "'.$_POST[after].'" and joinDate <= "'.$_POST[before].'"';

	$selS = 'select * from users where '.$selS;
	 
 	$resS = mysql_query($selS, $conn) or die(mysql_error());
	
 	$records = mysql_num_rows($resS);
 	
// 	echo $records; 
 	
 	if($records > 0)
 	{
 		unset($_SESSION[sendTo]);
 		$_SESSION[sendTo] = array();
 		
		$total = $records; //total # of records
		$listCount = $count = 1;
		$perPage = 100;
		
		while($c = mysql_fetch_assoc($resS))
		{
			if($count % $perPage == 0)
				$listCount ++;
		
			$cust[$listCount] .= '<tr><td><a href="updateProfile.php?id='.$c[id].'">'.$c[id].'</a></td>
			<td>'.$c[joinDate].'</td><td>'.$c[username].'</td>
			<td>'.$c[email].'</a></td><td>'.$c[fname].'</td></tr>';
					
			$count ++;
			
			array_push($_SESSION[sendTo], $c[email]); 
		}
		
		//calculate page numbers
		$numPages = ceil($total / $perPage);

		for($p = 1; $p <= $numPages; $p++)
		{
			if($currentPage == $p)
				$pages .= '<font size=3><a href="?p='.$p.'">'.$p.'</a></font> ';
			else
				$pages .= '<a href="?p='.$p.'">'.$p.'</a> ';
		}
		
		//calculate prev page
		if($currentPage == 1)
			$prev = 1;
		else
			$prev = $currentPage - 1;
			
		//calculate next page
		if($currentPage == $numPages)
			$next = $numPages;
		else
			$next = $currentPage + 1;

		$pages = '<tr><td colspan=4 align=center><a href="?p='.$prev.'"><< Prev</a> 
		'.$pages.' <a href="?p='.$next.'">Next >></a></td></tr>';

		
		$salesTable = '<table class=thelist cellspacing=0>
		<tr>
			<th>#</th><th>joinDate</th>
			<th>username</th>
			<th>email</th>
			<th>fname</th>
		</tr>
		'.$pages.' '.$cust[$currentPage].' '.$pages.'
		<tr>
			<td colspan=5><a href="sendEmail.php"><input type=button value="Email All"></a></td>
		</tr></table>';
		
	 	$searchText = 'Search results: found '.$records.' records';
 	}
 	else
 	{
 		$searchText = 'Search found '.$records.' records. Please try again.';
 	}

}


 
?>

<form method=POST>
<table class="thelist">
<tr>
	<th colspan=3>Search Critera</th>
</tr><tr>
	<td>fname</td><td><input type=text name=fname value="<?=$_POST[fname]?>"></td>
</tr><tr>
	<td>lname</td><td><input type=text name=lname value="<?=$_POST[lname]?>"></td>
</tr><tr>
	<td>username</td><td><input type=text name=username value="<?=$_POST[username]?>"></td>
</tr><tr>
	<td>email </td><td><input type=text name=email value="<?=$_POST[email]?>"></td>
</tr><tr>
	<td>joinDate </td><td>
	Before <input type=text name=before value="<?=$_POST[before]?>"><br>
	After &nbsp; <input type=text name=after value="<?=$_POST[after]?>">
	</td>
</tr><tr>
	<td align=center colspan=2><input type=submit name=search value="Search"></td>
</tr>
</form>
</table>

<br><br>

<?
echo $subOpt; 

if($_SESSION[search])
{	
	echo '<font size=3>'.$searchText.'</font><br><br>'; 

	echo $salesTable;
}

	
?>