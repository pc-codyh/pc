<?php

$username = $_GET['username'];
$playerOne = $_GET['player_one'];
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
    <li id="main_menu_playerprofiles"><a href="viewplayerprofiles.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/playerprofiles_selected.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_teamstats"><a href="viewteamstats.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/teamstats.png" /></a></li>
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
<p>Select a player below.</p>

<form action="viewplayerprofiles_selected.php" method="get">
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
}
?>

</select>

<input type="submit" name="submit_team" value="View Profile" class="submit_team_button" />
<input type="hidden" name="username" value="<?php echo $username; ?>" />
</form>

<p>Overall stats for <?php echo $playerOne; ?></p>

<div id="player_rank">
    <table>
        <tr>
            <td><img src="imgs/rank_icon.png" /></td>
            <td>
                <b>
                <?php
                    $selectRankQuery = "SELECT `rank` FROM `players` WHERE `id_registrations`='".$id_registrations."' AND `name`='".$playerOne."'";

                    if ($runSelectRankQuery = mysql_query($selectRankQuery))
                    {
                        $playerRank = mysql_result($runSelectRankQuery, 0, "rank");

                        echo " Rank: ".$playerRank;
                    }
                ?>
                </b>
            </td>
        </tr>
    </table>
</div>

<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Name</td>
        <td class="stat_header" title="Games Played">GP</td>
        <td class="stat_header" title="Wins">W</td>
        <td class="stat_header" title="Losses">L</td>
        <td class="stat_header" title="Overtime Losses">OTL</td>
        <td class="stat_header" title="Overtime Games Played">OTGP</td>
        <td class="stat_header" title="Cup Differential">+/-</td>
        <td class="stat_header" title="Shots">Shots</td>
        <td class="stat_header" title="Hits">Hits</td>
        <td class="stat_header" title="Shooting Percentage">Shooting %</td>
        <td class="stat_header" title="Bounces">Bounces</td>
        <td class="stat_header" title="Gang-Bangs">Gang-Bangs</td>
        <td class="stat_header" title="Errors">Errors</td>
        <td class="stat_header" title="Heating Up">Heating Up</td>
        <td class="stat_header" title="On Fire">On Fire</td>
    </tr>
    
