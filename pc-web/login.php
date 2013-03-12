<?php

$databaseHost = "68.178.139.40";
$databaseName = "pchitmanhart33";
$databaseUsername = "pchitmanhart33";
$databasePassword = "Cooors33!";

mysql_connect($databaseHost, $databaseUsername, $databasePassword) or die(mysql_error());
mysql_select_db($databaseName) or die(mysql_error()); 

$hashed_password = md5($_GET['password']);

$query = "SELECT * FROM `registrations` WHERE `username`='".$_GET['username']."' AND `password`='".$hashed_password."'";

if ($runQuery = mysql_query($query))
{
	$numRows = mysql_num_rows($runQuery);
	
	if ($numRows == 1)
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