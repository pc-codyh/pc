<?php

$databaseHost = "68.178.139.40";
$databaseName = "pchitmanhart33";
$databaseUsername = "pchitmanhart33";
$databasePassword = "Cooors33!";

mysql_connect($databaseHost, $databaseUsername, $databasePassword) or die(mysql_error());
mysql_select_db($databaseName) or die(mysql_error());

$queryID = "SELECT `id` FROM `registrations` WHERE `username`='".$_GET['username']."'";

if ($runQueryID = mysql_query($queryID))
{
	$row = mysql_fetch_assoc($runQueryID);
	
	$id_registrations = $row['id'];
		
	$queryName = "SELECT `name` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `name` ASC";
	
	if ($runQueryName = mysql_query($queryName))
	{
		while($e = mysql_fetch_assoc($runQueryName))
		{
			$output[] = $e;
		}

		echo(json_encode($output));
	}
}

mysql_close();

?>