<?php
 
    $selectedPlayerQuery = "SELECT * FROM `players` WHERE `id_registrations`='".$id_registrations."' AND `name`='".$playerOne."'";

    if ($runSelectedPlayerQuery = mysql_query($selectedPlayerQuery))
    {    
        $runSelectedPlayerQueryNumRows = mysql_num_rows($runSelectedPlayerQuery);
         
        if ($runSelectedPlayerQueryNumRows == 1)
        {
            $f1 = mysql_result($runSelectedPlayerQuery, 0, "name");
            $f2 = mysql_result($runSelectedPlayerQuery, 0, "games_played");
            $f3 = mysql_result($runSelectedPlayerQuery, 0, "wins");
            $f4 = mysql_result($runSelectedPlayerQuery, 0, "losses");
            $f5 = mysql_result($runSelectedPlayerQuery, 0, "ot_losses");
            $f6 = mysql_result($runSelectedPlayerQuery, 0, "ot_games_played");
            $f7 = mysql_result($runSelectedPlayerQuery, 0, "cup_dif");
            $f8 = mysql_result($runSelectedPlayerQuery, 0, "shots");
            $f9 = mysql_result($runSelectedPlayerQuery, 0, "hits");
            $f10 = number_format(mysql_result($runSelectedPlayerQuery, 0, "shooting_percentage") * 100, 2, '.', '');
            $f11 = mysql_result($runSelectedPlayerQuery, 0, "bounces");
            $f12 = mysql_result($runSelectedPlayerQuery, 0, "gang_bangs");
            $f13 = mysql_result($runSelectedPlayerQuery, 0, "errors");
            $f14 = mysql_result($runSelectedPlayerQuery, 0, "heating_up");
            $f15 = mysql_result($runSelectedPlayerQuery, 0, "on_fire");
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

    <td><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
    <td class="stat_column"><?php echo $f3; ?></td>
    <td class="stat_column"><?php echo $f4; ?></td>
    <td class="stat_column"><?php echo $f5; ?></td>
    <td class="stat_column"><?php echo $f6; ?></td>
    <td class="stat_column"><?php echo $f7; ?></td>
    <td class="stat_column"><?php echo $f8; ?></td>
    <td class="stat_column"><?php echo $f9; ?></td>
    <td class="stat_column"><?php echo $f10; ?></td>
    <td class="stat_column"><?php echo $f11; ?></td>
    <td class="stat_column"><?php echo $f12; ?></td>
    <td class="stat_column"><?php echo $f13; ?></td>
    <td class="stat_column"><?php echo $f14; ?></td>
    <td class="stat_column"><?php echo $f15; ?></td>
</tr>

<?php
    }
?>

</table>

<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Name</td>
        <td class="stat_header" title="Highest Win Streak">Win Streak</td>
        <td class="stat_header" title="Highest Loss Streak">Loss Streak</td>
        <td class="stat_header" title="Highest Hit Streak">Hit Streak</td>
        <td class="stat_header" title="Highest Miss Streak">Miss Streak</td>
        <td class="stat_header" title="Elo Rating">Elo Rating</td>
        <td class="stat_header" title="Compound Rating">Compound Rating</td>
        <td class="stat_header" title="Overall Rank">Rank</td>
    </tr>
    
<?php
 
    $selectedPlayerQuery = "SELECT * FROM `players` WHERE `id_registrations`='".$id_registrations."' AND `name`='".$playerOne."'";

    if ($runSelectedPlayerQuery = mysql_query($selectedPlayerQuery))
    {    
        $runSelectedPlayerQueryNumRows = mysql_num_rows($runSelectedPlayerQuery);
         
        if ($runSelectedPlayerQueryNumRows == 1)
        {
            $f1 = mysql_result($runSelectedPlayerQuery, 0, "name");
            $f2 = mysql_result($runSelectedPlayerQuery, 0, "win_streak");
            $f3 = mysql_result($runSelectedPlayerQuery, 0, "loss_streak");
            $f4 = mysql_result($runSelectedPlayerQuery, 0, "hit_streak");
            $f5 = mysql_result($runSelectedPlayerQuery, 0, "miss_streak");
            $f6 = number_format(mysql_result($runSelectedPlayerQuery, 0, "elo_rating"), 2, '.', '');
            $f7 = number_format(mysql_result($runSelectedPlayerQuery, 0, "compound_rating"), 2, '.', '');
            $f8 = mysql_result($runSelectedPlayerQuery, 0, "rank");
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

    <td><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
    <td class="stat_column"><?php echo $f3; ?></td>
    <td class="stat_column"><?php echo $f4; ?></td>
    <td class="stat_column"><?php echo $f5; ?></td>
    <td class="stat_column"><?php echo $f6; ?></td>
    <td class="stat_column"><?php echo $f7; ?></td>
    <td class="stat_column"><?php echo $f8; ?></td>
</tr>

<?php
    }
?>

</table>

<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Name</td>
        <td class="stat_header" title="Redemption Shots">Redemption Shots</td>
        <td class="stat_header" title="Redemption Hits">Redemption Hits</td>
        <td class="stat_header" title="Redemption Shooting Percentage">Redemption Shooting %</td>
        <td class="stat_header" title="Redemption Attempts">Redemption Attempts</td>
        <td class="stat_header" title="Redemption Successes">Redemption Successes</td>
    </tr>
    
<?php
 
    $selectedPlayerQuery = "SELECT * FROM `players` WHERE `id_registrations`='".$id_registrations."' AND `name`='".$playerOne."'";

    if ($runSelectedPlayerQuery = mysql_query($selectedPlayerQuery))
    {    
        $runSelectedPlayerQueryNumRows = mysql_num_rows($runSelectedPlayerQuery);
         
        if ($runSelectedPlayerQueryNumRows == 1)
        {
            $f1 = mysql_result($runSelectedPlayerQuery, 0, "name");
            $f2 = mysql_result($runSelectedPlayerQuery, 0, "redemp_shots");
            $f3 = mysql_result($runSelectedPlayerQuery, 0, "redemp_hits");
            $f4 = number_format(mysql_result($runSelectedPlayerQuery, 0, "redemp_shotperc") * 100, 2, '.', '');
            $f5 = mysql_result($runSelectedPlayerQuery, 0, "redemp_atmps");
            $f6 = mysql_result($runSelectedPlayerQuery, 0, "redemp_succs");
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

    <td><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
    <td class="stat_column"><?php echo $f3; ?></td>
    <td class="stat_column"><?php echo $f4; ?></td>
    <td class="stat_column"><?php echo $f5; ?></td>
    <td class="stat_column"><?php echo $f6; ?></td>
</tr>

<?php
    }
?>

</table>

<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Name</td>
        <td class="stat_header" title="Ten Cups">10</td>
        <td class="stat_header" title="Nine Cups">9</td>
        <td class="stat_header" title="Eight Cups">8</td>
        <td class="stat_header" title="Seven Cups">7</td>
        <td class="stat_header" title="Six Cups">6</td>
        <td class="stat_header" title="Five Cups">5</td>
        <td class="stat_header" title="Four Cups">4</td>
        <td class="stat_header" title="Three Cups">3</td>
        <td class="stat_header" title="Two Cups">2</td>
        <td class="stat_header" title="One Cup">Last Cup</td>
        <td class="stat_header" title="Last Cups Hit">Last Cups Hit</td>
    </tr>
    
<?php
 
    $selectedPlayerQuery = "SELECT * FROM `players` WHERE `id_registrations`='".$id_registrations."' AND `name`='".$playerOne."'";

    if ($runSelectedPlayerQuery = mysql_query($selectedPlayerQuery))
    {    
        $runSelectedPlayerQueryNumRows = mysql_num_rows($runSelectedPlayerQuery);
         
        if ($runSelectedPlayerQueryNumRows == 1)
        {
            $f1 = mysql_result($runSelectedPlayerQuery, 0, "name");
            $f2 = number_format(mysql_result($runSelectedPlayerQuery, 0, "p10") * 100, 2, '.', '');
            $f3 = number_format(mysql_result($runSelectedPlayerQuery, 0, "p9") * 100, 2, '.', '');
            $f4 = number_format(mysql_result($runSelectedPlayerQuery, 0, "p8") * 100, 2, '.', '');
            $f5 = number_format(mysql_result($runSelectedPlayerQuery, 0, "p7") * 100, 2, '.', '');
            $f6 = number_format(mysql_result($runSelectedPlayerQuery, 0, "p6") * 100, 2, '.', '');
            $f7 = number_format(mysql_result($runSelectedPlayerQuery, 0, "p5") * 100, 2, '.', '');
            $f8 = number_format(mysql_result($runSelectedPlayerQuery, 0, "p4") * 100, 2, '.', '');
            $f9 = number_format(mysql_result($runSelectedPlayerQuery, 0, "p3") * 100, 2, '.', '');
            $f10 = number_format(mysql_result($runSelectedPlayerQuery, 0, "p2") * 100, 2, '.', '');
            $f11 = number_format(mysql_result($runSelectedPlayerQuery, 0, "p1") * 100, 2, '.', '');
            $f12 = mysql_result($runSelectedPlayerQuery, 0, "h1");
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

    <td><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
    <td class="stat_column"><?php echo $f3; ?></td>
    <td class="stat_column"><?php echo $f4; ?></td>
    <td class="stat_column"><?php echo $f5; ?></td>
    <td class="stat_column"><?php echo $f6; ?></td>
    <td class="stat_column"><?php echo $f7; ?></td>
    <td class="stat_column"><?php echo $f8; ?></td>
    <td class="stat_column"><?php echo $f9; ?></td>
    <td class="stat_column"><?php echo $f10; ?></td>
    <td class="stat_column"><?php echo $f11; ?></td>
    <td class="stat_column"><?php echo $f12; ?></td>
</tr>

<?php
    }
?>

</table>

<p>Achievements for <?php echo $playerOne; ?></p>

<table class="achievement_group">
<tr>
<td>
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name" title="Achievement">Achievement</td>
        <td class="stat_header" title="Count">Count</td>
    </tr>

<?php

    $achievementQuery = "SELECT * FROM `players` WHERE `id_registrations`='".$id_registrations."' AND `name`='".$playerOne."'";

    if ($runAchievementQuery = mysql_query($achievementQuery))
    {
        $i = 0;

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
    <td><?php echo "Sharpshooter"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_shs"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Michael Jordan"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_mj"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Can I Buy A Vowel?"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_cibav"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Heartbreak City"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_hc"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Caught With Their Pants Down"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_cwtpd"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Porn Star"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_ps"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Perfection"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_per"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Down But Not Out"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_dbno"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Marathon"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_mar"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "First Degree Murder"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_fdm"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Comeback Kill"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_ck"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Bill Buckner"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_bb"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Bitch Cup"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_bc"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Bankruptcy"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_bank"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Skunked"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_skunk"); ?></td>
</tr>
<?php
        $i++;

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
    <td><?php echo "Stevie Wonder"; ?></td>
    <td class="stat_column"><?php echo mysql_result($runAchievementQuery, 0, "a_sw"); ?></td>
</tr>
<?php
        $i++;
    }
?>
</table>
</td>
<td><blockquote><center>* Achievement descriptions are available on the "Achievements" page.</center></blockquote></td>
</tr>
</table>

<h4>Unlocked Achievements</h4>
<table class="achievement_group">
<tr>
<td>
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name" title="Achievement">Achievement</td>
        <td class="stat_header" title="Count">Count</td>
        <td class="stat_header" title="How To Unlock">How To Unlock</td>
    </tr>

<?php
        $i = 0;

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
    <td><?php if (mysql_result($runAchievementQuery, 0, "games_played") >= 500) { echo "Alcoholic"; } else { echo "**********"; } ?></td>
    <td class="stat_column"><?php if (mysql_result($runAchievementQuery, 0, "games_played") >= 500) { echo mysql_result($runAchievementQuery, 0, "ua_alc"); } else { echo "*"; } ?></td>
    <?php if (mysql_result($runAchievementQuery, 0, "games_played") < 500) { ?><td class="stat_column">Complete the "Games Played" milestone.</td><?php } else { ?><td class="stat_column" title="Play in consecutive overtime games.">Unlocked</td><?php } ?>
</tr>
<?php
        $i++;

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
    <td><?php if (mysql_result($runAchievementQuery, 0, "wins") >= 500) { echo "On A Heater"; } else { echo "**********"; } ?></td>
    <td class="stat_column"><?php if (mysql_result($runAchievementQuery, 0, "wins") >= 500) { echo mysql_result($runAchievementQuery, 0, "ua_oah"); } else { echo "*"; } ?></td>
    <?php if (mysql_result($runAchievementQuery, 0, "wins") < 500) { ?><td class="stat_column">Complete the "Wins" milestone.</td><?php } else { ?><td class="stat_column" title="Win five games in a row.">Unlocked</td><?php } ?>
</tr>
<?php
        $i++;

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
    <td><?php if (mysql_result($runAchievementQuery, 0, "hits") >= 1000) { echo "Count 'Em"; } else { echo "**********"; } ?></td>
    <td class="stat_column"><?php if (mysql_result($runAchievementQuery, 0, "hits") >= 1000) { echo mysql_result($runAchievementQuery, 0, "ua_ce"); } else { echo "*"; } ?></td>
    <?php if (mysql_result($runAchievementQuery, 0, "hits") < 1000) { ?><td class="stat_column">Complete the "Cups Hit" milestone.</td><?php } else { ?><td class="stat_column" title="Hit ten cups in a game.">Unlocked</td><?php } ?>
</tr>
<?php
        $i++;

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
    <td><?php if (mysql_result($runAchievementQuery, 0, "bounces") >= 100) { echo "Slippery"; } else { echo "**********"; } ?></td>
    <td class="stat_column"><?php if (mysql_result($runAchievementQuery, 0, "bounces") >= 100) { echo mysql_result($runAchievementQuery, 0, "ua_slip"); } else { echo "*"; } ?></td>
    <?php if (mysql_result($runAchievementQuery, 0, "bounces") < 100) { ?><td class="stat_column">Complete the "Bounces Hit" milestone.</td><?php } else { ?><td class="stat_column" title="Hit two consecutive bounce shots without missing.">Unlocked</td><?php } ?>
</tr>
<?php
        $i++;

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
    <td><?php if (mysql_result($runAchievementQuery, 0, "redemp_succs") >= 100) { echo "Don't Call It A Comeback"; } else { echo "**********"; } ?></td>
    <td class="stat_column"><?php if (mysql_result($runAchievementQuery, 0, "redemp_succs") >= 100) { echo mysql_result($runAchievementQuery, 0, "ua_dciac"); } else { echo "*"; } ?></td>
    <?php if (mysql_result($runAchievementQuery, 0, "redemp_succs") < 100) { ?><td class="stat_column">Complete the "Redemption Successes" milestone.</td><?php } else { ?><td class="stat_column" title="Successfully complete a redemption and go on to win the game in the first overtime.">Unlocked</td><?php } ?>
</tr>
<?php
        $i++;

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
    <td><?php if (mysql_result($runAchievementQuery, 0, "h1") >= 100) { echo "Dropping The Hammer"; } else { echo "**********"; } ?></td>
    <td class="stat_column"><?php if (mysql_result($runAchievementQuery, 0, "h1") >= 100) { echo mysql_result($runAchievementQuery, 0, "ua_dth"); } else { echo "*"; } ?></td>
    <?php if (mysql_result($runAchievementQuery, 0, "h1") < 100) { ?><td class="stat_column">Complete the "Last Cups Hit" milestone.</td><?php } else { ?><td class="stat_column" title="Hit three last cups in a game.">Unlocked</td><?php } ?>
</tr>
<?php
        $i++;

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
    <td><?php if ((mysql_result($runSelectedPlayerQuery, 0, "a_shs") + mysql_result($runSelectedPlayerQuery, 0, "a_mj") + mysql_result($runSelectedPlayerQuery, 0, "a_cibav") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_hc") + mysql_result($runSelectedPlayerQuery, 0, "a_cwtpd") + mysql_result($runSelectedPlayerQuery, 0, "a_ps") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_per") + mysql_result($runSelectedPlayerQuery, 0, "a_dbno") + mysql_result($runSelectedPlayerQuery, 0, "a_mar") +
                 mysql_result($runSelectedPlayerQuery, 0, "a_ck") + mysql_result($runSelectedPlayerQuery, 0, "a_bb") + mysql_result($runSelectedPlayerQuery, 0, "a_bc") +
                 mysql_result($runSelectedPlayerQuery, 0, "a_bank") + mysql_result($runSelectedPlayerQuery, 0, "a_sw") + mysql_result($runSelectedPlayerQuery, 0, "a_fdm") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_skunk") + mysql_result($runSelectedPlayerQuery, 0, "ua_alc") + mysql_result($runSelectedPlayerQuery, 0, "ua_oah") + 
                 mysql_result($runSelectedPlayerQuery, 0, "ua_ce") + mysql_result($runSelectedPlayerQuery, 0, "ua_slip") + mysql_result($runSelectedPlayerQuery, 0, "ua_dciac") + 
                 mysql_result($runSelectedPlayerQuery, 0, "ua_dth") + mysql_result($runSelectedPlayerQuery, 0, "ua_show")) >= 100) { echo "Showman"; } else { echo "**********"; } ?></td>
    <td class="stat_column"><?php if ((mysql_result($runSelectedPlayerQuery, 0, "a_shs") + mysql_result($runSelectedPlayerQuery, 0, "a_mj") + mysql_result($runSelectedPlayerQuery, 0, "a_cibav") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_hc") + mysql_result($runSelectedPlayerQuery, 0, "a_cwtpd") + mysql_result($runSelectedPlayerQuery, 0, "a_ps") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_per") + mysql_result($runSelectedPlayerQuery, 0, "a_dbno") + mysql_result($runSelectedPlayerQuery, 0, "a_mar") +
                 mysql_result($runSelectedPlayerQuery, 0, "a_ck") + mysql_result($runSelectedPlayerQuery, 0, "a_bb") + mysql_result($runSelectedPlayerQuery, 0, "a_bc") +
                 mysql_result($runSelectedPlayerQuery, 0, "a_bank") + mysql_result($runSelectedPlayerQuery, 0, "a_sw") + mysql_result($runSelectedPlayerQuery, 0, "a_fdm") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_skunk") + mysql_result($runSelectedPlayerQuery, 0, "ua_alc") + mysql_result($runSelectedPlayerQuery, 0, "ua_oah") + 
                 mysql_result($runSelectedPlayerQuery, 0, "ua_ce") + mysql_result($runSelectedPlayerQuery, 0, "ua_slip") + mysql_result($runSelectedPlayerQuery, 0, "ua_dciac") + 
                 mysql_result($runSelectedPlayerQuery, 0, "ua_dth") + mysql_result($runSelectedPlayerQuery, 0, "ua_show")) >= 100) { echo mysql_result($runAchievementQuery, 0, "ua_show"); } else { echo "*"; } ?></td>
    <?php if ((mysql_result($runSelectedPlayerQuery, 0, "a_shs") + mysql_result($runSelectedPlayerQuery, 0, "a_mj") + mysql_result($runSelectedPlayerQuery, 0, "a_cibav") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_hc") + mysql_result($runSelectedPlayerQuery, 0, "a_cwtpd") + mysql_result($runSelectedPlayerQuery, 0, "a_ps") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_per") + mysql_result($runSelectedPlayerQuery, 0, "a_dbno") + mysql_result($runSelectedPlayerQuery, 0, "a_mar") +
                 mysql_result($runSelectedPlayerQuery, 0, "a_ck") + mysql_result($runSelectedPlayerQuery, 0, "a_bb") + mysql_result($runSelectedPlayerQuery, 0, "a_bc") +
                 mysql_result($runSelectedPlayerQuery, 0, "a_bank") + mysql_result($runSelectedPlayerQuery, 0, "a_sw") + mysql_result($runSelectedPlayerQuery, 0, "a_fdm") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_skunk") + mysql_result($runSelectedPlayerQuery, 0, "ua_alc") + mysql_result($runSelectedPlayerQuery, 0, "ua_oah") + 
                 mysql_result($runSelectedPlayerQuery, 0, "ua_ce") + mysql_result($runSelectedPlayerQuery, 0, "ua_slip") + mysql_result($runSelectedPlayerQuery, 0, "ua_dciac") + 
                 mysql_result($runSelectedPlayerQuery, 0, "ua_dth") + mysql_result($runSelectedPlayerQuery, 0, "ua_show")) < 100) { ?><td class="stat_column">Complete the "Achievements Earned" milestone.</td><?php } else { ?><td class="stat_column" title="Earn three achievements in a game.">Unlocked</td><?php } ?>
