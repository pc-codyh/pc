<?php

$databaseHost = "68.178.139.40";
$databaseName = "pchitmanhart33";
$databaseUsername = "pchitmanhart33";
$databasePassword = "Cooors33!";
$id_registrations = 0;

$teamOnePlayerOne = $_POST['team_one_player_one'];
$teamOnePlayerTwo = $_POST['team_one_player_two'];
$teamTwoPlayerOne = $_POST['team_two_player_one'];
$teamTwoPlayerTwo = $_POST['team_two_player_two'];

mysql_connect($databaseHost, $databaseUsername, $databasePassword) or die(mysql_error());
mysql_select_db($databaseName) or die(mysql_error());

$queryID = "SELECT `id` FROM `registrations` WHERE `username`='".$_POST['username']."'";

if ($runQueryID = mysql_query($queryID))
{
	$row = mysql_fetch_assoc($runQueryID);

	$id_registrations = $row['id'];
	
	$teamOneName = $_POST['team_one_player_one'] . " and " . $_POST['team_one_player_two'];
	$teamTwoName = $_POST['team_two_player_one'] . " and " . $_POST['team_two_player_two'];

    if ($_POST['team_one_cups_remaining'] > 0)
    {
        $winningTeam = $_POST['team_one_player_one'] . " and " . $_POST['team_one_player_two'];
        $losingTeam = $_POST['team_two_player_one'] . " and " . $_POST['team_two_player_two'];   
    }
	else
	{
        $winningTeam = $_POST['team_two_player_one'] . " and " . $_POST['team_two_player_two'];
        $losingTeam = $_POST['team_one_player_one'] . " and " . $_POST['team_one_player_two'];
    }

    //////////////////////////////////
    // ADD CURRENT SEASON TO RESULT //
    //////////////////////////////////
    date_default_timezone_set('America/Los_Angeles');

    function get_season($month)
    {
        if ($month == '12' || $month == '01' || $month == '02')
        {
            return 'winter';
        }
        else if ($month == '03' || $month == '04' || $month == '05')
        {
            return 'spring';
        }
        else if ($month == '06' || $month == '07' || $month == '08')
        {
            return 'summer';
        }
        else if ($month == '09' || $month == '10' || $month == '11')
        {
            return 'fall';
        }

        return null;
    }

    $current_date = date('Y-m');
    $index = strpos($current_date, '-');
    $current_year = (trim(substr($current_date, 0, $index)));
    $current_month = trim(substr($current_date, $index + 1));

    if ($current_month == '12')
    {
        $current_year = $current_year + 1;
    }

    $current_season = 'season_'.get_season($current_month).'_'.$current_year;
    
    $queryName = "INSERT INTO `games`(`id_registrations`, `team_one_player_one`, `team_one_player_two`, `team_one_name`, `team_one_cups_remaining`, `team_two_player_one`, `team_two_player_two`, `team_two_name`, `team_two_cups_remaining`, `number_of_ots`, `winning_team`, `losing_team`, `date`, `season`) VALUES ('".$id_registrations."', '".$_POST['team_one_player_one']."', '".$_POST['team_one_player_two']."', '".$teamOneName."', '".$_POST['team_one_cups_remaining']."', '".$_POST['team_two_player_one']."', '".$_POST['team_two_player_two']."', '".$teamTwoName."', '".$_POST['team_two_cups_remaining']."', '".$_POST['number_of_ots']."', '".$winningTeam."', '".$losingTeam."', '".$_POST['date']."', '".$current_season."')"; 

    $teamOneQuery = "SELECT * FROM `teams` WHERE `id_registrations`='".$id_registrations."' AND ((`player_one`='".$_POST['team_one_player_one']."' AND `player_two`='".$_POST['team_one_player_two']."') OR (`player_one`='".$_POST['team_one_player_two']."' AND `player_two`='".$_POST['team_one_player_one']."'))";
    $teamTwoQuery = "SELECT * FROM `teams` WHERE `id_registrations`='".$id_registrations."' AND ((`player_one`='".$_POST['team_two_player_one']."' AND `player_two`='".$_POST['team_two_player_two']."') OR (`player_one`='".$_POST['team_two_player_two']."' AND `player_two`='".$_POST['team_two_player_one']."'))";
    
    if ($runTeamOneQuery = mysql_query($teamOneQuery))
    {
        $teamOneNumRows = mysql_num_rows($runTeamOneQuery);
        
        if ($teamOneNumRows == 0)
        {
            $teamName = $teamOnePlayerOne . " and " . $teamOnePlayerTwo;
            
            mysql_query("INSERT INTO `teams`(`id_registrations`, `player_one`, `player_two`, `team_name`) VALUES ('".$id_registrations."', '".$teamOnePlayerOne."', '".$teamOnePlayerTwo."', '".$teamName."')");
        }
        
        $teamOneStatsQuery = "SELECT * FROM `teams` WHERE `id_registrations`='".$id_registrations."' AND ((`player_one`='".$teamOnePlayerOne."' AND `player_two`='".$teamOnePlayerTwo."') OR (`player_one`='".$teamOnePlayerTwo."' AND `player_two`='".$teamOnePlayerOne."'))";
        
        if ($runTeamOneStatsQuery = mysql_query($teamOneStatsQuery))
        {
            $teamOneStatsQueryNumRows = mysql_num_rows($runTeamOneStatsQuery);
            
            $i = 0;
            
            while ($i < $teamOneStatsQueryNumRows)
            {
                $gamesPlayed = mysql_result($runTeamOneStatsQuery, $i, "games_played");
                $wins = mysql_result($runTeamOneStatsQuery, $i, "wins");
                $losses = mysql_result($runTeamOneStatsQuery, $i, "losses");
                $otlosses = mysql_result($runTeamOneStatsQuery, $i, "ot_losses");
                $winstreak = mysql_result($runTeamOneStatsQuery, $i, "win_streak");
                $curwinstreak = mysql_result($runTeamOneStatsQuery, $i, "cur_win_streak");
                $otgamesplayed = mysql_result($runTeamOneStatsQuery, $i, "ot_games_played");
                
                $gamesPlayed++;
                
                if ($_POST['number_of_ots'] > 0)
                {
                    $otgamesplayed++;
                }
                
                if ($_POST['team_one_cups_remaining'] > 0)
                {
                    $wins++;
                    $curwinstreak++;
                    
                    if ($curwinstreak > $winstreak)
                    {
                        $winstreak = $curwinstreak;
                    }
                }
                else
                {
                    $curwinstreak = 0;
                    
                    if ($_POST['number_of_ots'] > 0)
                    {
                        $otlosses++;
                    }
                    else
                    {
                        $losses++;
                    }
                }
                
                $i++;
            }
            
            $winperc = 0;
            
            if ($gamesPlayed > 0)
            {
                $winperc = ($wins / $gamesPlayed);
            }
            
            mysql_query("UPDATE `teams` SET `games_played`='".$gamesPlayed."', `wins`='".$wins."', `losses`='".$losses."', `ot_losses`='".$otlosses."', `win_streak`='".$winstreak."', `cur_win_streak`='".$curwinstreak."', `win_perc`='".$winperc."', `ot_games_played`='".$otgamesplayed."' WHERE `id_registrations`='".$id_registrations."' AND ((`player_one`='".$teamOnePlayerOne."' AND `player_two`='".$teamOnePlayerTwo."') OR (`player_one`='".$teamOnePlayerTwo."' AND `player_two`='".$teamOnePlayerOne."'))");
        }
    }
    
    if ($runTeamTwoQuery = mysql_query($teamTwoQuery))
    {
        $teamTwoNumRows = mysql_num_rows($runTeamTwoQuery);

        if ($teamTwoNumRows == 0)
        {
            $teamName = $teamTwoPlayerOne . " and " . $teamTwoPlayerTwo;

            mysql_query("INSERT INTO `teams`(`id_registrations`, `player_one`, `player_two`, `team_name`) VALUES ('".$id_registrations."', '".$teamTwoPlayerOne."', '".$teamTwoPlayerTwo."', '".$teamName."')");
        }

        $teamTwoStatsQuery = "SELECT * FROM `teams` WHERE `id_registrations`='".$id_registrations."' AND ((`player_one`='".$teamTwoPlayerOne."' AND `player_two`='".$teamTwoPlayerTwo."') OR (`player_one`='".$teamTwoPlayerTwo."' AND `player_two`='".$teamTwoPlayerOne."'))";

        if ($runTeamTwoStatsQuery = mysql_query($teamTwoStatsQuery))
        {
            $teamTwoStatsQueryNumRows = mysql_num_rows($runTeamTwoStatsQuery);

            $i = 0;

            while ($i < $teamTwoStatsQueryNumRows)
            {
                $gamesPlayed = mysql_result($runTeamTwoStatsQuery, $i, "games_played");
                $wins = mysql_result($runTeamTwoStatsQuery, $i, "wins");
                $losses = mysql_result($runTeamTwoStatsQuery, $i, "losses");
                $otlosses = mysql_result($runTeamTwoStatsQuery, $i, "ot_losses");
                $winstreak = mysql_result($runTeamTwoStatsQuery, $i, "win_streak");
                $curwinstreak = mysql_result($runTeamTwoStatsQuery, $i, "cur_win_streak");
                $otgamesplayed = mysql_result($runTeamTwoStatsQuery, $i, "ot_games_played");

                $gamesPlayed++;

                if ($_POST['number_of_ots'] > 0)
                {
                    $otgamesplayed++;
                }

                if ($_POST['team_two_cups_remaining'] > 0)
                {
                    $wins++;
                    $curwinstreak++;

                    if ($curwinstreak > $winstreak)
                    {
                        $winstreak = $curwinstreak;
                    }
                }
                else
                {
                    $curwinstreak = 0;

                    if ($_POST['number_of_ots'] > 0)
                    {
                        $otlosses++;
                    }
                    else
                    {
                        $losses++;
                    }
                }

                $i++;
            }
            
            $winperc = 0;

            if ($gamesPlayed > 0)
            {
                $winperc = ($wins / $gamesPlayed);
            }

            mysql_query("UPDATE `teams` SET `games_played`='".$gamesPlayed."', `wins`='".$wins."', `losses`='".$losses."', `ot_losses`='".$otlosses."', `win_streak`='".$winstreak."', `cur_win_streak`='".$curwinstreak."', `win_perc`='".$winperc."', `ot_games_played`='".$otgamesplayed."' WHERE `id_registrations`='".$id_registrations."' AND ((`player_one`='".$teamTwoPlayerOne."' AND `player_two`='".$teamTwoPlayerTwo."') OR (`player_one`='".$teamTwoPlayerTwo."' AND `player_two`='".$teamTwoPlayerOne."'))");
        }
    }
    
	if ($runQueryName = mysql_query($queryName))
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