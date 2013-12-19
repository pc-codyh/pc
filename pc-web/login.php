<?php

$databaseHost = "68.178.139.40";
$databaseName = "pchitmanhart33";
$databaseUsername = "pchitmanhart33";
$databasePassword = "Cooors33!";

mysql_connect($databaseHost, $databaseUsername, $databasePassword) or die(mysql_error());
mysql_select_db($databaseName) or die(mysql_error()); 

$query = "SELECT * FROM `registrations` WHERE `username`='".$_GET['username']."' AND `password`='".$_GET['password']."'";

if ($runQuery = mysql_query($query))
{
	$numRows = mysql_num_rows($runQuery);
	
	// Make sure password is case sensitive as well.
	if ($numRows == 1 && strcmp(mysql_result($runQuery, 0, 'password'), $_GET['password']) == 0)
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}

mysql_close();

?>