</tr>
</table>
</td>
<td>
<blockquote><center>* Achievement descriptions are available by hovering over the "How To Unlock" section once the achievement has been unlocked.</center></blockquote>
</td>
</tr>
</table>


<?php 
    function getMilestoneRangeHigh($value)
    {
        if ($value < 10)
        {
            return 10;
        }
        else if ($value < 50)
        {
            return 50;
        }
        else if ($value < 100)
        {
            return 100;
        }
        else if ($value < 500)
        {
            return 500;
        }
        else
        {
            return 1000;
        }
    }

    function getMilestoneRangeMedium($value)
    {
        if ($value < 10)
        {
            return 10;
        }
        else if ($value < 50)
        {
            return 50;
        }
        else if ($value < 100)
        {
            return 100;
        }
        else if ($value < 200)
        {
            return 200;
        }
        else
        {
            return 500;
        }
    }

    function getMilestoneRangeLow($value)
    {
        if ($value < 5)
        {
            return 5;
        }
        else if ($value < 10)
        {
            return 10;
        }
        else if ($value < 20)
        {
            return 20;
        }
        else if ($value < 50)
        {
            return 50;
        }
        else
        {
            return 100;
        }
    }

    function getLevelHigh($value)
    {
        if ($value < 10)
        {
            return "Level 1";
        }
        else if ($value < 50)
        {
            return "Level 2";
        }
        else if ($value < 100)
        {
            return "Level 3";
        }
        else if ($value < 500)
        {
            return "Level 4";
        }
        else
        {
            return "Level 5";
        }
    }

    function getLevelMedium($value)
    {
        if ($value < 10)
        {
            return "Level 1";
        }
        else if ($value < 50)
        {
            return "Level 2";
        }
        else if ($value < 100)
        {
            return "Level 3";
        }
        else if ($value < 200)
        {
            return "Level 4";
        }
        else
        {
            return "Level 5";
        }
    }

    function getLevelLow($value)
    {
        if ($value < 5)
        {
            return "Level 1";
        }
        else if ($value < 10)
        {
            return "Level 2";
        }
        else if ($value < 20)
        {
            return "Level 3";
        }
        else if ($value < 50)
        {
            return "Level 4";
        }
        else
        {
            return "Level 5";
        }
    }
