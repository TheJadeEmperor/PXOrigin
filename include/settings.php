<?
include($dir.'include/functions.php'); 
include($dir.'include/class.phpmailer.php');
include($dir.'include/class.smtp.php'); 
include($dir.'include/mysql.php');

//database info goes here
//////////////////////////////
$dbHost = '74.220.215.54';
$dbUser = 'pxorigin_root';
$dbPW = 'Triforce75*';
$dbName = 'pxorigin_home';
/////////////////////////////

$conn = database($dbHost, $dbUser, $dbPW, $dbName);


$host = "mail.pxorigin.org"; // SMTP host
$username = "admin@pxorigin.org"; //SMTP username
$password = "1a1a1a1a"; // SMTP password
$fromName = 'PXO';
$fromEmail = $username;

$selS = 'select * from settings order by opt';
$resS = mysql_query($selS, $conn) or die(mysql_error());

while($s = mysql_fetch_assoc($resS))
{
    $val[$s[opt]] = $s[setting];
}

$prelaunch = false;
$prelaunchDate = '';
$adminEmail = $val[adminEmail];
$websiteName = 'PXOrigin.com';
$websiteURL = 'http://www.pxorigin.org';
$businessName = 'PXOrigin (PXO)';


//weekly backups
//backup options
$dayOfWeek = '0'; //day of week to backup 
$backupDir = '.backup';

if( date('w', time()) == $dayOfWeek )
{
    $backupFile = date('Y-m-d', time()).'.sql';
    $dump = 'mysqldump -u'.$dbUser.' -p'.$dbPW.' '.$dbName.' > ./'.$backupDir.'/'.$backupFile;

    system($dump); 
} 
?>