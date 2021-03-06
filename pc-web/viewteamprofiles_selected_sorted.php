<?php

$username = $_GET['username'];
$playerOne = $_GET['player_one'];
$playerTwo = $_GET['player_two'];
$sortby = $_GET['sortby'];

 ?>

<html>
<head>
<title>Pong Champ</title>
<link rel="stylesheet" type="text/css" href="viewstats.css" />
</head>

<body>
<div id="main_body">

<div id="main_header">
<a href="index.html"><img src="imgs/main_logo_2.png" /></a>
</div>

<div id="main_menu">
<ul>
    <li id="main_menu_playerstats"><a href="viewstats.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/playerstats.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_playerprofiles"><a href="viewplayerprofiles.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/playerprofiles.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_teamstats"><a href="viewteamstats.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/teamstats.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_teamprofiles"><a href="viewteamprofiles.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/teamprofiles_selected.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_gameresults"><a href="viewresults.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/gameresults.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_headtohead"><a href="viewheadtohead.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/headtohead.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_leagueleaders"><a href="viewleagueleaders.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/leagueleaders.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_achievements"><a href="viewachievements.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/achievements.png" /></a></li>
</ul>
</div>

<div id="main_info">
<p>Select a team below.</p>

<form action="viewteamprofiles_selected.php" method="get">
<select name="player_one">

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

	$teamQuery = "SELECT * FROM `teams` WHERE `id_registrations`='".$id_registrations."' ORDER BY `team_name` ASC";
	$playersQuery = "SELECT `name` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `name` ASC";

	if ($runTeamQuery = mysql_query($teamQuery))
	{
        $teamQueryNumRows = mysql_num_rows($runTeamQuery);
    }
    
    if ($runPlayerQuery = mysql_query($playersQuery))
    {
        $playerQueryNumRows = mysql_num_rows($runPlayerQuery);
    }

    $i = 0;

    while ($i < $playerQueryNumRows)
    {
        $f1 = mysql_result($runPlayerQuery, $i, "name");
?>

<option value="<?php echo $f1; ?>" <?php if ($playerOne == $f1) { ?> selected <?php } ?> ><?php echo $f1; ?></option>

<?php

        $i++;
    }
?>

</select>

<select name="player_two">

<?php

    $i = 0;

    while ($i < $playerQueryNumRows)
    {
        $f1 = mysql_result($runPlayerQuery, $i, "name");
?>

<option value="<?php echo $f1; ?>" <?php if ($playerTwo == $f1) { ?> selected <?php } ?> ><?php echo $f1; ?></option>

<?php

        $i++;
    }
?>

</select>

<input type="submit" name="submit_team" value="View Profile" class="submit_team_button" />
<input type="hidden" name="username" value="<?php echo $username; ?>" />
</form>

<p>Overall team stats for <?php echo $playerOne . " and " . $playerTwo . "."; ?></p>
<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Team Name</td>
        <td class="stat_header" title="Games Played">GP</td>
        <td class="stat_header" title="Wins">W</td>
        <td class="stat_header" title="Losses">L</td>
        <td class="stat_header" title="Overtime Losses">OTL</td>
        <td class="stat_header" title="Overtime Games Played">OTGP</td>
        <td class="stat_header" title="Winning Percentage">Winning %</td>
        <td class="stat_header" title="Highest Win Streak">Win Streak</td>
    </tr>
    
