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

	/*$teamQuery = "SELECT * FROM `teams` WHERE `id_registrations`='".$id_registrations."' ORDER BY `team_name` ASC";*/
	$teamQuery = "SELECT `name` from `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `name` ASC";

	if ($runTeamQuery = mysql_query($teamQuery))
	{
        $teamQueryNumRows = mysql_num_rows($runTeamQuery);
    }

    mysql_close();

    $i = 0;

    while ($i < $teamQueryNumRows)
    {
        /*$f1 = mysql_result($runTeamQuery, $i, "player_one");*/
        $f1 = mysql_result($runTeamQuery, $i, "name"); 
?>

<option value="<?php echo $f1; ?>"><?php echo $f1; ?></option>

<?php

        $i++;
    }
?>

</select>

<select name="player_two">

<?php

$i = 0;

    while ($i < $teamQueryNumRows)
    {
        /*$f1 = mysql_result($runTeamQuery, $i, "player_two");*/
        $f1 = mysql_result($runTeamQuery, $i, "name");
?>

<option value="<?php echo $f1; ?>"><?php echo $f1; ?></option>

<?php

        $i++;
    }
}
?>

</select>

<input type="submit" name="submit_team" value="View Profile" class="submit_team_button" />
<input type="hidden" name="username" value="<?php echo $username; ?>" />
</form>

</div>

</div>

<div id="footer">Copyright &copy; 2013 Pong Champ. All Rights Reserved.</div>

</body>
</html>