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
    <li id="main_menu_teamstats"><a href="viewteamstats.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/teamstats.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_teamprofiles"><a href="viewteamprofiles.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/teamprofiles.png" /></a></li>
</ul>
<ul>
    <li id="main_menu_gameresults"><a href="viewresults.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/gameresults.png" /></a></li>
</ul>
</div>

<div id="main_info">
<p>Achievements</p>
<table class="stats_table" cellspacing="0" cellpadding="5">
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

    <td><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
}
?>
</table>

<table class="stats_table" cellspacing="0" cellpadding="5">
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

    mysql_close();

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

    <td><?php echo $f1; ?></td>
    <td class="stat_column"><?php echo $f2; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>

</div>

</div>

<div id="footer">Copyright &copy; 2013 Pong Champ. All Rights Reserved.</div>

</body>
</html>