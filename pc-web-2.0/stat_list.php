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
<title>Stat List</title>
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
			<h1 class="main_heading">Stat List</h1>
			<div id="stat_list">
				<h2 class="sub_heading">Individual Stats</h2>
				<table class="all_time_stats">
					<tr>
						<th>GP</th>
						<td>Games played</td>
					</tr>
					<tr>
						<th>W</th>
						<td>Wins</td>
					</tr>
					<tr>
						<th>L</th>
						<td>Losses</td>
					</tr>
					<tr>
						<th>OTL</th>
						<td>Overtime losses</td>
					</tr>
					<tr>
						<th>OTGP</th>
						<td>Overtime games played</td>
					</tr>
					<tr>
						<th>OTWP</th>
						<td>Overtime winning percentage</td>
					</tr>
					<tr>
						<th>DIF</th>
						<td>Cup differential</td>
					</tr>
					<tr>
						<th>WP</th>
						<td>Winning percentage</td>
					</tr>
					<tr>
						<th>S</th>
						<td>Total shots taken</td>
					</tr>
					<tr>
						<th>H</th>
						<td>Total shots hit</td>
					</tr>
					<tr>
						<th>SP</th>
						<td>Shooting percentage</td>
					</tr>
					<tr>
						<th>B</th>
						<td>Total bounces hit</td>
					</tr>
					<tr>
						<th>GB</th>
						<td>Total gang-bangs hit</td>
					</tr>
					<tr>
						<th>E</th>
						<td>Total errors Committed</td>
					</tr>
					<tr>
						<th>HU</th>
						<td>Number of times heating up</td>
					</tr>
					<tr>
						<th>OF</th>
						<td>Number of times on fire</td>
					</tr>
					<tr>
						<th>WS</th>
						<td>Highest win streak</td>
					</tr>
					<tr>
						<th>LS</th>
						<td>Highest loss streak</td>
					</tr>
					<tr>
						<th>HS</th>
						<td>Highest hit streak</td>
					</tr>
					<tr>
						<th>MS</th>
						<td>Highest miss streak</td>
					</tr>
					<tr>
						<th>ELO</th>
						<td>Overall ELO rating</td>
					</tr>
					<tr>
						<th>CR</th>
						<td>Overall compound rating based off a formula which includes ELO rating, shooting percentage, and games played</td>
					</tr>
					<tr>
						<th>Rank</th>
						<td>Overall rank based off compound rating</td>
					</tr>
					<tr>
						<th>RS</th>
						<td>Redemption shots taken</td>
					</tr>
					<tr>
						<th>RH</th>
						<td>Redemption shots hit</td>
					</tr>
					<tr>
						<th>RSP</th>
						<td>Redemption shooting percentage</td>
					</tr>
					<tr>
						<th>RAT</th>
						<td>Total number of redemption attempts</td>
					</tr>
					<tr>
						<th>RSU</th>
						<td>Total number of redemption successes</td>
					</tr>
					<tr>
						<th>Rack Shooting % (2-10)</th>
						<td>Shooting percentage on each rack (number of cups remaining)</td>
					</tr>
					<tr>
						<th>LC</th>
						<td>Shooting percentage on the last cup</td>
					</tr>
					<tr>
						<th>LCH</th>
						<td>Total number of last cups hit</td>
					</tr>
				</table>
			</div>
			<div id="stat_list">
				<h2 class="sub_heading">Team Stats</h2>
				<table class="all_time_stats">
					<tr>
						<th>GP</th>
						<td>Games played</td>
					</tr>
					<tr>
						<th>W</th>
						<td>Wins</td>
					</tr>
					<tr>
						<th>L</th>
						<td>Losses</td>
					</tr>
					<tr>
						<th>OTL</th>
						<td>Overtime losses</td>
					</tr>
					<tr>
						<th>OTGP</th>
						<td>Overtime games played</td>
					</tr>
					<tr>
						<th>WP</th>
						<td>Team winning percentage</td>
					</tr>
					<tr>
						<th>WS</th>
						<td>Team highest win streak</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<?php include 'footer.inc.php'; ?>
</body>
</html>