?>

<p>Milestones for <?php echo $playerOne; ?></p> <!-- $runSelectedPlayerQuery -->
<h5>Games Played</h5>
    <?php
        $value = mysql_result($runSelectedPlayerQuery, 0, "games_played");
        $max = getMilestoneRangeMedium($value);
     ?>
     <table class="milestone_table">
        <tr>
            <td><meter value="<?php echo $value; ?>" min="0" max="<?php echo $max; ?>" class="milestone_meter"></meter></td>
            <td><div class="milestone_value"><?php if ($value < $max) { echo $value." / ".$max; } else { echo $max." / ".$max; } ?></div></td>
            <td><div class="milestone_medal"><?php if ($value < $max) { echo getLevelMedium($value); } else { echo "Binge Drinker"; } ?></div><?php if ($value >= $max) { ?><center><img src="imgs/binge_drinker.jpg" class="top_3" /></center><?php } ?></td>
        </tr>
    </table>

<h5>Wins</h5>
    <?php
        $value = mysql_result($runSelectedPlayerQuery, 0, "wins");
        $max = getMilestoneRangeMedium($value);
     ?>
     <table class="milestone_table">
        <tr>
            <td><meter value="<?php echo $value; ?>" min="0" max="<?php echo $max; ?>" class="milestone_meter"></meter></td>
            <td><div class="milestone_value"><?php if ($value < $max) { echo $value." / ".$max; } else { echo $max." / ".$max; } ?></div></td>
            <td><div class="milestone_medal"><?php if ($value < $max) { echo getLevelMedium($value); } else { echo "Superstar"; } ?></div><?php if ($value >= $max) { ?><center><img src="imgs/superstar.jpg" class="top_3" /></center><?php } ?></td>
        </tr>
    </table>

