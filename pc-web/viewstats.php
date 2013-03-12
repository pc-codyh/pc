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
    <li id="main_menu_playerstats"><a href="viewstats.php?username=<?php echo $username; ?>&submit=Submit"><img src="imgs/playerstats_selected.png" /></a></li>
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
<p>Win/Loss records, shooting percentages, and shot-types hit.</p>
<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Name</td>
        <td class="stat_header"><a title="Games Played" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=games_played">GP</a></td>
        <td class="stat_header"><a title="Wins" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=wins">W</a></td>
        <td class="stat_header"><a title="Losses" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=losses">L</a></td>
        <td class="stat_header"><a title="Overtime Losses" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=ot_losses">OTL</a></td>
        <td class="stat_header"><a title="Overtime Games Played" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=ot_games_played">OTGP</a></td>
        <td class="stat_header"><a title="Cup Differential" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=cup_dif">+/-</a></td>
        <td class="stat_header"><a title="Shots" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=shots">Shots</a></td>
        <td class="stat_header"><a title="Hits" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=hits">Hits</a></td>
        <td class="stat_header"><a title="Shooting Percentage" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=shooting_percentage">Shooting %</a></td>
        <td class="stat_header"><a title="Bounces" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=bounces">Bounces</a></td>
        <td class="stat_header"><a title="Gang-Bangs" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=gang_bangs">Gang-Bangs</a></td>
        <td class="stat_header"><a title="Errors" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=errors">Errors</a></td>
        <td class="stat_header"><a title="Heating Up" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=heating_up">Heating Up</a></td> 
        <td class="stat_header"><a title="On Fire" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=on_fire">On Fire</a></td>   
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

	$playerQuery = "SELECT * FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `name` ASC";

	if ($runPlayerQuery = mysql_query($playerQuery))
	{
        $playerQueryNumRows = mysql_num_rows($runPlayerQuery);
    }

    mysql_close();

    $i = 0;

    while ($i < $playerQueryNumRows)
    {
        $f1 = mysql_result($runPlayerQuery, $i, "name");
        $f2 = mysql_result($runPlayerQuery, $i, "games_played");
        $f3 = mysql_result($runPlayerQuery, $i, "wins");
        $f4 = mysql_result($runPlayerQuery, $i, "losses");
        $f5 = mysql_result($runPlayerQuery, $i, "ot_losses");
        $f6 = mysql_result($runPlayerQuery, $i, "cup_dif");
        $f7 = mysql_result($runPlayerQuery, $i, "shots");
        $f8 = mysql_result($runPlayerQuery, $i, "hits");
        $f9 = number_format(mysql_result($runPlayerQuery, $i, "shooting_percentage") * 100, 2, '.', '');
        $f10 = mysql_result($runPlayerQuery, $i, "bounces");
        $f11 = mysql_result($runPlayerQuery, $i, "gang_bangs");
        $f12 = mysql_result($runPlayerQuery, $i, "errors");
        $f13 = mysql_result($runPlayerQuery, $i, "heating_up");
        $f14 = mysql_result($runPlayerQuery, $i, "on_fire");
        $f100 = mysql_result($runPlayerQuery, $i, "ot_games_played");
        
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
    <td class="stat_column"><?php echo $f100; ?></td>
    <td class="stat_column"><?php echo $f6; ?></td>
    <td class="stat_column"><?php echo $f7; ?></td>
    <td class="stat_column"><?php echo $f8; ?></td>
    <td class="stat_column"><?php echo $f9; ?></td>
    <td class="stat_column"><?php echo $f10; ?></td>
    <td class="stat_column"><?php echo $f11; ?></td>
    <td class="stat_column"><?php echo $f12; ?></td>
    <td class="stat_column"><?php echo $f13; ?></td>
    <td class="stat_column"><?php echo $f14; ?></td>
</tr>

<?php

        $i++;
    }
?>
</table>

<p>Win/Loss streaks, hit/miss streaks, and overall rank.</p>
<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Name</td>
        <td class="stat_header"><a title="Highest Win Streak" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=win_streak">Win Streak</a></td>
        <td class="stat_header"><a title="Highest Loss Streak" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=loss_streak">Loss Streak</a></td>
        <td class="stat_header"><a title="Highest Hit Streak" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=hit_streak">Hit Streak</a></td>
        <td class="stat_header"><a title="Highest Miss Streak" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=miss_streak">Miss Streak</a></td>
        <td class="stat_header"><a title="Elo Rating" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=elo_rating">Elo Rating</a></td>
        <td class="stat_header"><a title="Compound Rating" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=compound_rating">Compound Rating</a></td>
        <td class="stat_header"><a title="Overall Rank" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=rank">Rank</a></td>
    </tr> 
    
<?php

    $i = 0;

    while ($i < $playerQueryNumRows)
    {
        $f1 = mysql_result($runPlayerQuery, $i, "name");
        $f15 = mysql_result($runPlayerQuery, $i, "win_streak");
        $f16 = mysql_result($runPlayerQuery, $i, "loss_streak");
        $f17 = mysql_result($runPlayerQuery, $i, "hit_streak");
        $f18 = mysql_result($runPlayerQuery, $i, "miss_streak");
        $f19 = mysql_result($runPlayerQuery, $i, "elo_rating");

        $f19 = number_format($f19, 2, '.', '');

        $f20 = mysql_result($runPlayerQuery, $i, "compound_rating");

        $f20 = number_format($f20, 2, '.', '');

        $f21 = mysql_result($runPlayerQuery, $i, "rank");
        
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
    <td class="stat_column"><?php echo $f15; ?></td>
    <td class="stat_column"><?php echo $f16; ?></td>
    <td class="stat_column"><?php echo $f17; ?></td>
    <td class="stat_column"><?php echo $f18; ?></td>
    <td class="stat_column"><?php echo $f19; ?></td>
    <td class="stat_column"><?php echo $f20; ?></td>
    <td class="stat_column"><?php echo $f21; ?></td>
</tr>

<?php

        $i++;
    }
