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
    <li id="main_menu_teamstats"><a href="viewteamstats.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/teamstats.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_teamprofiles"><a href="viewteamprofiles.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/teamprofiles.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_gameresults"><a href="viewresults.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/gameresults_selected.png" /></a></li>
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
<p>Previous Game Results.</p>
<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name"><a title="Team One" href="viewresults_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_one_name">Team One</a></td>
        <td class="stat_header"><a title="Team One Cups Remaining" href="viewresults_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_one_cups_remaining">Cups Remaining</a></td>
        <td class="stat_header_name"><a title="Team Two" href="viewresults_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_two_name">Team Two</a></td>
        <td class="stat_header"><a title="Team Two Cups Remaining" href="viewresults_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_two_cups_remaining">Cups Remaining</a></td>
        <td class="stat_header"><a title="Number Of Overtimes" href="viewresults_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=number_of_ots">Number Of OT's</a></td>
        <td class="stat_header"><a title="Winning Team" href="viewresults_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=winning_team">Winning Team</a></td>
        <td class="stat_header"><a title="Date Game Was Played" href="viewresults_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=date">Date</a></td>
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

    if ($sortby == "team_one_name" or $sortby == "team_two_name" or $sortby == "winning_team")
    {
        $gameQuery = "SELECT * FROM `games` WHERE `id_registrations`='".$id_registrations."' ORDER BY `".$sortby."` ASC";    
    }
    else
    {
        $gameQuery = "SELECT * FROM `games` WHERE `id_registrations`='".$id_registrations."' ORDER BY `".$sortby."` DESC";
    }

	if ($runGameQuery = mysql_query($gameQuery))
	{
        $gameQueryNumRows = mysql_num_rows($runGameQuery);
    }

    mysql_close();

    $i = 0;

    while ($i < $gameQueryNumRows)
    {
        $f1 = mysql_result($runGameQuery, $i, "team_one_name");
        $f2 = mysql_result($runGameQuery, $i, "team_one_cups_remaining");
        $f3 = mysql_result($runGameQuery, $i, "team_two_name");
        $f4 = mysql_result($runGameQuery, $i, "team_two_cups_remaining");
        $f5 = mysql_result($runGameQuery, $i, "number_of_ots");
        $f6 = mysql_result($runGameQuery, $i, "winning_team");
        $f7 = mysql_result($runGameQuery, $i, "date");

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
    <td class="stat_column_name_selected"><a href="viewteamprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runGameQuery, $i, "team_one_player_one"); ?>&player_two=<?php echo mysql_result($runGameQuery, $i, "team_one_player_two"); ?>"><?php echo $f1; ?></a></td>
<?php } else { ?>
    <td><a href="viewteamprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runGameQuery, $i, "team_one_player_one"); ?>&player_two=<?php echo mysql_result($runGameQuery, $i, "team_one_player_two"); ?>"><?php echo $f1; ?></a></td>
<?php } ?>
<?php if ($sortby == "team_one_cups_remaining") { ?>
    <td class="stat_column_selected"><?php echo $f2; ?></td>
<?php } else { ?>
    <td class="stat_column"><?php echo $f2; ?></td>
<?php } ?>
<?php if ($sortby == "team_two_name") { ?>
    <td class="stat_column_name_selected"><a href="viewteamprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runGameQuery, $i, "team_two_player_one"); ?>&player_two=<?php echo mysql_result($runGameQuery, $i, "team_two_player_two"); ?>"><?php echo $f3; ?></a></td>
<?php } else { ?>
    <td><a href="viewteamprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runGameQuery, $i, "team_two_player_one"); ?>&player_two=<?php echo mysql_result($runGameQuery, $i, "team_two_player_two"); ?>"><?php echo $f3; ?></a></td>
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
    <td class="stat_column_selected"><a href="viewheadtohead_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runGameQuery, $i, "team_one_player_one"); ?>&player_two=<?php echo mysql_result($runGameQuery, $i, "team_one_player_two"); ?>&player_three=<?php echo mysql_result($runGameQuery, $i, "team_two_player_one"); ?>&player_four=<?php echo mysql_result($runGameQuery, $i, "team_two_player_two"); ?>"><?php echo $f7; ?></a></td>
<?php } else { ?>
    <td class="stat_column"><a href="viewheadtohead_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo mysql_result($runGameQuery, $i, "team_one_player_one"); ?>&player_two=<?php echo mysql_result($runGameQuery, $i, "team_one_player_two"); ?>&player_three=<?php echo mysql_result($runGameQuery, $i, "team_two_player_one"); ?>&player_four=<?php echo mysql_result($runGameQuery, $i, "team_two_player_two"); ?>"><?php echo $f7; ?></a></td>
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