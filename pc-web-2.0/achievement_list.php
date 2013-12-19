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
			<h1 class="main_heading">Achievement List</h1>
			<div id="stat_list">
				<h2 class="sub_heading">Individual Achievements</h2>
				<h3>The Good</h3>
				<table class="all_time_stats">
					<tr>
						<td class="icon"><img src="imgs/sharpshooter.png" alt="icon" height="30" width="30"></td>
						<th>Sharpshooter</th>
						<td class="desc">Hit five cups in a row in a game</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/michael_jordan.png" alt="icon" height="30" width="30"></td>
						<th>Michael Jordan</th>
						<td class="desc">Go on fire two or more times in a game</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/the_kid_can_play.png" alt="icon" height="30" width="30"></td>
						<th>The Kid Can Play</th>
						<td class="desc">Sink all the cups for your team in a game (and your team must win)</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/heartbreak_city.png" alt="icon" height="30" width="30"></td>
						<th>Heartbreak City</th>
						<td class="desc">Win a game after being down by five or more cups</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/caught_with_their_pants_down.png" alt="icon" height="30" width="30"></td>
						<th>Caught With Their Pants Down</th>
						<td class="desc">Hit two or more bounce shots in a game</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/porn_star.png" alt="icon" height="30" width="30"></td>
						<th>Porn Star</th>
						<td class="desc">Hit two or more gang-bangs in a game</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/perfection.png" alt="icon" height="30" width="30"></td>
						<th>Perfection</th>
						<td class="desc">Shoot one-hundred percent in a game</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/down_but_not_out.png" alt="icon" height="30" width="30"></td>
						<th>Down But Not Out</th>
						<td class="desc">As an individual, successfully complete two or more redemptions in a game</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/marathon.png" alt="icon" height="30" width="30"></td>
						<th>Marathon</th>
						<td class="desc">Compete in a game that goes to triple overtime</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/first_degree_murder.png" alt="icon" height="30" width="30"></td>
						<th>First Degree Murder</th>
						<td class="desc">Win a game before the other team gets a re-rack (ten-cup start)</td>
					</tr>
				</table>
				<h3>The Bad</h3>
				<table class="all_time_stats">
					<tr>
						<td class="icon"><img src="imgs/comeback_kill.png" alt="icon" height="30" width="30"></td>
						<th>Comeback Kill</th>
						<td class="desc">Sink a cup after missing five or more shots in a row</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/bill_buckner.png" alt="icon" height="30" width="30"></td>
						<th>Bill Buckner</th>
						<td class="desc">Commit two or more errors in a game</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/bitch_cup.png" alt="icon" height="30" width="30"></td>
						<th>Bitch Cup</th>
						<td class="desc">Hit the middle cup first on a ten-cup rack</td>
					</tr>
				</table>
				<h3>The Ugly</h3>
				<table class="all_time_stats">
					<tr>
						<td class="icon"><img src="imgs/bankruptcy.png" alt="icon" height="30" width="30"></td>
						<th>Bankruptcy</th>
						<td class="desc">Sink no cups in a game</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/skunked.png" alt="icon" height="30" width="30"></td>
						<th>Skunked</th>
						<td class="desc">Lose a game before getting a re-rack (ten-cup start)</td>
					</tr>
					<tr>
						<td class="icon"><img src="imgs/stevie_wonder.png" alt="icon" height="30" width="30"></td>
						<th>Stevie Wonder</th>
						<td class="desc">Miss ten shots in a row in a game</td>
					</tr>
				</table>
			</div>
			<div id="unlockable_achievement_list">
				<h4>In addition to the Achievements listed here, there are seven unlockable Achievements for each player. Those Achievements are unlocked by completing the following milestones:</h4>
				<table class="all_time_stats">
					<tr>
						<th>Games Played</th>
						<td>200</td>
					</tr>
					<tr>
						<th>Wins</th>
						<td>100</td>
					</tr>
					<tr>
						<th>Cups Hit</th>
						<td>1000</td>
					</tr>
					<tr>
						<th>Bounces Hit</th>
						<td>50</td>
					</tr>
					<tr>
						<th>Redemption Successes</th>
						<td>50</td>
					</tr>
					<tr>
						<th>Last Cups Hit</th>
						<td>100</td>
					</tr>
					<tr>
						<th>Achievements Earned</th>
						<td>100</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<?php include 'footer.inc.php'; ?>
</body>
</html>