<h5>Cups Hit</h5>
    <?php
        $value = mysql_result($runSelectedPlayerQuery, 0, "hits");
        $max = getMilestoneRangeHigh($value);
     ?>
     <table class="milestone_table">
        <tr>
            <td><meter value="<?php echo $value; ?>" min="0" max="<?php echo $max; ?>" class="milestone_meter"></meter></td>
            <td><div class="milestone_value"><?php if ($value < $max) { echo $value." / ".$max; } else { echo $max." / ".$max; } ?></div></td>
            <td><div class="milestone_medal"><?php if ($value < $max) { echo getLevelHigh($value); } else { echo "Serial Killer"; } ?></div><?php if ($value >= $max) { ?><center><img src="imgs/serial_killer.jpg" class="top_3" /></center><?php } ?></td>
        </tr>
    </table>

<h5>Bounces Hit</h5>
    <?php
        $value = mysql_result($runSelectedPlayerQuery, 0, "bounces");
        $max = getMilestoneRangeLow($value);
     ?>
     <table class="milestone_table">
        <tr>
            <td><meter value="<?php echo $value; ?>" min="0" max="<?php echo $max; ?>" class="milestone_meter"></meter></td>
            <td><div class="milestone_value"><?php if ($value < $max) { echo $value." / ".$max; } else { echo $max." / ".$max; } ?></div></td>
            <td><div class="milestone_medal"><?php if ($value < $max) { echo getLevelLow($value); } else { echo "Magician"; } ?></div><?php if ($value >= $max) { ?><center><img src="imgs/magician.jpg" class="top_3" /></center><?php } ?></td>
        </tr>
    </table>

