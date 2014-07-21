<?php

$databaseHost = "68.178.139.40";
$databaseName = "pchitmanhart33";
$databaseUsername = "pchitmanhart33";
$databasePassword = "Cooors33!";

$id_registrations = 0;

mysql_connect($databaseHost, $databaseUsername, $databasePassword) or die(mysql_error());
mysql_select_db($databaseName) or die(mysql_error());

$queryID = "SELECT `id` FROM `registrations` WHERE `username`='".$_GET['username']."'";

if ($runQueryID = mysql_query($queryID))
{
	$row = mysql_fetch_assoc($runQueryID);

	$id_registrations = $row['id'];

    $queryName = "SELECT `wins`, `losses`, `ot_losses`, `player_one`, `player_two` FROM `teams` WHERE `id_registrations`='".$id_registrations."' AND ((`player_one`='".$_GET['team_one_player_one']."' AND `player_two`='".$_GET['team_one_player_two']."') OR (`player_one`='".$_GET['team_one_player_two']."' AND `player_two`='".$_GET['team_one_player_one']."')) OR ((`player_one`='".$_GET['team_two_player_one']."' AND `player_two`='".$_GET['team_two_player_two']."') OR (`player_one`='".$_GET['team_two_player_two']."' AND `player_two`='".$_GET['team_two_player_one']."'))";
                                                                                                                                
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