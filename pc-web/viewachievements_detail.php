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
<p>
<?php 
    $achievementInDetail = "";

    if ($sortby == "shs")
    {
        $achievementInDetail = "Sharpshooter";
    }
    else if ($sortby == "mj")
    {
        $achievementInDetail = "Michael Jordan";
    }
    else if ($sortby == "cibav")
    {
        $achievementInDetail = "Can I Buy A Vowel?";
    }
    else if ($sortby == "hc")
    {
        $achievementInDetail = "Heartbreak City";
    }
    else if ($sortby == "cwtpd")
    {
        $achievementInDetail = "Caught With Their Pants Down";
    }
    else if ($sortby == "ps")
    {
        $achievementInDetail = "Porn Star";
    }
    else if ($sortby == "per")
    {
        $achievementInDetail = "Perfection";
    }
    else if ($sortby == "dbno")
    {
        $achievementInDetail = "Down But Not Out";
    }
    else if ($sortby == "mar")
    {
        $achievementInDetail = "Marathon";
    }
    else if ($sortby == "fdm")
    {
        $achievementInDetail = "First Degree Murder";
    }
    else if ($sortby == "ck")
    {
        $achievementInDetail = "Comeback Kill";
    }
    else if ($sortby == "bb")
    {
        $achievementInDetail = "Bill Buckner";
    }
    else if ($sortby == "bc")
    {
        $achievementInDetail = "Bitch Cup";
    }
    else if ($sortby == "bank")
    {
        $achievementInDetail = "Bankruptcy";
    }
    else if ($sortby == "skunk")
    {
        $achievementInDetail = "Skunked";
    }
    else if ($sortby == "sw")
    {
        $achievementInDetail = "Stevie Wonder";
    }

    echo $achievementInDetail." in detail.";
?>
</p>


<table class="achievement_group">
<tr>
<td>
<table class="stats_table_ach achievement_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Row</td>
        <td class="stat_header_name"><?php echo $achievementInDetail; ?></td>
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

	$SHSQuery = "SELECT * FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `a_".$sortby."` DESC";

	if ($runSHSQuery = mysql_query($SHSQuery))
	{
        $SHSQueryNumRows = mysql_num_rows($runSHSQuery);
    }

    $i = 0;

    while ($i < $SHSQueryNumRows)
    {
        $f1 = mysql_result($runSHSQuery, $i, "name");
        $f2 = mysql_result($runSHSQuery, $i, "a_".$sortby);

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
</td>
<td><blockquote><?php echo "Overall standings for the ".$achievementInDetail." achievement." ?></blockquote></td>
</tr>
</table>


</div>

</div>

<div id="footer">Copyright &copy; 2013 Pong Champ. All Rights Reserved.</div>

</body>
</html>