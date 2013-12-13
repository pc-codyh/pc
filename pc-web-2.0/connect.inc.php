<?php

$DEBUG = 1;

if ($DEBUG)
{
	$mysql_host = 'localhost';
	$mysql_user = 'root';
	$mysql_pass = '';

	$mysql_db	= 'pc-2.0';
}
else
{
	$mysql_host = '68.178.139.40';
	$mysql_user = 'pchitmanhart33';
	$mysql_pass = 'Cooors33!';

	$mysql_db	= 'pchitmanhart33';
}

if (!mysql_connect($mysql_host, $mysql_user, $mysql_pass) || !mysql_select_db($mysql_db))
{
	if ($DEBUG)
	{
		die(mysql_error());
	}
	else
	{
		die('Failed to connect to the database. Exiting.');
	}
}

?>