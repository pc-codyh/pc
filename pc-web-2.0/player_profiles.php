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

	$playerName = '';
	$profile_table = 'players';

	if (isset($_GET['player']))
	{
		// Check for SQL injection.
		if (!stripos($_GET['player'], 'drop database'))
		{
			$playerName = $_GET['player'];
		}
	}

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
			$profile_table = $_GET['season'];

			$season_title = '';

			$index = strpos($profile_table, '_');
			$season_title = substr($profile_table, $index + 1);
			$index = strpos($season_title, '_');
			$season_title = ucfirst(substr($season_title, 0, $index).' '.substr($season_title, $index + 1));
		}
	}

	///////////////////
	// Query Players //
	$players_query = 'SELECT `name` FROM `'.$profile_table.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `name` ASC';
	$run_players_query = mysql_query($players_query);
	///////////////////

	////////////////
	// Query Rank //
	$rank_query = 'SELECT `name`, `rank`, `elo_rating`, `compound_rating` FROM `'.$profile_table.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `name`="'.$playerName.'"';
	$run_rank_query = mysql_query($rank_query);
	////////////////

	//////////////////
	// Query Record //
	$record_query = 'SELECT `name`, `wins`, `losses`, `ot_losses`, `ot_games_played`, `cup_dif` FROM `'.$profile_table.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `name`="'.$playerName.'"';
	$run_record_query = mysql_query($record_query);
	//////////////////

	////////////////////
	// Query Shooting //
	$shooting_query = 'SELECT `name`, `shots`, `hits`, `shooting_percentage`, `bounces`, `gang_bangs`, `errors`, `heating_up`, `on_fire`, `h1` FROM `'.$profile_table.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `name`="'.$playerName.'"';
	$run_shooting_query = mysql_query($shooting_query);
	////////////////////

	///////////////////
	// Query Streaks //
	$streaks_query = 'SELECT `name`, `win_streak`, `loss_streak`, `hit_streak`, `miss_streak` FROM `'.$profile_table.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `name`="'.$playerName.'"';
	$run_streaks_query = mysql_query($streaks_query);
	///////////////////

	//////////////////////
	// Query Redemption //
	$redemption_query = 'SELECT `name`, `redemp_shotperc`, `redemp_shots`, `redemp_hits`, `redemp_atmps`, `redemp_succs` FROM `'.$profile_table.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `name`="'.$playerName.'"';
	$run_redemption_query = mysql_query($redemption_query);
	//////////////////////

	/////////////////////////
	// Query Rack Shooting //
	$rack_query = 'SELECT `name`, `p10`, `p9`, `p8`, `p7`, `p6`, `p5`, `p4`, `p3`, `p2`, `p1`, `h1` FROM `'.$profile_table.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `name`="'.$playerName.'"';
	$run_rack_query = mysql_query($rack_query);
	/////////////////////////

	////////////////////////
	// Query Achievements //
	$achievement_query = 'SELECT * FROM `'.$profile_table.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `name`="'.$playerName.'"';
	$run_achievement_query = mysql_query($achievement_query);
	////////////////////////

	///////////////////////////
	// Query Achievement Sum //
	$achievement_sum_query = 'SELECT SUM(`a_shs` + `a_mj` + `a_cibav` + `a_bank` + `a_ck` + `a_hc` + `a_cwtpd` + `a_ps` + `a_sw` + `a_per` + `a_dbno` + `a_bb` + `a_bc` + `a_mar` + `a_fdm` + `a_skunk` + `ua_alc` + `ua_oah` + `ua_ce` + `ua_slip` + `ua_dciac` + `ua_dth`) FROM `'.$profile_table.'` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `name`="'.$playerName.'"';
	$run_achievement_sum_query = mysql_query($achievement_sum_query);
	///////////////////////////

	///////////////////
	// Query Results //
	$results_query = 'SELECT * FROM `games` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND (`team_one_player_one`="'.$playerName.'" OR `team_one_player_two`="'.$playerName.'" OR `team_two_player_one`="'.$playerName.'" OR `team_two_player_two`="'.$playerName.'") ORDER BY `id` DESC';
	$run_results_query = mysql_query($results_query);
	///////////////////
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
				<?php if ($playerName != '' && mysql_num_rows($run_rank_query) > 0) { ?>
				<div id="profile_form">
					<form action="player_profiles.php" method="get">
						<table>
							<tr>
								<td>
									<select name="player" class="form_input">
										<?php 
											$i = 0;

											while ($i < mysql_num_rows($run_players_query))
											{
												if ($playerName == mysql_result($run_players_query, $i, 'name'))
												{
													echo '<option value="'.mysql_result($run_players_query, $i, 'name').'" selected>'.mysql_result($run_players_query, $i, 'name').'</option>';
												}
												else
												{
													echo '<option value="'.mysql_result($run_players_query, $i, 'name').'">'.mysql_result($run_players_query, $i, 'name').'</option>';
												}

												$i++;
											}
										?>
									</select>
									<?php if ($profile_table != 'players') { ?>
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
									<?php } ?>
								</td>
								<td>
									<input type="submit" name="submit" value="View Profile" class="form_submit">
								</td>
							</tr>
						</table>
					</form>
				</div>
				<h1 class="main_heading">Individual Stats For <?php echo $playerName; if ($profile_table != 'players') { echo ' (<span class="season">'.$season_title.'</span>)'; } ?></h1>
				<div id="ind_rank">
					<table>
						<tr>
							<td><img src="imgs/rank_icon.png" alt="icon" width="100" height="100" /></td>
							<td><span><?php echo 'Rank: '.mysql_result($run_rank_query, 0, 'rank'); ?></span></td>
						</tr>
					</table>
				</div>
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
							</table>
						</td>
					</tr>
				</table>
				<?php if ($profile_table == 'players') { ?>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Seasons</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Season</th>
									<th class="fixed_cell" title="Games Played">GP</th>
									<th class="fixed_cell" title="Wins">W</th>
									<th class="fixed_cell" title="Losses">L</th>
									<th class="fixed_cell" title="Overtime Losses">OTL</th>
									<th class="fixed_cell" title="Cup Differential">DIF</th>
									<th class="fixed_cell" title="Winning Percentage">WP</th>
									<th class="fixed_cell" title="Shooting Percentage">SP</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									while ($i < mysql_num_rows($run_seasons_query))
									{
										$run_ind_season_query = mysql_query('SELECT * FROM `'.mysql_result($run_seasons_query, $i, 'season').'` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `name`="'.$playerName.'"');

										$ind_season_title = mysql_result($run_seasons_query, $i, 'season');

										$index = strpos($ind_season_title, '_');
										$ind_season_title = substr($ind_season_title, $index + 1);
										$index = strpos($ind_season_title, '_');
										$ind_season_title = ucfirst(substr($ind_season_title, 0, $index).' '.substr($ind_season_title, $index + 1));

										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?player='.mysql_result($run_rack_query, $i, 'name').'&season='.mysql_result($run_seasons_query, $i, 'season').'">'.$ind_season_title.'</a></td>';

										if (mysql_num_rows($run_ind_season_query) == 1)
										{
											echo '<td class="fixed_cell">'.mysql_result($run_ind_season_query, $i, 'games_played').'</td>';
											echo '<td class="fixed_cell">'.mysql_result($run_ind_season_query, $i, 'wins').'</td>';
											echo '<td class="fixed_cell">'.mysql_result($run_ind_season_query, $i, 'losses').'</td>';
											echo '<td class="fixed_cell">'.mysql_result($run_ind_season_query, $i, 'ot_losses').'</td>';
											echo '<td class="fixed_cell">'.mysql_result($run_ind_season_query, $i, 'cup_dif').'</td>';

											if (mysql_result($run_ind_season_query, $i, 'games_played') > 0)
											{
												echo '<td class="fixed_cell">'.number_format((mysql_result($run_ind_season_query, $i, 'wins') / mysql_result($run_ind_season_query, $i, 'games_played')) * 100, 2, '.', '').'</td>';
											}
											else
											{
												echo '<td class="fixed_cell">'.number_format(0.0, 2, '.', '').'</td>';
											}

											echo '<td class="fixed_cell">'.number_format(mysql_result($run_ind_season_query, $i, 'shooting_percentage') * 100, 2, '.', '').'</td>';
											echo '</tr>';
										}
										else
										{
											echo '<td class="fixed_cell">0</td>';
											echo '<td class="fixed_cell">0</td>';
											echo '<td class="fixed_cell">0</td>';
											echo '<td class="fixed_cell">0</td>';
											echo '<td class="fixed_cell">0</td>';
											echo '<td class="fixed_cell">0.00</td>';
											echo '<td class="fixed_cell">0.00</td>';
											echo '</tr>';
										}

										$i++;
									}
								?>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
				<?php } ?>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Achievements</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th title="Achievement Icon">Icon</th>
									<th class="fixed_cell" title="Achievement Title">Achievement</th>
									<th class="fixed_cell" title="Number of This Achievement Earned">Count</th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td><img src="imgs/sharpshooter.png" alt="icon" height="30" width="30"></td>
									<td title="Hit five cups in a row in a game">Sharpshooter</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_shs') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/michael_jordan.png" alt="icon" height="30" width="30"></td>
									<td title="Go on fire two or more times in a game">Michael Jordan</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_mj') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/the_kid_can_play.png" alt="icon" height="30" width="30"></td>
									<!-- Formerly "Can I Buy A Vowel?" -->
									<td title="Sink all the cups for your team in a game (and your team must win)">The Kid Can Play</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_cibav') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/heartbreak_city.png" alt="icon" height="30" width="30"></td>
									<td title="Win a game after being down by five or more cups">Heartbreak City</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_hc') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/caught_with_their_pants_down.png" alt="icon" height="30" width="30"></td>
									<td title="Hit two or more bounce shots in a game">Caught With Their Pants Down</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_cwtpd') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/porn_star.png" alt="icon" height="30" width="30"></td>
									<td title="Hit two or more gang-bangs in a game">Porn Star</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_ps') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/perfection.png" alt="icon" height="30" width="30"></td>
									<td title="Shoot one-hundred percent in a game">Perfection</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_per') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/down_but_not_out.png" alt="icon" height="30" width="30"></td>
									<td title="As an individual, successfully complete two or more redemptions in a game">Down But Not Out</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_dbno') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/marathon.png" alt="icon" height="30" width="30"></td>
									<td title="Compete in a game that goes to triple overtime">Marathon</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_mar') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/first_degree_murder.png" alt="icon" height="30" width="30"></td>
									<td title="Win a game before the other team gets a re-rack (ten-cup start)">First Degree Murder</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_fdm') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/comeback_kill.png" alt="icon" height="30" width="30"></td>
									<td title="Sink a cup after missing five or more shots in a row">Comeback Kill</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_ck') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/bill_buckner.png" alt="icon" height="30" width="30"></td>
									<td title="Commit two or more errors in a game">Bill Buckner</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_bb') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/bitch_cup.png" alt="icon" height="30" width="30"></td>
									<td title="Hit the middle cup first on a ten-cup rack">Bitch Cup</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_bc') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/bankruptcy.png" alt="icon" height="30" width="30"></td>
									<td title="Sink no cups in a game">Bankruptcy</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_bank') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/skunked.png" alt="icon" height="30" width="30"></td>
									<td title="Lose a game before getting a re-rack (ten-cup start)">Skunked</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_skunk') ?></td>
								</tr>
								<tr>
									<td></td>
									<td><img src="imgs/stevie_wonder.png" alt="icon" height="30" width="30"></td>
									<td title="Miss ten shots in a row in a game">Stevie Wonder</td>
									<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'a_sw') ?></td>
								</tr>
								</tbody>
							</table>
						</td>
						<?php if ($profile_table == 'players') { ?>
						<td>
							<h2 class="sub_heading">Unlocked Achievements</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th title="Achievement Icon">Icon</th>
									<th class="fixed_cell" title="Achievement Title">Achievement</th>
									<th class="fixed_cell" title="Number of This Achievement Earned">Count</th>
									<th title="How to Unlock the Achievement">How To Unlock</th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<?php if ((mysql_result($run_record_query, 0, 'wins') + mysql_result($run_record_query, 0, 'losses') + mysql_result($run_record_query, 0, 'ot_losses')) >= 200) { ?>
										<td><img src="imgs/binge_drinker.png" alt="icon" height="30" width="30"></td>
										<td title="Binge Drinker">Binge Drinker</td>
										<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'ua_alc') ?></td>
										<td>Play in consecutive overtime games.</td>
									<?php } else { ?>
										<td>*</td>
										<td>**********</td>
										<td class="fixed_cell">*</td>
										<td>Complete the "Games Played" milestone.</td>
									<?php } ?>
								</tr>
								<tr>
									<td></td>
									<?php if (mysql_result($run_record_query, 0, 'wins') >= 100) { ?>
										<td><img src="imgs/superstar.png" alt="icon" height="30" width="30"></td>
										<td title="Superstar">Superstar</td>
										<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'ua_oah') ?></td>
										<td>Win five games in a row.</td>
									<?php } else { ?>
										<td>*</td>
										<td>**********</td>
										<td class="fixed_cell">*</td>
										<td>Complete the "Wins" milestone.</td>
									<?php } ?>
								</tr>
								<tr>
									<td></td>
									<?php if (mysql_result($run_shooting_query, 0, 'hits') >= 1000) { ?>
										<td><img src="imgs/serial_killer.png" alt="icon" height="30" width="30"></td>
										<td title="Serial Killer">Serial Killer</td>
										<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'ua_ce') ?></td>
										<td>Hit ten cups in a game.</td>
									<?php } else { ?>
										<td>*</td>
										<td>**********</td>
										<td class="fixed_cell">*</td>
										<td>Complete the "Cups Hit" milestone.</td>
									<?php } ?>
								</tr>
								<tr>
									<td></td>
									<?php if (mysql_result($run_shooting_query, 0, 'bounces') >= 50) { ?>
										<td><img src="imgs/magician.png" alt="icon" height="30" width="30"></td>
										<td title="Magician">Magician</td>
										<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'ua_slip') ?></td>
										<td>Hit two consecutive bounce shots without missing.</td>
									<?php } else { ?>
										<td>*</td>
										<td>**********</td>
										<td class="fixed_cell">*</td>
										<td>Complete the "Bounces Hit" milestone.</td>
									<?php } ?>
								</tr>
								<tr>
									<td></td>
									<?php if (mysql_result($run_redemption_query, 0, 'redemp_succs') >= 50) { ?>
										<td><img src="imgs/immortal.png" alt="icon" height="30" width="30"></td>
										<td title="Immortal">Immortal</td>
										<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'ua_dciac') ?></td>
										<td>Successfully complete a redemption and go on to win the game in the first overtime.</td>
									<?php } else { ?>
										<td>*</td>
										<td>**********</td>
										<td class="fixed_cell">*</td>
										<td>Complete the "Redemption Successes" milestone.</td>
									<?php } ?>
								</tr>
								<tr>
									<td></td>
									<?php if (mysql_result($run_rack_query, 0, 'h1') >= 100) { ?>
										<td><img src="imgs/marksman.png" alt="icon" height="30" width="30"></td>
										<td title="Marksman">Marksman</td>
										<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'ua_dth') ?></td>
										<td>Hit three last cups in a game.</td>
									<?php } else { ?>
										<td>*</td>
										<td>**********</td>
										<td class="fixed_cell">*</td>
										<td>Complete the "Last Cups Hit" milestone.</td>
									<?php } ?>
								</tr>
								<tr>
									<td></td>
									<?php if (mysql_result($run_achievement_sum_query, 0) >= 100) { ?>
										<td><img src="imgs/seen_it_all.png" alt="icon" height="30" width="30"></td>
										<td title="Seen It All">Seen It All</td>
										<td class="fixed_cell"><?php echo mysql_result($run_achievement_query, 0, 'ua_show') ?></td>
										<td>Earn three achievements in a game.</td>
									<?php } else { ?>
										<td>*</td>
										<td>**********</td>
										<td class="fixed_cell">*</td>
										<td>Complete the "Achievements Earned" milestone.</td>
									<?php } ?>
								</tr>
								</tbody>
							</table>
						</td>
						<?php } ?>
					</tr>
				</table>
				<?php if ($profile_table == 'players') { ?>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Milestones</h2>
							<h3 class="milestone_header">Games Played</h3>
							<table class="milestone">
								<tr>
									<?php if ($gamesPlayed > 200) { $gamesPlayed = 200; } ?>
									<td><meter value="<?php echo $gamesPlayed; ?>" min="0" max="200" class="milestone_meter"></meter></td>
									<td class="fixed_cell_large"><?php echo $gamesPlayed; ?> / 200</td>
									<td>Status: <?php if ($gamesPlayed >= 200) { echo '<span class="completed">Completed</span>'; } else { echo '<span class="incomplete">Incomplete</span>'; } ?></td>
								</tr>
							</table>
							<h3 class="milestone_header">Wins</h3>
							<table class="milestone">
								<tr>
									<?php $wins = mysql_result($run_record_query, 0, 'wins'); if ($wins > 100) { $wins = 100; } ?>
									<td><meter value="<?php echo $wins; ?>" min="0" max="100" class="milestone_meter"></meter></td>
									<td class="fixed_cell_large"><?php echo $wins; ?> / 100</td>
									<td>Status: <?php if ($wins >= 100) { echo '<span class="completed">Completed</span>'; } else { echo '<span class="incomplete">Incomplete</span>'; } ?></td>
								</tr>
							</table>
							<h3 class="milestone_header">Cups Hit</h3>
							<table class="milestone">
								<tr>
									<?php $hits = mysql_result($run_shooting_query, 0, 'hits'); if ($hits > 1000) { $hits = 1000; } ?>
									<td><meter value="<?php echo $hits; ?>" min="0" max="1000" class="milestone_meter"></meter></td>
									<td class="fixed_cell_large"><?php echo $hits; ?> / 1000</td>
									<td>Status: <?php if ($hits >= 1000) { echo '<span class="completed">Completed</span>'; } else { echo '<span class="incomplete">Incomplete</span>'; } ?></td>
								</tr>
							</table>
							<h3 class="milestone_header">Bounces Hit</h3>
							<table class="milestone">
								<tr>
									<?php $bounces = mysql_result($run_shooting_query, 0, 'bounces'); if ($bounces > 50) { $bounces = 50; } ?>
									<td><meter value="<?php echo $bounces; ?>" min="0" max="50" class="milestone_meter"></meter></td>
									<td class="fixed_cell_large"><?php echo $bounces; ?> / 50</td>
									<td>Status: <?php if ($bounces >= 50) { echo '<span class="completed">Completed</span>'; } else { echo '<span class="incomplete">Incomplete</span>'; } ?></td>
								</tr>
							</table>
							<h3 class="milestone_header">Redemption Successes</h3>
							<table class="milestone">
								<tr>
									<?php $redemp_succs = mysql_result($run_redemption_query, 0, 'redemp_succs'); if ($redemp_succs > 50) { $redemp_succs = 50; } ?>
									<td><meter value="<?php echo $redemp_succs; ?>" min="0" max="50" class="milestone_meter"></meter></td>
									<td class="fixed_cell_large"><?php echo $redemp_succs; ?> / 50</td>
									<td>Status: <?php if ($redemp_succs >= 50) { echo '<span class="completed">Completed</span>'; } else { echo '<span class="incomplete">Incomplete</span>'; } ?></td>
								</tr>
							</table>
							<h3 class="milestone_header">Last Cups Hit</h3>
							<table class="milestone">
								<tr>
									<?php $h1 = mysql_result($run_shooting_query, 0, 'h1'); if ($h1 > 100) { $h1 = 100; } ?>
									<td><meter value="<?php echo $h1; ?>" min="0" max="100" class="milestone_meter"></meter></td>
									<td class="fixed_cell_large"><?php echo $h1; ?> / 100</td>
									<td>Status: <?php if ($h1 >= 100) { echo '<span class="completed">Completed</span>'; } else { echo '<span class="incomplete">Incomplete</span>'; } ?></td>
								</tr>
							</table>
							<h3 class="milestone_header">Achievements Earned</h3>
							<table class="milestone">
								<tr>
									<?php $achievement_sum = mysql_result($run_achievement_sum_query, 0); if ($achievement_sum > 100) { $achievement_sum = 100; } ?>
									<td><meter value="<?php echo $achievement_sum; ?>" min="0" max="100" class="milestone_meter"></meter></td>
									<td class="fixed_cell_large"><?php echo $achievement_sum; ?> / 100</td>
									<td>Status: <?php if ($achievement_sum >= 100) { echo '<span class="completed">Completed</span>'; } else { echo '<span class="incomplete">Incomplete</span>'; } ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<?php } ?>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Results</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Winning Team</th>
									<th>Losing Team</th>
									<th class="fixed_cell" title="Cups Remaining">CR</th>
									<th class="fixed_cell" title="Number of Overtimes">OTS</th>
									<th class="fixed_cell" title="Date">Date</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									while ($i < mysql_num_rows($run_results_query))
									{
										$result_date = getdate(strtotime(mysql_result($run_results_query, $i, 'date')));
										$result_date = $result_date['month'].' '.$result_date['mday'].', '.$result_date['year'];

										$winning_team = mysql_result($run_results_query, $i, 'winning_team');
										$losing_team = mysql_result($run_results_query, $i, 'team_one_name');
										$cups_remaining = mysql_result($run_results_query, $i, 'team_two_cups_remaining');

										if ($losing_team == $winning_team)
										{
											$losing_team = mysql_result($run_results_query, $i, 'team_two_name');
											$cups_remaining = mysql_result($run_results_query, $i, 'team_one_cups_remaining');
										}

										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="team_profiles.php?team='.$winning_team.'">'.$winning_team.'</a></td>';
										echo '<td class="highlight_cell"><a href="team_profiles.php?team='.$losing_team.'">'.$losing_team.'</a></td>';
										echo '<td class="fixed_cell">'.$cups_remaining.'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_results_query, $i, 'number_of_ots').'</td>';
										echo '<td class="highlight_cell"><a href="detail.php?game='.mysql_result($run_results_query, $i, 'id').'">'.$result_date.'</a></td>';
										echo '</tr>';

										$i++;
									}
								?>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
				<?php } else { ?>
					<div id="profile_form">
						<form action="player_profiles.php" method="get">
							<table>
								<tr>
									<td>
										<select name="player" class="form_input">
											<?php 
												$i = 0;

												while ($i < mysql_num_rows($run_players_query))
												{
													if ($playerName == mysql_result($run_players_query, $i, 'name'))
													{
														echo '<option value="'.mysql_result($run_players_query, $i, 'name').'" selected>'.mysql_result($run_players_query, $i, 'name').'</option>';
													}
													else
													{
														echo '<option value="'.mysql_result($run_players_query, $i, 'name').'">'.mysql_result($run_players_query, $i, 'name').'</option>';
													}

													$i++;
												}
											?>
										</select>
									</td>
									<td>
										<input type="submit" name="submit" value="View Profile" class="form_submit">
									</td>
								</tr>
							</table>
						</form>
					</div>
					<div id="account_already_exists">
						<?php if ($profile_table == 'players') { ?>
						<p>The player you are looking for does not exist.</p>
						<?php } else { ?>
						<p>The player you are looking for has not played a game yet this season.</p>
						<?php } ?>
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