<h5>Redemption Successes</h5>
    <?php
        $value = mysql_result($runSelectedPlayerQuery, 0, "redemp_succs");
        $max = getMilestoneRangeLow($value);
     ?>
     <table class="milestone_table">
        <tr>
            <td><meter value="<?php echo $value; ?>" min="0" max="<?php echo $max; ?>" class="milestone_meter"></meter></td>
            <td><div class="milestone_value"><?php if ($value < $max) { echo $value." / ".$max; } else { echo $max." / ".$max; } ?></div></td>
            <td><div class="milestone_medal"><?php if ($value < $max) { echo getLevelLow($value); } else { echo "Immortal"; } ?></div><?php if ($value >= $max) { ?><center><img src="imgs/immortal.jpg" class="top_3" /></center><?php } ?></td>
        </tr>
    </table>

<h5>Last Cups Hit</h5>
    <?php
        $value = mysql_result($runSelectedPlayerQuery, 0, "h1");
        $max = getMilestoneRangeLow($value);
     ?>
     <table class="milestone_table">
        <tr>
            <td><meter value="<?php echo $value; ?>" min="0" max="<?php echo $max; ?>" class="milestone_meter"></meter></td>
            <td><div class="milestone_value"><?php if ($value < $max) { echo $value." / ".$max; } else { echo $max." / ".$max; } ?></div></td>
            <td><div class="milestone_medal"><?php if ($value < $max) { echo getLevelLow($value); } else { echo "Marksman"; } ?></div><?php if ($value >= $max) { ?><center><img src="imgs/marksman.jpg" class="top_3" /></center><?php } ?></td>
        </tr>
    </table>

