<?php

$username = $_GET['username'];

$sort1 = $_GET['sort1'];
$sort2 = $_GET['sort2'];

$multiplier = 1;

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
<p>
<?php 
    $leagueLeaderInDetail = "";

    if ($sort1 == "hits" && $sort2 == "shots")
    {
        $leagueLeaderInDetail = "Shooting Percentage";
        $multiplier = 100;
    }
    else if ($sort1 == "wins" && $sort2 == "games_played")
    {
        $leagueLeaderInDetail = "Winning Percentage";
        $multiplier = 100;
    }
    else if ($sort1 == "hits" && $sort2 == "games_played")
    {
        $leagueLeaderInDetail = "Hits / Game";
    }
    else if ($sort1 == "bounces" && $sort2 == "games_played")
    {
        $leagueLeaderInDetail = "Bounces / Game";
    }
    else if ($sort1 == "heating_up" && $sort2 == "games_played")
    {
        $leagueLeaderInDetail = "Heating Up / Game";
    }
    else if ($sort1 == "on_fire" && $sort2 == "games_played")
    {
        $leagueLeaderInDetail = "On Fire / Game";
    }
    else if ($sort1 == "on_fire" && $sort2 == "heating_up")
    {
        $leagueLeaderInDetail = "On Fire Conversion Rate";
        $multiplier = 100;
    }
    else if ($sort1 == "h1" && $sort2 == "games_played")
    {
        $leagueLeaderInDetail = "Last Cups / Game";
    }
    else if ($sort1 == "redemp_succs" && $sort2 == "redemp_atmps")
    {
        $leagueLeaderInDetail = "Redemption Conversion Rate";
        $multiplier = 100;
    }

    echo $leagueLeaderInDetail." in detail.";
?>
</p>

<table class="achievement_group">
<tr>
<td>
<a href="viewleagueleaders_detail.php?username=<?php echo $username; ?>&sort1=hits&sort2=shots">
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Row</td>
        <td class="stat_header_name"><?php echo $leagueLeaderInDetail; ?></td>
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

	$SHSQuery = "SELECT `name`, `".$sort1."` / `".$sort2."` AS 'category' FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `category` DESC";

	if ($runSHSQuery = mysql_query($SHSQuery))
	{
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = number_format(mysql_result($runSHSQuery, $i, "category") * $multiplier, 2, '.', '');

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

    <td><?php echo ($i + 1); ?></td>
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
<td><blockquote><center><?php echo "Overall standings for ".$leagueLeaderInDetail."."; ?></center></blockquote></td>
</tr>
</table>

</div>

</div>

<div id="footer">Copyright &copy; 2013 Pong Champ. All Rights Reserved.</div>

</body>
</html>