<?php
 
    $selectedTeamQuery = "SELECT * FROM `teams` WHERE `id_registrations`='".$id_registrations."' AND ((`player_one`='".$playerOne."' AND `player_two`='".$playerTwo."') OR (`player_one`='".$playerTwo."' AND `player_two`='".$playerOne."'))";

    if ($runSelectedTeamQuery = mysql_query($selectedTeamQuery))
    {    
        $runSelectedTeamQueryNumRows = mysql_num_rows($runSelectedTeamQuery);
         
        if ($runSelectedTeamQueryNumRows == 1)
        {
            $f100 = mysql_result($runSelectedTeamQuery, 0, "team_name");
            $f1 = mysql_result($runSelectedTeamQuery, 0, "games_played");
            $f2 = mysql_result($runSelectedTeamQuery, 0, "wins");
            $f3 = mysql_result($runSelectedTeamQuery, 0, "losses");
            $f4 = mysql_result($runSelectedTeamQuery, 0, "ot_losses");
            $f5 = mysql_result($runSelectedTeamQuery, 0, "ot_games_played");
            $f6 = number_format(mysql_result($runSelectedTeamQuery, 0, "win_perc") * 100, 2, '.', '');
            $f7 = mysql_result($runSelectedTeamQuery, 0, "win_streak");
        }
        else
        {
            $f100 = $playerOne . " and " . $playerTwo;
            $f1 = 0;
            $f2 = 0;
            $f3 = 0;
            $f4 = 0;
            $f5 = 0;
            $f6 = number_format(0, 2, '.', '');
            $f7 = 0;
        }

        if (($i % 2) == 0)
        {
?>

<tr class="even_row">

<?php
        }
        else
        {
?>

<tr class="odd_row">

<?php
        }
?>

    <td><?php echo $f100; ?></td>
    <td class="stat_column"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
    <td class="stat_column"><?php echo $f3; ?></td>
    <td class="stat_column"><?php echo $f4; ?></td>
    <td class="stat_column"><?php echo $f5; ?></td>
    <td class="stat_column"><?php echo $f6; ?></td>
    <td class="stat_column"><?php echo $f7; ?></td>
</tr>

<?php
    }
?>

</table>

<p>Game results for <?php echo $playerOne . " and " . $playerTwo . "."; ?></p>
<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name"><a title="Team One" href="viewteamprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_one_name&player_one=<?php echo $playerOne; ?>&player_two=<?php echo $playerTwo; ?>">Team One</a></td>
        <td class="stat_header"><a title="Team One Cups Remaining" href="viewteamprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_one_cups_remaining&player_one=<?php echo $playerOne; ?>&player_two=<?php echo $playerTwo; ?>">Cups Remaining</a></td>
        <td class="stat_header_name"><a title="Team Two" href="viewteamprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_two_name&player_one=<?php echo $playerOne; ?>&player_two=<?php echo $playerTwo; ?>">Team Two</a></td>
        <td class="stat_header"><a title="Team Two Cups Remaining" href="viewteamprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_two_cups_remaining&player_one=<?php echo $playerOne; ?>&player_two=<?php echo $playerTwo; ?>">Cups Remaining</a></td>
        <td class="stat_header"><a title="Number Of Overtimes" href="viewteamprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=number_of_ots&player_one=<?php echo $playerOne; ?>&player_two=<?php echo $playerTwo; ?>">Number Of OT's</a></td>
        <td class="stat_header"><a title="Winning Team" href="viewteamprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=winning_team&player_one=<?php echo $playerOne; ?>&player_two=<?php echo $playerTwo; ?>">Winning Team</a></td>
        <td class="stat_header"><a title="Date Game Was Played" href="viewteamprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=date&player_one=<?php echo $playerOne; ?>&player_two=<?php echo $playerTwo; ?>">Date</a></td>
    </tr>

