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

	$season = '';
	$message = '';

	///////////////////
	// Seasons Query //
	///////////////////
	$run_seasons_query = mysql_query('SELECT `season` FROM `seasons` WHERE 1');
	///////////////////


	if (isset($_GET['season']))
	{
		$run_get_season_query = mysql_query('SELECT * FROM `seasons` WHERE `season`="'.$_GET['season'].'"');

		if (mysql_num_rows($run_get_season_query) == 1)
		{
			$season = $_GET['season'];

			$season_title = '';

			$index = strpos($season, '_');
			$season_title = substr($season, $index + 1);
			$index = strpos($season_title, '_');
			$season_title = ucfirst(substr($season_title, 0, $index).' '.substr($season_title, $index + 1));

			////////////////
			// Query Rank //
			$rank_query = 'SELECT `name`, `rank`, `elo_rating`, `compound_rating` FROM `'.$season.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `rank` ASC';
			$run_rank_query = mysql_query($rank_query);
			////////////////

			//////////////////
			// Query Record //
			$record_query = 'SELECT `name`, `wins`, `losses`, `ot_losses`, `ot_games_played`, `cup_dif` FROM `'.$season.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `wins` DESC';
			$run_record_query = mysql_query($record_query);
			//////////////////

			////////////////////
			// Query Shooting //
			$shooting_query = 'SELECT `name`, `shots`, `hits`, `shooting_percentage`, `bounces`, `gang_bangs`, `errors`, `heating_up`, `on_fire`, `h1` FROM `'.$season.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `shooting_percentage` DESC';
			$run_shooting_query = mysql_query($shooting_query);
			////////////////////

			///////////////////
			// Query Streaks //
			$streaks_query = 'SELECT `name`, `win_streak`, `loss_streak`, `hit_streak`, `miss_streak` FROM `'.$season.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `hit_streak` DESC';
			$run_streaks_query = mysql_query($streaks_query);
			///////////////////

			//////////////////////
			// Query Redemption //
			$redemption_query = 'SELECT `name`, `redemp_shotperc`, `redemp_shots`, `redemp_hits`, `redemp_atmps`, `redemp_succs` FROM `'.$season.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `redemp_shotperc` DESC';
			$run_redemption_query = mysql_query($redemption_query);
			//////////////////////

			/////////////////////////
			// Query Rack Shooting //
			$rack_query = 'SELECT `name`, `p10`, `p9`, `p8`, `p7`, `p6`, `p5`, `p4`, `p3`, `p2`, `p1` FROM `'.$season.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `p1` DESC';
			$run_rack_query = mysql_query($rack_query);
			/////////////////////////
		}
		// The season doesn't exist.
		else
		{
			$message = 'The season you are looking for does not exist.';
		}
	}
	else
	{
		$message = 'Select a season.';
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
			<?php if ($is_logged_in) { ?>
				<div id="profile_form">
					<form action="seasonal_stats.php" method="get">
						<table>
							<tr>
								<td>
									<select name="season" class="form_input">
										<?php 
											$i = 0;

											while ($i < mysql_num_rows($run_seasons_query))
											{
												$season_name = mysql_result($run_seasons_query, $i, 'season');
												$index = strpos($season_name, '_');
												$season_name = substr($season_name, $index + 1);
												$index = strpos($season_name, '_');
												$season_name = ucfirst(substr($season_name, 0, $index).' '.substr($season_name, $index + 1));
													
												if ($season == mysql_result($run_seasons_query, $i, 'season'))
												{
													echo '<option value="'.mysql_result($run_seasons_query, $i, 'season').'" selected>'.$season_name.'</option>';
												}
												else
												{
													echo '<option value="'.mysql_result($run_seasons_query, $i, 'season').'">'.$season_name.'</option>';
												}

												$i++;
											}
										?>
									</select>
								</td>
								<td>
									<input type="submit" name="submit" value="View Season" class="form_submit">
								</td>
							</tr>
						</table>
					</form>
				</div>
				<?php if ($season != '') { ?>
				<h1 class="main_heading">Seasonal Stats for <span class="season"><?php echo $season_title; ?></span></h1>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Rank</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Overall Rank">Rank</th>
									<th class="fixed_cell" title="ELO Rating">ELO</th>
									<th class="fixed_cell" title="Compound Rating">CR</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									while ($i < mysql_num_rows($run_rank_query))
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_rank_query, $i, 'name').'">'.mysql_result($run_rank_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_rank_query, $i, 'rank').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rank_query, $i, 'elo_rating'), 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rank_query, $i, 'compound_rating'), 2, '.', '').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
								<thead>
								<tr class="stats_table_header_row">
									<th>Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Overall Rank">Rank</th>
									<th class="fixed_cell" title="ELO Rating">ELO</th>
									<th class="fixed_cell" title="Compound Rating">CR</th>
								</tr>
								</thead>
							</table>
						</td>
						<td>
							<h2 class="sub_heading">Record</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th>Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Games Played">GP</th>
									<th class="fixed_cell" title="Wins">W</th>
									<th class="fixed_cell" title="Losses">L</th>
									<th class="fixed_cell" title="Overtime Losses">OTL</th>
									<th class="fixed_cell" title="Overtime Games Played">OTGP</th>
									<th class="fixed_cell" title="Cup Differential">DIF</th>
									<th class="fixed_cell" title="Winning Percentage">WP</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									while ($i < mysql_num_rows($run_record_query))
									{
										$gamesPlayed = mysql_result($run_record_query, $i, 'wins') + mysql_result($run_record_query, $i, 'losses') + mysql_result($run_record_query, $i, 'ot_losses');

										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_record_query, $i, 'name').'">'.mysql_result($run_record_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.$gamesPlayed.'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_record_query, $i, 'wins').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_record_query, $i, 'losses').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_record_query, $i, 'ot_losses').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_record_query, $i, 'ot_games_played').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_record_query, $i, 'cup_dif').'</td>';

										if ($gamesPlayed > 0)
										{
											echo '<td class="fixed_cell">'.number_format((mysql_result($run_record_query, $i, 'wins') / $gamesPlayed) * 100, 2, '.', '').'</td>';
										}
										else
										{
											echo '<td class="fixed_cell">'.number_format(0.0, 2, '.', '').'</td>';
										}
										
										echo '</tr>';

										$i++;
									}
								?>	
								</tbody>
								<thead>
								<tr class="stats_table_header_row">
									<th>Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Games Played">GP</th>
									<th class="fixed_cell" title="Wins">W</th>
									<th class="fixed_cell" title="Losses">L</th>
									<th class="fixed_cell" title="Overtime Losses">OTL</th>
									<th class="fixed_cell" title="Overtime Games Played">OTGP</th>
									<th class="fixed_cell" title="Cup Differential">DIF</th>
									<th class="fixed_cell" title="Winning Percentage">WP</th>
								</tr>
								</thead>
							</table>
						</td>
					</tr>
				</table>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Shooting</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Shooting Percentage">SP</th>
									<th class="fixed_cell" title="Shots">S</th>
									<th class="fixed_cell" title="Hits">H</th>
									<th class="fixed_cell" title="Bounces">B</th>
									<th class="fixed_cell" title="Gang-Bangs">GB</th>
									<th class="fixed_cell" title="Errors">E</th>
									<th class="fixed_cell" title="Times Heating Up">HU</th>
									<th class="fixed_cell" title="Times On Fire">OF</th>
									<th class="fixed_cell" title="Last Cups Hit">LCH</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									while ($i < mysql_num_rows($run_shooting_query))
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_shooting_query, $i, 'name').'">'.mysql_result($run_shooting_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_shooting_query, $i, 'shooting_percentage') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_shooting_query, $i, 'shots').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_shooting_query, $i, 'hits').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_shooting_query, $i, 'bounces').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_shooting_query, $i, 'gang_bangs').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_shooting_query, $i, 'errors').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_shooting_query, $i, 'heating_up').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_shooting_query, $i, 'on_fire').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_shooting_query, $i, 'h1').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
								<thead>
								<tr class="stats_table_header_row">
									<th>Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Shooting Percentage">SP</th>
									<th class="fixed_cell" title="Shots">S</th>
									<th class="fixed_cell" title="Hits">H</th>
									<th class="fixed_cell" title="Bounces">B</th>
									<th class="fixed_cell" title="Gang-Bangs">GB</th>
									<th class="fixed_cell" title="Errors">E</th>
									<th class="fixed_cell" title="Times Heating Up">HU</th>
									<th class="fixed_cell" title="Times On Fire">OF</th>
									<th class="fixed_cell" title="Last Cups Hit">LCH</th>
								</tr>
								</thead>
							</table>
						</td>
					</tr>
				</table>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Streaks</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Highest Win Streak">WS</th>
									<th class="fixed_cell" title="Highest Loss Streak">LS</th>
									<th class="fixed_cell" title="Highest Hit Streak">HS</th>
									<th class="fixed_cell" title="Highest Miss Streak">MS</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									while ($i < mysql_num_rows($run_streaks_query))
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_streaks_query, $i, 'name').'">'.mysql_result($run_streaks_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_streaks_query, $i, 'win_streak').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_streaks_query, $i, 'loss_streak').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_streaks_query, $i, 'hit_streak').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_streaks_query, $i, 'miss_streak').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
								<thead>
								<tr class="stats_table_header_row">
									<th>Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Highest Win Streak">WS</th>
									<th class="fixed_cell" title="Highest Loss Streak">LS</th>
									<th class="fixed_cell" title="Highest Hit Streak">HS</th>
									<th class="fixed_cell" title="Highest Miss Streak">MS</th>
								</tr>
								</thead>
							</table>
						</td>
						<td>
							<h2 class="sub_heading">Redemption</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Redemption Shooting Percentage">RSP</th>
									<th class="fixed_cell" title="Redemption Shots">RS</th>
									<th class="fixed_cell" title="Redemption Hits">RH</th>
									<th class="fixed_cell" title="Redemption Attempts">RAT</th>
									<th class="fixed_cell" title="Redemption Successes">RSU</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									while ($i < mysql_num_rows($run_redemption_query))
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_redemption_query, $i, 'name').'">'.mysql_result($run_redemption_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_redemption_query, $i, 'redemp_shotperc') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_redemption_query, $i, 'redemp_shots').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_redemption_query, $i, 'redemp_hits').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_redemption_query, $i, 'redemp_atmps').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_redemption_query, $i, 'redemp_succs').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
								<thead>
								<tr class="stats_table_header_row">
									<th>Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Redemption Shooting Percentage">RSP</th>
									<th class="fixed_cell" title="Redemption Shots">RS</th>
									<th class="fixed_cell" title="Redemption Hits">RH</th>
									<th class="fixed_cell" title="Redemption Attempts">RAT</th>
									<th class="fixed_cell" title="Redemption Successes">RSU</th>
								</tr>
								</thead>
							</table>
						</td>
					</tr>
				</table>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Rack Shooting</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Ten Cups">10</th>
									<th class="fixed_cell" title="Nine Cups">9</th>
									<th class="fixed_cell" title="Eight Cups">8</th>
									<th class="fixed_cell" title="Seven Cups">7</th>
									<th class="fixed_cell" title="Six Cups">6</th>
									<th class="fixed_cell" title="Five Cups">5</th>
									<th class="fixed_cell" title="Four Cups">4</th>
									<th class="fixed_cell" title="Three Cups">3</th>
									<th class="fixed_cell" title="Two Cups">2</th>
									<th class="fixed_cell" title="One Cup">LC</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									while ($i < mysql_num_rows($run_rack_query))
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_rack_query, $i, 'name').'">'.mysql_result($run_rack_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rack_query, $i, 'p10') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rack_query, $i, 'p9') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rack_query, $i, 'p8') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rack_query, $i, 'p7') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rack_query, $i, 'p6') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rack_query, $i, 'p5') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rack_query, $i, 'p4') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rack_query, $i, 'p3') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rack_query, $i, 'p2') * 100, 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_rack_query, $i, 'p1') * 100, 2, '.', '').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
								<thead>
								<tr class="stats_table_header_row">
									<th>Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Ten Cups">10</th>
									<th class="fixed_cell" title="Nine Cups">9</th>
									<th class="fixed_cell" title="Eight Cups">8</th>
									<th class="fixed_cell" title="Seven Cups">7</th>
									<th class="fixed_cell" title="Six Cups">6</th>
									<th class="fixed_cell" title="Five Cups">5</th>
									<th class="fixed_cell" title="Four Cups">4</th>
									<th class="fixed_cell" title="Three Cups">3</th>
									<th class="fixed_cell" title="Two Cups">2</th>
									<th class="fixed_cell" title="One Cup">LC</th>
								</tr>
								</thead>
							</table>
						</td>
					</tr>
				</table>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Overtime Record</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Overtime Games Played">OTGP</th>
									<th class="fixed_cell" title="Overtime Wins">W</th>
									<th class="fixed_cell" title="Overtime Losses">L</th>
									<th class="fixed_cell" title="Overtime Winning Percentage">OTWP</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									while ($i < mysql_num_rows($run_record_query))
									{
										$ot_wins = mysql_result($run_record_query, $i, 'ot_games_played') - mysql_result($run_record_query, $i, 'ot_losses');

										$ot_winning_perc = 0;

										if (mysql_result($run_record_query, $i, 'ot_games_played') > 0)
										{
											$ot_winning_perc = ($ot_wins / mysql_result($run_record_query, $i, 'ot_games_played'));
										}

										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_record_query, $i, 'name').'">'.mysql_result($run_record_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_record_query, $i, 'ot_games_played').'</td>';
										echo '<td class="fixed_cell">'.$ot_wins.'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_record_query, $i, 'ot_losses').'</td>';
										echo '<td class="fixed_cell">'.number_format($ot_winning_perc * 100, 2, '.', '').'</td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
								<thead>
								<tr class="stats_table_header_row">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Overtime Games Played">OTGP</th>
									<th class="fixed_cell" title="Overtime Wins">W</th>
									<th class="fixed_cell" title="Overtime Losses">L</th>
									<th class="fixed_cell" title="Overtime Winning Percentage">OTWP</th>
								</tr>
								</thead>
							</table>
						</td>
					</tr>
				</table>
				<?php } else { ?>
					<div id="account_already_exists">
						<p><?php echo $message; ?></p>
					</div>
				<?php } ?>
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