?>

</table>

<p>Redemption statistics.</p>
<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Name</td>
        <td class="stat_header"><a title="Redemption Shots" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=redemp_shots">Redemption Shots</a></td>
        <td class="stat_header"><a title="Redemption Hits" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=redemp_hits">Redemption Hits</a></td>
        <td class="stat_header"><a title="Redemption Shooting Percentage" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=redemp_shotperc">Redemption Shooting %</a></td>
        <td class="stat_header"><a title="Redemption Attempts" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=redemp_atmps">Redemption Attempts</a></td>
        <td class="stat_header"><a title="Redemption Successes" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=redemp_succs">Redemption Successes</a></td>
    </tr>
    
<?php

    $i = 0;

    while ($i < $playerQueryNumRows)
    {
        $f1 = mysql_result($runPlayerQuery, $i, "name");
        $f22 = mysql_result($runPlayerQuery, $i, "redemp_shots");
        $f23 = mysql_result($runPlayerQuery, $i, "redemp_hits");
        $f24 = mysql_result($runPlayerQuery, $i, "redemp_shotperc") * 100;
        
        $f24 = number_format($f24, 2, '.', '');
        
        $f25 = mysql_result($runPlayerQuery, $i, "redemp_atmps");
        $f26 = mysql_result($runPlayerQuery, $i, "redemp_succs");

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
    <td class="stat_column"><?php echo $f22; ?></td>
    <td class="stat_column"><?php echo $f23; ?></td>
    <td class="stat_column"><?php echo $f24; ?></td>
    <td class="stat_column"><?php echo $f25; ?></td>
    <td class="stat_column"><?php echo $f26; ?></td>
</tr>

<?php

        $i++;
    }
?>

</table>

<p>Shooting percentages for each rack (number of cups remaining).</p>
<table class="stats_table" cellspacing="0" cellpadding="5">
    <tr>
        <td class="stat_header_name">Name</td>
        <td class="stat_header"><a title="Ten Cups" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=p10">10</a></td>
        <td class="stat_header"><a title="Nine Cups" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=p9">9</a></td>
        <td class="stat_header"><a title="Eight Cups" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=p8">8</a></td>
        <td class="stat_header"><a title="Seven Cups" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=p7">7</a></td>
        <td class="stat_header"><a title="Six Cups" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=p6">6</a></td>
        <td class="stat_header"><a title="Five Cups" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=p5">5</a></td>
        <td class="stat_header"><a title="Four Cups" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=p4">4</a></td>
        <td class="stat_header"><a title="Three Cups" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=p3">3</a></td>
        <td class="stat_header"><a title="Two Cups" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=p2">2</a></td>
        <td class="stat_header"><a title="One Cup" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=p1">Last Cup</a></td>
        <td class="stat_header"><a title="Last Cups Hit" href="viewstats_sorted.php?username=<?php echo $username; ?>&submit=Submit&sortby=h1">Last Cups Hit</a></td>
    </tr>
    
<?php

    $i = 0;

    while ($i < $playerQueryNumRows)
    {
        $f1 = mysql_result($runPlayerQuery, $i, "name");
        $f27 = number_format(mysql_result($runPlayerQuery, $i, "p10") * 100, 2, '.', '');
        $f28 = number_format(mysql_result($runPlayerQuery, $i, "p9") * 100, 2, '.', '');
        $f29 = number_format(mysql_result($runPlayerQuery, $i, "p8") * 100, 2, '.', '');
        $f30 = number_format(mysql_result($runPlayerQuery, $i, "p7") * 100, 2, '.', '');
        $f31 = number_format(mysql_result($runPlayerQuery, $i, "p6") * 100, 2, '.', '');
        $f32 = number_format(mysql_result($runPlayerQuery, $i, "p5") * 100, 2, '.', '');
        $f33 = number_format(mysql_result($runPlayerQuery, $i, "p4") * 100, 2, '.', '');
        $f34 = number_format(mysql_result($runPlayerQuery, $i, "p3") * 100, 2, '.', '');
        $f35 = number_format(mysql_result($runPlayerQuery, $i, "p2") * 100, 2, '.', '');
        $f36 = number_format(mysql_result($runPlayerQuery, $i, "p1") * 100, 2, '.', '');
        $f37 = mysql_result($runPlayerQuery, $i, "h1");

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
    <td class="stat_column"><?php echo $f27; ?></td>
    <td class="stat_column"><?php echo $f28; ?></td>
    <td class="stat_column"><?php echo $f29; ?></td>
    <td class="stat_column"><?php echo $f30; ?></td>
    <td class="stat_column"><?php echo $f31; ?></td>
    <td class="stat_column"><?php echo $f32; ?></td>
    <td class="stat_column"><?php echo $f33; ?></td>
    <td class="stat_column"><?php echo $f34; ?></td>
    <td class="stat_column"><?php echo $f35; ?></td>
    <td class="stat_column"><?php echo $f36; ?></td>
    <td class="stat_column"><?php echo $f37; ?></td>
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