<?php

    if ($sortby == "team_one_name" or $sortby == "team_two_name" or $sortby == "winning_team")
    {
        $selectedGameQuery = "SELECT * FROM `games` WHERE `id_registrations`='".$id_registrations."' AND ((`team_one_player_one`='".$playerOne."' AND `team_one_player_two`='".$playerTwo."') OR (`team_one_player_one`='".$playerTwo."' AND `team_one_player_two`='".$playerOne."') OR (`team_two_player_one`='".$playerTwo."' AND `team_two_player_two`='".$playerOne."') OR (`team_two_player_one`='".$playerOne."' AND `team_two_player_two`='".$playerTwo."')) ORDER BY `".$sortby."` ASC";   
    }
    else
    {
        $selectedGameQuery = "SELECT * FROM `games` WHERE `id_registrations`='".$id_registrations."' AND ((`team_one_player_one`='".$playerOne."' AND `team_one_player_two`='".$playerTwo."') OR (`team_one_player_one`='".$playerTwo."' AND `team_one_player_two`='".$playerOne."') OR (`team_two_player_one`='".$playerTwo."' AND `team_two_player_two`='".$playerOne."') OR (`team_two_player_one`='".$playerOne."' AND `team_two_player_two`='".$playerTwo."')) ORDER BY `".$sortby."` DESC";   
    }

    if ($runSelectedGameQuery = mysql_query($selectedGameQuery))
    {
        $i = 0;
        
        $runSelectedGameQueryNumRows = mysql_num_rows($runSelectedGameQuery);

        while ($i < $runSelectedGameQueryNumRows)
        {
            $f1 = mysql_result($runSelectedGameQuery, $i, "team_one_name");
            $f2 = mysql_result($runSelectedGameQuery, $i, "team_one_cups_remaining");
            $f3 = mysql_result($runSelectedGameQuery, $i, "team_two_name");
            $f4 = mysql_result($runSelectedGameQuery, $i, "team_two_cups_remaining");
            $f5 = mysql_result($runSelectedGameQuery, $i, "number_of_ots");
            $f6 = mysql_result($runSelectedGameQuery, $i, "winning_team");
            $f7 = mysql_result($runSelectedGameQuery, $i, "date");  

        if (($i % 2) == 0)
        {
?>

<tr class="even_row">

<?php
        }
        else
        {
?>

<tr class="odd_row">

<?php
        }
?>

    <?php if ($sortby == "team_one_name") { ?>
        <td class="stat_column_name_selected"><a href="viewteamprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runSelectedGameQuery, $i, "team_one_player_one"); ?>&player_two=<?php echo mysql_result($runSelectedGameQuery, $i, "team_one_player_two"); ?>"><?php echo $f1; ?></a></td>
    <?php } else { ?>
        <td><a href="viewteamprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runSelectedGameQuery, $i, "team_one_player_one"); ?>&player_two=<?php echo mysql_result($runSelectedGameQuery, $i, "team_one_player_two"); ?>"><?php echo $f1; ?></a></td>
    <?php } ?>
    <?php if ($sortby == "team_one_cups_remaining") { ?>
        <td class="stat_column_selected"><?php echo $f2; ?></td>
    <?php } else { ?>
        <td class="stat_column"><?php echo $f2; ?></td>
    <?php } ?>
    <?php if ($sortby == "team_two_name") { ?>
        <td class="stat_column_name_selected"><a href="viewteamprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runSelectedGameQuery, $i, "team_two_player_one"); ?>&player_two=<?php echo mysql_result($runSelectedGameQuery, $i, "team_two_player_two"); ?>"><?php echo $f3; ?></a></td>
    <?php } else { ?>
        <td><a href="viewteamprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runSelectedGameQuery, $i, "team_two_player_one"); ?>&player_two=<?php echo mysql_result($runSelectedGameQuery, $i, "team_two_player_two"); ?>"><?php echo $f3; ?></a></td>
    <?php } ?>
    <?php if ($sortby == "team_two_cups_remaining") { ?>
        <td class="stat_column_selected"><?php echo $f4; ?></td>
    <?php } else { ?>
        <td class="stat_column"><?php echo $f4; ?></td>
    <?php } ?>
    <?php if ($sortby == "number_of_ots") { ?>
        <td class="stat_column_selected"><?php echo $f5; ?></td>
    <?php } else { ?>
        <td class="stat_column"><?php echo $f5; ?></td>
    <?php } ?>
    <?php if ($sortby == "winning_team") { ?>
        <td class="stat_column_selected"><?php echo $f6; ?></td>
    <?php } else { ?>
        <td class="stat_column"><?php echo $f6; ?></td>
    <?php } ?>
    <?php if ($sortby == "date") { ?>
        <td class="stat_column_selected"><a href="viewheadtohead_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runSelectedGameQuery, $i, "team_one_player_one"); ?>&player_two=<?php echo mysql_result($runSelectedGameQuery, $i, "team_one_player_two"); ?>&player_three=<?php echo mysql_result($runSelectedGameQuery, $i, "team_two_player_one"); ?>&player_four=<?php echo mysql_result($runSelectedGameQuery, $i, "team_two_player_two"); ?>"><?php echo $f7; ?></a></td>
    <?php } else { ?>
        <td class="stat_column"><a href="viewheadtohead_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runSelectedGameQuery, $i, "team_one_player_one"); ?>&player_two=<?php echo mysql_result($runSelectedGameQuery, $i, "team_one_player_two"); ?>&player_three=<?php echo mysql_result($runSelectedGameQuery, $i, "team_two_player_one"); ?>&player_four=<?php echo mysql_result($runSelectedGameQuery, $i, "team_two_player_two"); ?>"><?php echo $f7; ?></a></td>
    <?php } ?>
</tr>

<?php
            $i++;
        }
    }
    
    mysql_close();
}
?>

</table>

</div>

</div>

<div id="footer">Copyright &copy; 2013 Pong Champ. All Rights Reserved.</div>

</body>
</html>