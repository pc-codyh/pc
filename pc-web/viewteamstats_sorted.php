<?php

$username = $_GET['username'];
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
    <li id="main_menu_teamstats"><a href="viewteamstats.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/teamstats_selected.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_teamprofiles"><a href="viewteamprofiles.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/teamprofiles.png" /></a></li>
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
<p>Overall team stats.</p>
<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Team Name</td>
        <td class="stat_header"><a title="Games Played" href="viewteamstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=games_played">GP</a></td>
        <td class="stat_header"><a title="Wins" href="viewteamstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=wins">W</a></td>
        <td class="stat_header"><a title="Losses" href="viewteamstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=losses">L</a></td>
        <td class="stat_header"><a title="Overtime Losses" href="viewteamstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=ot_losses">OTL</a></td>
        <td class="stat_header"><a title="Overtime Games Played" href="viewteamstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=ot_games_played">OTGP</a></td>
        <td class="stat_header"><a title="Winning Percentage" href="viewteamstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=win_perc">Winning %</a></td>
        <td class="stat_header"><a title="Highest Win Streak" href="viewteamstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=win_streak">Win Streak</a></td>
    </tr>

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

	$teamQuery = "SELECT * FROM `teams` WHERE `id_registrations`='".$id_registrations."' ORDER BY `".$sortby."` DESC";

	if ($runTeamQuery = mysql_query($teamQuery))
	{
        $teamQueryNumRows = mysql_num_rows($runTeamQuery);
    }

    mysql_close();

    $i = 0;

    while ($i < $teamQueryNumRows)
    {
        $f1 = mysql_result($runTeamQuery, $i, "team_name");
        $f2 = mysql_result($runTeamQuery, $i, "games_played");
        $f3 = mysql_result($runTeamQuery, $i, "wins");
        $f4 = mysql_result($runTeamQuery, $i, "losses");
        $f5 = mysql_result($runTeamQuery, $i, "ot_losses");
        $f6 = mysql_result($runTeamQuery, $i, "ot_games_played");
        $f7 = number_format(mysql_result($runTeamQuery, $i, "win_perc") * 100, 2, '.', '');
        $f8 = mysql_result($runTeamQuery, $i, "win_streak");

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

    <td><?php echo $f1; ?></td>
<?php if ($sortby == "games_played") { ?>
    <td class="stat_column_selected"><?php echo $f2; ?></td>
<?php } else { ?>
    <td class="stat_column"><?php echo $f2; ?></td>
<?php } ?>
<?php if ($sortby == "wins") { ?>
    <td class="stat_column_selected"><?php echo $f3; ?></td>
<?php } else { ?>
    <td class="stat_column"><?php echo $f3; ?></td>
<?php } ?>
<?php if ($sortby == "losses") { ?>
    <td class="stat_column_selected"><?php echo $f4; ?></td>
<?php } else { ?>
    <td class="stat_column"><?php echo $f4; ?></td>
<?php } ?>
<?php if ($sortby == "ot_losses") { ?>
    <td class="stat_column_selected"><?php echo $f5; ?></td>
<?php } else { ?>
    <td class="stat_column"><?php echo $f5; ?></td>
<?php } ?>
<?php if ($sortby == "ot_games_played") { ?>
    <td class="stat_column_selected"><?php echo $f6; ?></td>
<?php } else { ?>
    <td class="stat_column"><?php echo $f6; ?></td>
<?php } ?>
<?php if ($sortby == "win_perc") { ?>
    <td class="stat_column_selected"><?php echo $f7; ?></td>
<?php } else { ?>
    <td class="stat_column"><?php echo $f7; ?></td>
<?php } ?>
<?php if ($sortby == "win_streak") { ?>
    <td class="stat_column_selected"><?php echo $f8; ?></td>
<?php } else { ?>
    <td class="stat_column"><?php echo $f8; ?></td>
<?php } ?>

</tr>

<?php

        $i++;
    }
}
?>
</table>

</div>

</div>

<div id="footer">Copyright &copy; 2013 Pong Champ. All Rights Reserved.</div>

</body>
</html>