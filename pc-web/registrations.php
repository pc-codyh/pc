<?php

$databaseHost = "68.178.139.40";
$databaseName = "pchitmanhart33";
$databaseUsername = "pchitmanhart33";
$databasePassword = "Cooors33!";

mysql_connect($databaseHost, $databaseUsername, $databasePassword) or die(mysql_error());
mysql_select_db($databaseName) or die(mysql_error()); 

$query = "SELECT * FROM `registrations` WHERE `username`='".$_POST['username']."'";

if ($runQuery = mysql_query($query))
{
	$numRows = mysql_num_rows($runQuery);
	
	if ($numRows == 0)
	{   
		mysql_query("INSERT INTO `registrations`(`username`,`password`) VALUES ('".$_POST['username']."','".$_POST['password']."')");
		
		echo 1;
	}
	else
	{
		echo 0;
	}
}

mysql_close();

?>