<h5>Achievements Earned</h5>
    <?php
        $value = mysql_result($runSelectedPlayerQuery, 0, "a_shs") + mysql_result($runSelectedPlayerQuery, 0, "a_mj") + mysql_result($runSelectedPlayerQuery, 0, "a_cibav") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_hc") + mysql_result($runSelectedPlayerQuery, 0, "a_cwtpd") + mysql_result($runSelectedPlayerQuery, 0, "a_ps") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_per") + mysql_result($runSelectedPlayerQuery, 0, "a_dbno") + mysql_result($runSelectedPlayerQuery, 0, "a_mar") +
                 mysql_result($runSelectedPlayerQuery, 0, "a_ck") + mysql_result($runSelectedPlayerQuery, 0, "a_bb") + mysql_result($runSelectedPlayerQuery, 0, "a_bc") +
                 mysql_result($runSelectedPlayerQuery, 0, "a_bank") + mysql_result($runSelectedPlayerQuery, 0, "a_sw") + mysql_result($runSelectedPlayerQuery, 0, "a_fdm") + 
                 mysql_result($runSelectedPlayerQuery, 0, "a_skunk") + mysql_result($runSelectedPlayerQuery, 0, "ua_alc") + mysql_result($runSelectedPlayerQuery, 0, "ua_oah") + 
                 mysql_result($runSelectedPlayerQuery, 0, "ua_ce") + mysql_result($runSelectedPlayerQuery, 0, "ua_slip") + mysql_result($runSelectedPlayerQuery, 0, "ua_dciac") + 
                 mysql_result($runSelectedPlayerQuery, 0, "ua_dth") + mysql_result($runSelectedPlayerQuery, 0, "ua_show");
        $max = getMilestoneRangeLow($value);
     ?>
    <table class="milestone_table">
        <tr>
            <td><meter value="<?php echo $value; ?>" min="0" max="<?php echo $max; ?>" class="milestone_meter"></meter></td>
            <td><div class="milestone_value"><?php if ($value < $max) { echo $value." / ".$max; } else { echo $max." / ".$max; } ?></div></td>
            <td><div class="milestone_medal"><?php if ($value < $max) { echo getLevelLow($value); } else { echo "Seen It All"; } ?></div><?php if ($value >= $max) { ?><center><img src="imgs/seen_it_all.jpg" class="top_3" /></center><?php } ?></td>
        </tr>
    </table>


