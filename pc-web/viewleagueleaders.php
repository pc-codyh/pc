<?php

$username = $_GET['username'];

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
    <li id="main_menu_gameresults"><a href="viewresults.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/gameresults.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_headtohead"><a href="viewheadtohead.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/headtohead.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_leagueleaders"><a href="viewleagueleaders.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/leagueleaders_selected.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_achievements"><a href="viewachievements.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/achievements.png" /></a></li>
</ul>
</div>

<div id="main_info">
<p>League Leaders</p>

<div id="note">*Note: Each table can be clicked on to expand the view to the entire league.</div>

<table class="achievement_group">
<tr>
<td>
<a href="viewleagueleaders_detail.php?username=<?php echo $username; ?>&sort1=hits&sort2=shots">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Shooting Percentage</td>
        <td class="stat_header"></td>
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

	$SHSQuery = "SELECT `name`, `shooting_percentage` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `shooting_percentage` DESC";

	if ($runSHSQuery = mysql_query($SHSQuery))
	{
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = number_format(mysql_result($runSHSQuery, $i, "shooting_percentage") * 100, 2, '.', '');

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

    <td><a href="viewplayerprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo $f1; ?>"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
}
?>
</table>
</a>
</td>
<td><blockquote>Overall shooting percentage.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewleagueleaders_detail.php?username=<?php echo $username; ?>&sort1=wins&sort2=games_played">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Winning Percentage</td>
        <td class="stat_header"></td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `wins`, `games_played` FROM `players` WHERE `id_registrations`='".$id_registrations."'";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $firstHighest = 0;
    $firstHighestName = "";
    $secondHighest = 0;
    $secondHighestName = "";
    $thirdHighest = 0;
    $thirdHighestName = "";
    $value = 0;
    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        if (mysql_result($runSHSQuery, $i, "games_played") > 0)
        {
            $value = (mysql_result($runSHSQuery, $i, "wins") / mysql_result($runSHSQuery, $i, "games_played"));

            if ($value >= $firstHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $firstHighest;
                $firstHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = $firstHighestName;
                $firstHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $secondHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $thirdHighest)
            {
                $thirdHighest = $value;

                $thirdHighestName = mysql_result($runSHSQuery, $i, "name");
            }
        }

        $i++;
    }

    $nameArray = array($firstHighestName, $secondHighestName, $thirdHighestName);
    $valueArray = array($firstHighest, $secondHighest, $thirdHighest);

    $i = 0;

    while ($i < 3)
    {
        $f1 = $nameArray[$i];
        $f2 = number_format($valueArray[$i] * 100, 2, '.', '');

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

    <td><a href="viewplayerprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo $f1; ?>"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>
</a>
</td>
<td><blockquote>Overall winning percentage.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewleagueleaders_detail.php?username=<?php echo $username; ?>&sort1=hits&sort2=games_played">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Hits / Game</td>
        <td class="stat_header"></td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `hits`, `games_played` FROM `players` WHERE `id_registrations`='".$id_registrations."'";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $firstHighest = 0;
    $firstHighestName = "";
    $secondHighest = 0;
    $secondHighestName = "";
    $thirdHighest = 0;
    $thirdHighestName = "";
    $value = 0;
    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        if (mysql_result($runSHSQuery, $i, "games_played") > 0)
        {
            $value = (mysql_result($runSHSQuery, $i, "hits") / mysql_result($runSHSQuery, $i, "games_played"));

            if ($value >= $firstHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $firstHighest;
                $firstHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = $firstHighestName;
                $firstHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $secondHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $thirdHighest)
            {
                $thirdHighest = $value;

                $thirdHighestName = mysql_result($runSHSQuery, $i, "name");
            }
        }

        $i++;
    }

    $nameArray = array($firstHighestName, $secondHighestName, $thirdHighestName);
    $valueArray = array($firstHighest, $secondHighest, $thirdHighest);

    $i = 0;

    while ($i < 3)
    {
        $f1 = $nameArray[$i];
        $f2 = number_format($valueArray[$i], 2, '.', '');

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

    <td><a href="viewplayerprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo $f1; ?>"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>
</a>
</td>
<td><blockquote>Average number of cups made per game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewleagueleaders_detail.php?username=<?php echo $username; ?>&sort1=bounces&sort2=games_played">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Bounces / Game</td>
        <td class="stat_header"></td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `bounces`, `games_played` FROM `players` WHERE `id_registrations`='".$id_registrations."'";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $firstHighest = 0;
    $firstHighestName = "";
    $secondHighest = 0;
    $secondHighestName = "";
    $thirdHighest = 0;
    $thirdHighestName = "";
    $value = 0;
    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        if (mysql_result($runSHSQuery, $i, "games_played") > 0)
        {
            $value = (mysql_result($runSHSQuery, $i, "bounces") / mysql_result($runSHSQuery, $i, "games_played"));

            if ($value >= $firstHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $firstHighest;
                $firstHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = $firstHighestName;
                $firstHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $secondHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $thirdHighest)
            {
                $thirdHighest = $value;

                $thirdHighestName = mysql_result($runSHSQuery, $i, "name");
            }
        }

        $i++;
    }

    $nameArray = array($firstHighestName, $secondHighestName, $thirdHighestName);
    $valueArray = array($firstHighest, $secondHighest, $thirdHighest);

    $i = 0;

    while ($i < 3)
    {
        $f1 = $nameArray[$i];
        $f2 = number_format($valueArray[$i], 2, '.', '');

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

    <td><a href="viewplayerprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo $f1; ?>"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>
</a>
</td>
<td><blockquote>Average number of bounce shots made per game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewleagueleaders_detail.php?username=<?php echo $username; ?>&sort1=heating_up&sort2=games_played">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Heating Up / Game</td>
        <td class="stat_header"></td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `heating_up`, `games_played` FROM `players` WHERE `id_registrations`='".$id_registrations."'";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $firstHighest = 0;
    $firstHighestName = "";
    $secondHighest = 0;
    $secondHighestName = "";
    $thirdHighest = 0;
    $thirdHighestName = "";
    $value = 0;
    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        if (mysql_result($runSHSQuery, $i, "games_played") > 0)
        {
            $value = (mysql_result($runSHSQuery, $i, "heating_up") / mysql_result($runSHSQuery, $i, "games_played"));

            if ($value >= $firstHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $firstHighest;
                $firstHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = $firstHighestName;
                $firstHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $secondHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $thirdHighest)
            {
                $thirdHighest = $value;

                $thirdHighestName = mysql_result($runSHSQuery, $i, "name");
            }
        }

        $i++;
    }

    $nameArray = array($firstHighestName, $secondHighestName, $thirdHighestName);
    $valueArray = array($firstHighest, $secondHighest, $thirdHighest);

    $i = 0;

    while ($i < 3)
    {
        $f1 = $nameArray[$i];
        $f2 = number_format($valueArray[$i], 2, '.', '');

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

    <td><a href="viewplayerprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo $f1; ?>"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>
</a>
</td>
<td><blockquote>Average number of times "Heating Up" per game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewleagueleaders_detail.php?username=<?php echo $username; ?>&sort1=on_fire&sort2=games_played">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">On Fire / Game</td>
        <td class="stat_header"></td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `on_fire`, `games_played` FROM `players` WHERE `id_registrations`='".$id_registrations."'";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $firstHighest = 0;
    $firstHighestName = "";
    $secondHighest = 0;
    $secondHighestName = "";
    $thirdHighest = 0;
    $thirdHighestName = "";
    $value = 0;
    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        if (mysql_result($runSHSQuery, $i, "games_played") > 0)
        {
            $value = (mysql_result($runSHSQuery, $i, "on_fire") / mysql_result($runSHSQuery, $i, "games_played"));

            if ($value >= $firstHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $firstHighest;
                $firstHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = $firstHighestName;
                $firstHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $secondHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $thirdHighest)
            {
                $thirdHighest = $value;

                $thirdHighestName = mysql_result($runSHSQuery, $i, "name");
            }
        }

        $i++;
    }

    $nameArray = array($firstHighestName, $secondHighestName, $thirdHighestName);
    $valueArray = array($firstHighest, $secondHighest, $thirdHighest);

    $i = 0;

    while ($i < 3)
    {
        $f1 = $nameArray[$i];
        $f2 = number_format($valueArray[$i], 2, '.', '');

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

    <td><a href="viewplayerprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo $f1; ?>"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>
</a>
</td>
<td><blockquote>Average number of times "On Fire" per game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewleagueleaders_detail.php?username=<?php echo $username; ?>&sort1=on_fire&sort2=heating_up">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">On Fire Conversion Rate</td>
        <td class="stat_header"></td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `heating_up`, `on_fire` FROM `players` WHERE `id_registrations`='".$id_registrations."'";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $firstHighest = 0;
    $firstHighestName = "";
    $secondHighest = 0;
    $secondHighestName = "";
    $thirdHighest = 0;
    $thirdHighestName = "";
    $value = 0;
    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        if (mysql_result($runSHSQuery, $i, "heating_up") > 0)
        {
            $value = (mysql_result($runSHSQuery, $i, "on_fire") / mysql_result($runSHSQuery, $i, "heating_up"));

            if ($value >= $firstHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $firstHighest;
                $firstHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = $firstHighestName;
                $firstHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $secondHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $thirdHighest)
            {
                $thirdHighest = $value;

                $thirdHighestName = mysql_result($runSHSQuery, $i, "name");
            }
        }

        $i++;
    }

    $nameArray = array($firstHighestName, $secondHighestName, $thirdHighestName);
    $valueArray = array($firstHighest, $secondHighest, $thirdHighest);

    $i = 0;

    while ($i < 3)
    {
        $f1 = $nameArray[$i];
        $f2 = number_format($valueArray[$i] * 100, 2, '.', '');

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

    <td><a href="viewplayerprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo $f1; ?>"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>
</a>
</td>
<td><blockquote>Percentage that you go "On Fire" after "Heating Up".</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewleagueleaders_detail.php?username=<?php echo $username; ?>&sort1=h1&sort2=games_played">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Last Cups / Game</td>
        <td class="stat_header"></td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `h1`, `games_played` FROM `players` WHERE `id_registrations`='".$id_registrations."'";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $firstHighest = 0;
    $firstHighestName = "";
    $secondHighest = 0;
    $secondHighestName = "";
    $thirdHighest = 0;
    $thirdHighestName = "";
    $value = 0;
    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        if (mysql_result($runSHSQuery, $i, "games_played") > 0)
        {
            $value = (mysql_result($runSHSQuery, $i, "h1") / mysql_result($runSHSQuery, $i, "games_played"));

            if ($value >= $firstHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $firstHighest;
                $firstHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = $firstHighestName;
                $firstHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $secondHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $thirdHighest)
            {
                $thirdHighest = $value;

                $thirdHighestName = mysql_result($runSHSQuery, $i, "name");
            }
        }

        $i++;
    }

    $nameArray = array($firstHighestName, $secondHighestName, $thirdHighestName);
    $valueArray = array($firstHighest, $secondHighest, $thirdHighest);

    $i = 0;

    while ($i < 3)
    {
        $f1 = $nameArray[$i];
        $f2 = number_format($valueArray[$i], 2, '.', '');

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

    <td><a href="viewplayerprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo $f1; ?>"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>
</a>
</td>
<td><blockquote>Average number of last cups made per game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewleagueleaders_detail.php?username=<?php echo $username; ?>&sort1=redemp_succs&sort2=redemp_atmps">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Redemption Conversion Rate</td>
        <td class="stat_header"></td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `redemp_atmps`, `redemp_succs` FROM `players` WHERE `id_registrations`='".$id_registrations."'";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $firstHighest = 0;
    $firstHighestName = "";
    $secondHighest = 0;
    $secondHighestName = "";
    $thirdHighest = 0;
    $thirdHighestName = "";
    $value = 0;
    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        if (mysql_result($runSHSQuery, $i, "redemp_atmps") > 0)
        {
            $value = (mysql_result($runSHSQuery, $i, "redemp_succs") / mysql_result($runSHSQuery, $i, "redemp_atmps"));

            if ($value >= $firstHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $firstHighest;
                $firstHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = $firstHighestName;
                $firstHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $secondHighest)
            {
                $thirdHighest = $secondHighest;
                $secondHighest = $value;

                $thirdHighestName = $secondHighestName;
                $secondHighestName = mysql_result($runSHSQuery, $i, "name");
            }
            else if ($value >= $thirdHighest)
            {
                $thirdHighest = $value;

                $thirdHighestName = mysql_result($runSHSQuery, $i, "name");
            }
        }

        $i++;
    }

    $nameArray = array($firstHighestName, $secondHighestName, $thirdHighestName);
    $valueArray = array($firstHighest, $secondHighest, $thirdHighest);

    $i = 0;

    while ($i < 3)
    {
        $f1 = $nameArray[$i];
        $f2 = number_format($valueArray[$i] * 100, 2, '.', '');

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

    <td><a href="viewplayerprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo $f1; ?>"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>
</a>
</td>
<td><blockquote>Percentage that you successfully send the game into overtime.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Overtime Records</td>
        <td class="stat_header">W</td>
        <td class="stat_header">L</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `ot_games_played`, `ot_losses`, (`ot_games_played` - `ot_losses`) / `ot_games_played` AS 'ot_win_perc' FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `ot_win_perc` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "ot_games_played") - mysql_result($runSHSQuery, $i, "ot_losses");
        $f3 = mysql_result($runSHSQuery, $i, "ot_losses");

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

    <td><a href="viewplayerprofiles_selected.php?username=<?php echo $username; ?>&submit=Submit&player_one=<?php echo $f1; ?>"><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
    <td class="stat_column"><?php echo $f3; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>
</td>
<td><blockquote>Overall overtime records.</blockquote></td>
</tr>
</table>

</div>

</div>

<div id="footer">Copyright &copy; 2013 Pong Champ. All Rights Reserved.</div>

</body>
</html>