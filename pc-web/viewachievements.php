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
    <li id="main_menu_leagueleaders"><a href="viewleagueleaders.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/leagueleaders.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_achievements"><a href="viewachievements.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/achievements_selected.png" /></a></li>
</ul>
</div>

<div id="main_info">
<p>Achievements</p>

<div id="note">*Note: Each table can be clicked on to expand the view to the entire league.</div>

<div class="achievement_title">
    The Good
</div>


<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=shs">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Sharpshooter</td>
        <td class="stat_header">Count</td>
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

	$SHSQuery = "SELECT `name`, `a_shs` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_shs` DESC";

	if ($runSHSQuery = mysql_query($SHSQuery))
	{
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_shs");

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
<td><blockquote>Hit five cups in a row in a game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=mj">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Michael Jordan</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_mj` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_mj` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_mj");

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
<td><blockquote>Go "On Fire" two or more times in a game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=cibav">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Can I Buy A Vowel?</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_cibav` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_cibav` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_cibav");

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
<td><blockquote>Sink all the cups for your team in a game (your team must win).</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=hc">    
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Heartbreak City</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_hc` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_hc` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_hc");

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
<td><blockquote>Win a game after being down by five or more cups.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=cwtpd">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Caught With Their Pants Down</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_cwtpd` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_cwtpd` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_cwtpd");

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
<td><blockquote>Hit two or more bounce shots in a game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=ps">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Porn Star</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_ps` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_ps` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_ps");

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
<td><blockquote>Hit two or more gangbangs in a game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=per">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Perfection</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_per` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_per` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_per");

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
<td><blockquote>Shoot one-hundred percent in a game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=dbno">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Down But Not Out</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_dbno` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_dbno` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_dbno");

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
<td><blockquote>As an individual, successfully complete two or more redemptions in a game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=mar">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Marathon</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_mar` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_mar` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_mar");

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
<td><blockquote>Compete in a game that goes to triple overtime.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=fdm">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">First Degree Murder</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_fdm` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_fdm` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_fdm");

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
<td><blockquote>Win a game before the other team gets a re-rack (10-cup start).</blockquote></td>
</tr>
</table>

<div class="achievement_title">
    The Bad
</div>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=ck">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Comeback Kill</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_ck` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_ck` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_ck");

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
<td><blockquote>Sink a cup after missing five or more shots in a row.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=bb">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Bill Buckner</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_bb` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_bb` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_bb");

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
<td><blockquote>Commit two or more errors in a game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=bc">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Bitch Cup</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_bc` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_bc` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_bc");

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
<td><blockquote>Hit the middle cup first on a ten-cup rack.</blockquote></td>
</tr>
</table>

<div class="achievement_title">
    The Ugly
</div>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=bank">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Bankruptcy</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_bank` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_bank` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_bank");

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
<td><blockquote>Sink no cups in a game.</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=skunk">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Skunked</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_skunk` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_skunk` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_skunk");

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
<td><blockquote>Lose a game before getting a re-rack (10-cup start).</blockquote></td>
</tr>
</table>

<table class="achievement_group">
<tr>
<td>
<a href="viewachievements_detail.php?username=<?php echo $username; ?>&sortby=sw">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Stevie Wonder</td>
        <td class="stat_header">Count</td>
    </tr>

<?php

    $SHSQuery = "SELECT `name`, `a_sw` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_sw` DESC";

    if ($runSHSQuery = mysql_query($SHSQuery))
    {
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    mysql_close();

    $i = 0;

    while ($i < 3)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_sw");

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
<td><blockquote>Miss ten shots in a row in a game.</blockquote></td>
</tr>
</table>

</div>

</div>

<div id="footer">Copyright &copy; 2013 Pong Champ. All Rights Reserved.</div>

</body>
</html>