<p>Game results for <?php echo $playerOne; ?></p>
<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name"><a title="Team One" href="viewplayerprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_one_name&player_one=<?php echo $playerOne; ?>">Team One</a></td>
        <td class="stat_header"><a title="Team One Cups Remaining" href="viewplayerprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_one_cups_remaining&player_one=<?php echo $playerOne; ?>">Cups Remaining</a></td>
        <td class="stat_header_name"><a title="Team Two" href="viewplayerprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_two_name&player_one=<?php echo $playerOne; ?>">Team Two</a></td>
        <td class="stat_header"><a title="Team Two Cups Remaining" href="viewplayerprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=team_two_cups_remaining&player_one=<?php echo $playerOne; ?>">Cups Remaining</a></td>
        <td class="stat_header"><a title="Number Of Overtimes" href="viewplayerprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=number_of_ots&player_one=<?php echo $playerOne; ?>">Number Of OT's</a></td>
        <td class="stat_header"><a title="Winning Team" href="viewplayerprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=winning_team&player_one=<?php echo $playerOne; ?>">Winning Team</a></td>
        <td class="stat_header"><a title="Date Game Was Played" href="viewplayerprofiles_selected_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=date&player_one=<?php echo $playerOne; ?>">Date</a></td>
    </tr>

<?php

    if ($sortby == "team_one_name" or $sortby == "team_two_name" or $sortby == "winning_team")
    {
        $selectedGameQuery = "SELECT * FROM `games` WHERE `id_registrations`='".$id_registrations."' AND ((`team_one_player_one`='".$playerOne."') OR (`team_one_player_two`='".$playerOne."') OR (`team_two_player_one`='".$playerOne."') OR (`team_two_player_two`='".$playerOne."')) ORDER BY `".$sortby."` ASC";   
    }
    else
    {
        $selectedGameQuery = "SELECT * FROM `games` WHERE `id_registrations`='".$id_registrations."' AND ((`team_one_player_one`='".$playerOne."') OR (`team_one_player_two`='".$playerOne."') OR (`team_two_player_one`='".$playerOne."') OR (`team_two_player_two`='".$playerOne."')) ORDER BY `".$sortby."` DESC";  
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
?>

</table>

</div>

</div>

<div id="footer">Copyright &copy; 2013 Pong Champ. All Rights Reserved.</div>

</body>
</html>