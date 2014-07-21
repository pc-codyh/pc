<?php

$databaseHost = "68.178.139.40";
$databaseName = "pchitmanhart33";
$databaseUsername = "pchitmanhart33";
$databasePassword = "Cooors33!";

$id_registrations = 0;

mysql_connect($databaseHost, $databaseUsername, $databasePassword) or die(mysql_error());
mysql_select_db($databaseName) or die(mysql_error());

$playerOne = $_GET['player_one'];
$playerTwo = $_GET['player_two'];
$playerThree = $_GET['player_three'];
$playerFour = $_GET['player_four'];

$queryID = "SELECT `id` FROM `registrations` WHERE `username`='".$_GET['username']."'";

if ($runQueryID = mysql_query($queryID))
{
	$row = mysql_fetch_assoc($runQueryID);

	$id_registrations = $row['id'];

    $queryName = "SELECT `team_one_player_one`, `team_one_player_two`, `team_one_cups_remaining`, `team_two_player_one`, `team_two_player_two`, `team_two_cups_remaining`, `number_of_ots`, `date` FROM `games` WHERE `id_registrations`='".$id_registrations."' AND ((`team_one_player_one`='".$playerOne."' AND `team_one_player_two`='".$playerTwo."' AND `team_two_player_one`='".$playerThree."' AND `team_two_player_two`='".$playerFour."') OR (`team_one_player_one`='".$playerTwo."' AND `team_one_player_two`='".$playerOne."' AND `team_two_player_one`='".$playerThree."' AND `team_two_player_two`='".$playerFour."') OR (`team_one_player_one`='".$playerOne."' AND `team_one_player_two`='".$playerTwo."' AND `team_two_player_one`='".$playerFour."' AND `team_two_player_two`='".$playerThree."') OR (`team_one_player_one`='".$playerTwo."' AND `team_one_player_two`='".$playerOne."' AND `team_two_player_one`='".$playerFour."' AND `team_two_player_two`='".$playerThree."') OR (`team_one_player_one`='".$playerThree."' AND `team_one_player_two`='".$playerFour."' AND `team_two_player_one`='".$playerOne."' AND `team_two_player_two`='".$playerTwo."') OR (`team_one_player_one`='".$playerFour."' AND `team_one_player_two`='".$playerThree."' AND `team_two_player_one`='".$playerOne."' AND `team_two_player_two`='".$playerTwo."') OR (`team_one_player_one`='".$playerThree."' AND `team_one_player_two`='".$playerFour."' AND `team_two_player_one`='".$playerTwo."' AND `team_two_player_two`='".$playerOne."') OR (`team_one_player_one`='".$playerFour."' AND `team_one_player_two`='".$playerThree."' AND `team_two_player_one`='".$playerTwo."' AND `team_two_player_two`='".$playerOne."')) ORDER BY `date` DESC";
                                                                                                                                
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