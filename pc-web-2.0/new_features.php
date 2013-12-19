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
			<h1 class="main_heading">What's New</h1>
			<h3>2.0</h3>
			<ul>
				<li><b>Website overhaul</b> - The website has been completely rebuilt from the ground up to improve the Pong Champ experience and support the new features in 2.0.</li>
				<li><b>Added seasons</b> - In addition to all the features it had before, Pong Champ now features seasonal statistics, so players get a fresh start at the beginning of each season (spring, summer, fall, and winter).</li>
				<li><b>Achievement icons</b> - Every achievement, including the unlockable achievements, now has an icon with it.</li>
				<li><b>Mobile app interface</b> - There have been some slight tweaks to the mobile app interface to make it mirror the appearance of the new website.</li>
				<li><b>Mobile app shot history screen</b> - Shots are now displayed in chronological order with the most recent shot at the top.</li>
				<li><b>Mobile app "Quick Play"</b> - You can now start a new game straight from the random teams screen after randomizing the teams.</li>
				<li><b>Individual game details</b> - Each game result now has game details with it, viewable by clicking on the date of the game result.</li>
				<li><b>Player profiles</b> - Overall player profiles now contain a "Seasons" table, which displays the most important season-by-season statistics.</li>
				<li><b>Bug fixes</b></li>
			</ul>
		</div>
	</div>
	<?php include 'footer.inc.php'; ?>
</body>
</html>