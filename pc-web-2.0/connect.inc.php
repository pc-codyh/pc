<?php

$mysql_host = '68.178.139.40';
$mysql_user = 'pchitmanhart33';
$mysql_pass = 'Cooors33!';

$mysql_db = 'pchitmanhart33';

if (!mysql_connect($mysql_host, $mysql_user, $mysql_pass) || !mysql_select_db($mysql_db))
{
        die('Failed to connect to the database. Exiting.');
}

?>