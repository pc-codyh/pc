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
<script type="text/javascript" src="js/jquery-latest.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<!-- <script type="text/javascript" src="js/marquee.js"></script> -->
<script type="text/javascript" src="js/jquery.marquee.js"></script>
<script type="text/javascript">
	$(document).ready(function ()
	{
		$("#marquee").marquee();
	});

	$.tablesorter.addWidget({ 
	    id: 'bolder',
	    format: function(table) {
	        /* Will be called after table init and after sort */
	        i = 0;
	        foundIdx = 0;      // Idx for the column to be made bold
	        $(table).find ( "th.header" ).each ( function () {
	            if ( $(this).hasClass ( "headerSortDown" ) || $(this).hasClass ( "headerSortUp" ) ) { 
	                foundIdx = i;
	            }
	            i++;
	        });
	        $(table).find ( "tbody tr" ).each ( function () {
	            c = 0;
	            $(this).find ( "td" ).each ( function () {
	                if ( c == foundIdx ) {
	                    $(this).addClass ( "boldify" );
	                }
	                else {
	                    $(this).removeClass ( "boldify" );
	                }
	                c++;
	            });
	        });
	    }   
	});

	$.tablesorter.addWidget({
    	// give the widget a id
    	id: "indexFirstColumn",
    	// format is called when the on init and when a sorting has finished
    	format: function(table) {				
    		// loop all tr elements and set the value for the first column	
    		for(var i=0; i <= table.tBodies[0].rows.length; i++) {
    			$("tbody tr:eq(" + (i - 1) + ") td:first",table).html(i);
    		}    								
    	}
    });

	$(document).ready(function() 
    { 
        $(".stats_table").tablesorter({ widgets: ['zebra', 'bolder', 'indexFirstColumn'], sortInitialOrder: 'desc' });
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
			<h1 class="main_heading">Achievements</h1>
			<?php if ($is_logged_in) { ?>
				<table class="stats_divider">
					<tr>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'shs') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/sharpshooter.png" alt="icon"></td>
									<td><h2 class="sub_heading">Sharpshooter</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Hit five cups in a row in a game">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_shs` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_shs').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=shs">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'mj') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/michael_jordan.png" alt="icon"></td>
									<td><h2 class="sub_heading">Michael Jordan</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Go on fire two or more times in a game">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_mj` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_mj').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=mj">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'tkcp') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/the_kid_can_play.png" alt="icon"></td>
									<td><h2 class="sub_heading">The Kid Can Play</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Sink all the cups for your team in a game (and your team must win)">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_cibav` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_cibav').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=tkcp">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
					</tr>
				</table>
				<table class="stats_divider">
					<tr>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'hc') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/heartbreak_city.png" alt="icon"></td>
									<td><h2 class="sub_heading">Heartbreak City</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Win a game after being down by five or more cups">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_hc` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_hc').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=hc">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'cwtpd') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/caught_with_their_pants_down.png" alt="icon"></td>
									<td class="ach_max_width"><h2 class="sub_heading">Caught With Their Pants Down</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Hit two or more bounce shots in a game">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_cwtpd` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_cwtpd').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=cwtpd">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
					</tr>
				</table>
				<table class="stats_divider">
					<tr>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'ps') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/porn_star.png" alt="icon"></td>
									<td><h2 class="sub_heading">Porn Star</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Hit two or more gang-bangs in a game">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_ps` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_ps').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=ps">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'per') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/perfection.png" alt="icon"></td>
									<td><h2 class="sub_heading">Perfection</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Shoot one-hundred percent in a game">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_per` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_per').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=per">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'dbno') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/down_but_not_out.png" alt="icon"></td>
									<td><h2 class="sub_heading">Down But Not Out</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="As an individual, successfully complete two or more redemptions in a game">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_dbno` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_dbno').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=dbno">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
					</tr>
				</table>
				<table class="stats_divider">
					<tr>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'mar') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/marathon.png" alt="icon"></td>
									<td><h2 class="sub_heading">Marathon</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Compete in a game that goes to triple overtime">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_mar` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_mar').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=mar">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'ck') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/comeback_kill.png" alt="icon"></td>
									<td><h2 class="sub_heading">Comeback Kill</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Sink a cup after missing five or more shots in a row">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_ck` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_ck').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=ck">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'fdm') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/first_degree_murder.png" alt="icon"></td>
									<td><h2 class="sub_heading">First Degree Murder</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Win a game before the other team gets a re-rack (ten-cup start)">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_fdm` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_fdm').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=fdm">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
					</tr>
				</table>
				<table class="stats_divider">
					<tr>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'bb') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/bill_buckner.png" alt="icon"></td>
									<td><h2 class="sub_heading">Bill Buckner</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Commit two or more errors in a game">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_bb` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_bb').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=bb">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'bc') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/bitch_cup.png" alt="icon"></td>
									<td><h2 class="sub_heading">Bitch Cup</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Hit the middle cup first on a ten-cup rack">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_bc` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_bc').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=bc">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'bank') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/bankruptcy.png" alt="icon"></td>
									<td><h2 class="sub_heading">Bankruptcy</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Sink no cups in a game">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_bank` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_bank').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=bank">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
					</tr>
				</table>
				<table class="stats_divider">
					<tr>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'skunk') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/skunked.png" alt="icon"></td>
									<td><h2 class="sub_heading">Skunked</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Lose a game before getting a re-rack (ten-cup start)">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_skunk` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_skunk').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=skunk">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
						<?php if (!isset($_GET['ach']) || $_GET['ach'] == 'sw') { ?>
						<td>
							<table>
								<tr>
									<td><img src="imgs/stevie_wonder.png" alt="icon"></td>
									<td><h2 class="sub_heading">Stevie Wonder</h2></td>
								</tr>
							</table>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Miss ten shots in a row in a game">Count</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									////////////////////////
									// Query Achievements //
									$achievements_query = 'SELECT * FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `a_sw` DESC';
									$run_achievements_query = mysql_query($achievements_query);
									////////////////////////

									$rows = mysql_num_rows($run_achievements_query);

									if (!isset($_GET['ach']))
									{
										if ($rows > 5)
										{
											$rows = 5;
										}
									}

									while ($i < $rows)
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_achievements_query, $i, 'name').'">'.mysql_result($run_achievements_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_achievements_query, $i, 'a_sw').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
							<?php if (!isset($_GET['ach'])) { ?>
							<div class="show_all"><a href="achievements.php?ach=sw">Show All</a></div>
							<?php } ?>
						</td>
						<?php } ?>
					</tr>
				</table>
			<?php } else { ?>
				<div id="account_already_exists">
					<p>Please login above to view stats.</p>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php include 'footer.inc.php'; ?>
</body>
</html>