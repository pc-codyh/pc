<?php

// Every visible page requires these two files.
require 'connect.inc.php';

session_start();

$is_logged_in = 0;
$count = 0;
$i = 0;

if (isset($_SESSION['username']))
{
	$is_logged_in = 1;

	$recent_results_query = 'SELECT * FROM `games` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `id` DESC';

	if ($run_recent_results_query = mysql_query($recent_results_query))
	{
		$count = mysql_num_rows($run_recent_results_query);

		if ($count > 10)
		{
			$count = 10;
		}
	}
}

?>

<html>
<head>
<title></title>
<link rel="stylesheet" href="css/pc.css" />
<link href='http://fonts.googleapis.com/css?family=Alegreya+Sans+SC' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!-- <script type="text/javascript" src="js/marquee.js"></script> -->
<script type="text/javascript" src="js/jquery.marquee.js"></script>
<script type="text/javascript">
	$(document).ready(function ()
	{
		$("#marquee").marquee();
	});
</script>
<link rel="stylesheet" href="css/jquery.marquee.css" />
</head>
<body>
	<?php require 'topmenu.inc.php'; ?>
	<div id="bottom">
		<div id="bottom_left">
			<?php require 'sidemenu.inc.php'; ?>
		</div>
		<div id="bottom_right">
			<?php if ($is_logged_in) { ?>
				<?php
					include 'whatsnew.inc.php';

					$totalGamesPlayed = 0;
					$totalOTGamesPlayed = 0;
					$totalMultiOTGamesPlayed = 0;
					$totalBeersDrank = 0;
					$totalBeersSpilled = 0;
					$mostGamesInOneDay = 0;
					$mostGamesInOneDayDate = '';
					$leagueShootingPerc = 0;

					$games_played_query = 'SELECT COUNT(*) FROM `games` WHERE `id_registrations`="'.$_SESSION['userid'].'"';
					$ot_games_played_query = 'SELECT COUNT(*) FROM `games` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `number_of_ots`>0';
					$multi_ot_games_played_query = 'SELECT COUNT(*) FROM `games` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `number_of_ots`>1';
					$beers_drank_query = 'SELECT SUM(`hits`) / 5 as beers_drank FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'"';
					$beers_spilled_query = 'SELECT SUM(`errors`) as beers_spilled FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'"';
					$most_games_in_day_query = 'SELECT  `date` , COUNT( * ) AS daily_games FROM  `games` WHERE  `id_registrations`="'.$_SESSION['userid'].'" GROUP BY  `date`';
					$league_shooting_perc_query = 'SELECT AVG(`shooting_percentage`) FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'"';

					if ($run_games_played_query = mysql_query($games_played_query))
					{
						$totalGamesPlayed = mysql_result($run_games_played_query, 0);
					}

					if ($run_ot_games_played_query = mysql_query($ot_games_played_query))
					{
						$totalOTGamesPlayed = mysql_result($run_ot_games_played_query, 0);
					}

					if ($run_multi_ot_games_played_query = mysql_query($multi_ot_games_played_query))
					{
						$totalMultiOTGamesPlayed = mysql_result($run_multi_ot_games_played_query, 0);
					}

					if ($run_beers_drank_query = mysql_query($beers_drank_query))
					{
						$totalBeersDrank =intval(mysql_result($run_beers_drank_query, 0));
					}

					if ($run_beers_spilled_query = mysql_query($beers_spilled_query))
					{
						$totalBeersSpilled =intval(mysql_result($run_beers_spilled_query, 0));
					}

					if ($run_most_games_in_day_query = mysql_query($most_games_in_day_query))
					{
						$numRows = mysql_num_rows($run_most_games_in_day_query);

						for ($j = 0; $j < $numRows; $j++)
						{
							if (mysql_result($run_most_games_in_day_query, $j, 'daily_games') > $mostGamesInOneDay)
							{
								$mostGamesInOneDay = mysql_result($run_most_games_in_day_query, $j, 'daily_games');
								$mostGamesInOneDayDate = getdate(strtotime(mysql_result($run_most_games_in_day_query, $j, 'date')));
								$mostGamesInOneDayDate = $mostGamesInOneDayDate['month'].' '.$mostGamesInOneDayDate['mday'].', '.$mostGamesInOneDayDate['year'];
							}
						}
					}

					if ($run_league_shooting_perc_query = mysql_query($league_shooting_perc_query))
					{
						$leagueShootingPerc = number_format(mysql_result($run_league_shooting_perc_query, 0) * 100, 2, '.', '');
					}
				 ?>

				<h2 class="sub_heading">All-Time League Stats</h2>
				<table class="all_time_stats">
					<tr>
						<th>Games Played</th>
						<td><?php echo $totalGamesPlayed; ?></td>
					</tr>
					<tr>
						<th>Overtime Games Played</th>
						<td><?php echo $totalOTGamesPlayed; ?></td>
					</tr>
					<tr>
						<th>Multi-Overtime Games Played</th>
						<td><?php echo $totalMultiOTGamesPlayed; ?></td>
					</tr>
					<tr>
						<th>Beers Drank</th>
						<td><?php echo $totalBeersDrank; ?></td>
					</tr>
					<tr>
						<th>Cups Spilled</th>
						<td><?php echo $totalBeersSpilled; ?></td>
					</tr>
					<tr>
						<th>Most Games Played In One Day</th>
						<td><?php echo $mostGamesInOneDay.' on '.$mostGamesInOneDayDate; ?></td>
					</tr>
					<tr>
						<th>Average Shooting Percentage</th>
						<td><?php echo $leagueShootingPerc; ?></td>
					</tr>
				</table>
			<?php } else { include 'whatsnew.inc.php'; ?>
				<h2 class="sub_heading">Welcome to Pong Champ</h2>
				<p>
				Start tracking your beer pong stats like never before with the <a href="https://play.google.com/store/apps/details?id=com.pongchamp.pc&
				feature=search_result#?t=W251bGwsMSwyLDEsImNvbS5wb25nY2hhbXAucGMiXQ..">Pong Champ mobile app</a>, available exclusively
				for Android devices on the Google Play Store.
				</p>
				<div id="home_imgs">
					<span>
						<a href="imgs/home_1.png"><img src="imgs/home_1.png" alt="home1" width="150" height="267" /></a>
						<a href="imgs/home_2.png"><img src="imgs/home_2.png" alt="home2" width="150" height="267" /></a>
						<a href="imgs/home_3.png"><img src="imgs/home_3.png" alt="home3" width="150" height="267" /></a>
						<a href="imgs/home_4.png"><img src="imgs/home_4.png" alt="home4" width="150" height="267" /></a>
						<a href="imgs/home_5.png"><img src="imgs/home_5.png" alt="home5" width="150" height="267" /></a>
						<a href="imgs/home_6.png"><img src="imgs/home_6.png" alt="home6" width="150" height="267" /></a>
					</span>
				</div>
				<h2 class="sub_heading">What is Pong Champ?</h2>
				<p>
				Pong Champ is a mobile app for Android smartphones that records detailed stats of beer pong games and allows you to track
				those stats through this website. Stats are uploaded directly from the app to the website, so all you need to do is 
				<a href="https://play.google.com/store/apps/details?id=com.pongchamp.pc&feature=search_result#?t=W251bGwsMSwyLDEsImNvbS5wb25nY2hhbXAucGMiXQ..">
				download the app</a> and register an account to start tracking your beer pong games today!
				</p>
				<h2 class="sub_heading">Why is Pong Champ the best?</h2>
				<p>
				Pong Champ separates itself from other beer pong stats apps because it keeps track of so many more stats that these other
				apps just don't have. It addition to what you would expect: wins, losses, and shooting percentage, Pong Champ keeps track of more
				unique stats like how many overtime games you have played, your highest hit streak and highest win streak, an ELO-based rating system
				to establish your overall rank, and even how many times you have successfully completed a redemption. Each player has their own individual
				player profile which shows their complete progress report. For a complete list of the stats that Pong Champ records, <a href="stat_list.php">click here.</a>
				</p>
				<p>
				In addition to individual player stats, Pong Champ also keeps team stats, game-by-game results, head-to-head records, and individual
				achievements. A list of the achievements can be <a href="achievement_list.php">seen here.</a>
				</p>
				<h2 class="sub_heading">Other than unique stats, what does Pong Champ bring to the table?</h2>
				<p>
				Aside from the convenience of tracking beer pong games with your mobile device, Pong Champ also has some other pretty cool features: an in-app 
				team randomizer, to decide who is playing next or what the teams will be, the ability to switch up the rules depending on which house rules you
				play with, an in-game shot-by-shot history in case there are any disputes, and finally, for you gamblers, a pre-game odds window displaying the 
				cup spread and associated odds for the upcoming game.
				</p>
				<h2 class="sub_heading">Questions</h2>
				<p>
				If you have any questions about the website or the mobile app, or you want to suggest some new stats/features to include in new releases, drop us 
				a line at cody@pongchamp.com
				</p>
			<?php } ?>
		</div>
	</div>
	<?php include 'footer.inc.php'; ?>
</body>
</html>