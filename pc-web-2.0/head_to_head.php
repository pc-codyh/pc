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

	$playerOne = '';
	$playerTwo = '';
	$playerThree = '';
	$playerFour = '';

	if (isset($_GET['player_one']) && isset($_GET['player_two']) && ($_GET['player_one'] != $_GET['player_two']))
	{
		if (!stripos($_GET['player_one'], 'drop database') && !stripos($_GET['player_two'], 'drop database'))
		{
			$playerOne = $_GET['player_one'];
			$playerTwo = $_GET['player_two'];
		}
	}

	if (isset($_GET['player_three']) && isset($_GET['player_four']) && ($_GET['player_three'] != $_GET['player_four']))
	{
		if (!stripos($_GET['player_three'], 'drop database') && !stripos($_GET['player_four'], 'drop database'))
		{
			$playerThree = $_GET['player_three'];
			$playerFour = $_GET['player_four'];
		}
	}

	///////////////////
	// Query Players //
	$players_query = 'SELECT `name` FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `name` ASC';
	$run_players_query = mysql_query($players_query);
	///////////////////

	///////////////////
	// Query Results //
	$results_query = 'SELECT * FROM `games` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND (((`team_one_player_one`="'.$playerOne.'" OR `team_one_player_two`="'.$playerOne.'") AND (`team_one_player_one`="'.$playerTwo.'" OR `team_one_player_two`="'.$playerTwo.'") AND (`team_two_player_one`="'.$playerThree.'" OR `team_two_player_two`="'.$playerThree.'") AND (`team_two_player_one`="'.$playerFour.'" OR `team_two_player_two`="'.$playerFour.'")) OR ((`team_one_player_one`="'.$playerThree.'" OR `team_one_player_two`="'.$playerThree.'") AND (`team_one_player_one`="'.$playerFour.'" OR `team_one_player_two`="'.$playerFour.'") AND (`team_two_player_one`="'.$playerOne.'" OR `team_two_player_two`="'.$playerOne.'") AND (`team_two_player_one`="'.$playerTwo.'" OR `team_two_player_two`="'.$playerTwo.'"))) ORDER BY `id` DESC';
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
				<div id="profile_form">
					<form action="head_to_head.php" method="get">
						<table>
							<tr>
								<td>
									<select name="player_one" class="form_input">
										<?php 
											$i = 0;

											while ($i < mysql_num_rows($run_players_query))
											{
												if ($playerOne == mysql_result($run_players_query, $i, 'name'))
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
									<select name="player_two" class="form_input">
										<?php 
											$i = 0;

											while ($i < mysql_num_rows($run_players_query))
											{
												if ($playerTwo == mysql_result($run_players_query, $i, 'name'))
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
									<span> VS </span>
									<select name="player_three" class="form_input">
										<?php 
											$i = 0;

											while ($i < mysql_num_rows($run_players_query))
											{
												if ($playerThree == mysql_result($run_players_query, $i, 'name'))
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
									<select name="player_four" class="form_input">
										<?php 
											$i = 0;

											while ($i < mysql_num_rows($run_players_query))
											{
												if ($playerFour == mysql_result($run_players_query, $i, 'name'))
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
									<input type="submit" name="submit" value="View Matchup" class="form_submit matchup_submit">
								</td>
							</tr>
						</table>
					</form>
				</div>
				<?php if ($playerOne != '' && $playerTwo != '' && $playerThree != '' && $playerFour != '' && mysql_num_rows($run_results_query) > 0) { ?>
				<h1 class="main_heading">Head-To-Head Results</h1>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Record</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Team</th>
									<th class="fixed_cell" title="Games Played">GP</th>
									<th class="fixed_cell" title="Wins">W</th>
									<th class="fixed_cell" title="Losses">L</th>
									<th class="fixed_cell" title="Overtime Losses">OTL</th>
									<th class="fixed_cell" title="Winning Percentage">WP</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$teamOneGamesPlayed = 0;
									$teamOneWins = 0;
									$teamOneLosses = 0;
									$teamOneOTLosses = 0;
									$teamOneWP = 0.0;
									$teamTwoGamesPlayed = 0;
									$teamTwoWins = 0;
									$teamTwoLosses = 0;
									$teamTwoOTLosses = 0;
									$teamTwoWP = 0.0;

									$i = 0;

									while ($i < mysql_num_rows($run_results_query))
									{
										$teamOneGamesPlayed++;
										$teamTwoGamesPlayed++;

										if (mysql_result($run_results_query, $i, 'winning_team') == ($playerOne.' and '.$playerTwo) || mysql_result($run_results_query, $i, 'winning_team') == ($playerTwo.' and '.$playerOne))
										{
											$teamOneWins++;

											if (mysql_result($run_results_query, $i, 'number_of_ots') > 0)
											{
												$teamTwoOTLosses++;
											}
											else
											{
												$teamTwoLosses++;
											}
										}
										else
										{
											$teamTwoWins++;

											if (mysql_result($run_results_query, $i, 'number_of_ots') > 0)
											{
												$teamOneOTLosses++;
											}
											else
											{
												$teamOneLosses++;
											}
										}

										$i++;
									}

									if ($teamOneGamesPlayed > 0)
									{
										$teamOneWP = ($teamOneWins / $teamOneGamesPlayed);
									}

									if ($teamTwoGamesPlayed > 0)
									{
										$teamTwoWP = ($teamTwoWins / $teamTwoGamesPlayed);
									}

									echo '<tr>';
									echo '<td></td>';
									echo '<td class="highlight_cell"><a href="team_profiles.php?team='.$playerOne.' and '.$playerTwo.'">'.$playerOne.' and '.$playerTwo.'</a></td>';
									echo '<td class="fixed_cell">'.$teamOneGamesPlayed.'</td>';
									echo '<td class="fixed_cell">'.$teamOneWins.'</td>';
									echo '<td class="fixed_cell">'.$teamOneLosses.'</td>';
									echo '<td class="fixed_cell">'.$teamOneOTLosses.'</td>';
									echo '<td class="fixed_cell">'.number_format($teamOneWP * 100, 2, '.', '').'</td>';
									echo '</tr>';

									echo '<tr>';
									echo '<td></td>';
									echo '<td class="highlight_cell"><a href="team_profiles.php?team='.$playerThree.' and '.$playerFour.'">'.$playerThree.' and '.$playerFour.'</a></td>';
									echo '<td class="fixed_cell">'.$teamTwoGamesPlayed.'</td>';
									echo '<td class="fixed_cell">'.$teamTwoWins.'</td>';
									echo '<td class="fixed_cell">'.$teamTwoLosses.'</td>';
									echo '<td class="fixed_cell">'.$teamTwoOTLosses.'</td>';
									echo '<td class="fixed_cell">'.number_format($teamTwoWP * 100, 2, '.', '').'</td>';
									echo '</tr>';
								?>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
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
					<div id="account_already_exists">
						<?php if (isset($_GET['player_one']) && isset($_GET['player_two']) && isset($_GET['player_three']) && isset($_GET['player_four'])) { ?>
						<p>The matchup you are looking for does not exist.</p>
						<?php } else { ?>
						<p>Select a matchup.</p>
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