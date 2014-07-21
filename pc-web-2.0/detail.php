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

	$message = '';
	$game_id = '';

	if (isset($_GET['game']))
	{
		if (!stripos($_GET['game'], 'drop database'))
		{
			$game_id = $_GET['game'];
		}
	}

	if ($game_id != '')
	{
		$run_game_query = mysql_query('SELECT * FROM `games` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `id`="'.$game_id.'"');

		if (mysql_num_rows($run_game_query) == 1)
		{
			$detail_query = 'SELECT * FROM `season_detail_results` WHERE `id_registrations`="'.$_SESSION['userid'].'" AND `id_games`="'.$game_id.'"';

			$run_detail_query = mysql_query($detail_query);
			
			$result_date = getdate(strtotime(mysql_result($run_game_query, 0, 'date')));
			$result_date = $result_date['month'].' '.$result_date['mday'].', '.$result_date['year'];

			if (mysql_num_rows($run_detail_query) == 0)
			{
				$message = 'Detailed results are unavailable for this game.';
			}
		}
		else
		{
			$message = 'Detailed results are unavailable for this game.';
		}
	}
	else
	{
		$message = 'The game you are looking for does not exist.';
	}
}

?>

<html>
<head>
<title>Game Details</title>
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
				<?php if ($message == '') { ?>
				<h1 class="main_heading">Detailed Results (<?php echo $result_date; ?>)</h1>
				<table class="stats_divider">
					<tr>
						<td>
							<h2 class="sub_heading">Result</h2>
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
									$winning_team = mysql_result($run_game_query, 0, 'winning_team');
									$losing_team = mysql_result($run_game_query, 0, 'team_one_name');
									$cups_remaining = mysql_result($run_game_query, 0, 'team_two_cups_remaining');

									if ($losing_team == $winning_team)
									{
										$losing_team = mysql_result($run_game_query, 0, 'team_two_name');
										$cups_remaining = mysql_result($run_game_query, 0, 'team_one_cups_remaining');
									}

									echo '<tr>';
									echo '<td></td>';
									echo '<td class="highlight_cell"><a href="team_profiles.php?team='.$winning_team.'">'.$winning_team.'</a></td>';
									echo '<td class="highlight_cell"><a href="team_profiles.php?team='.$losing_team.'">'.$losing_team.'</a></td>';
									echo '<td class="fixed_cell">'.$cups_remaining.'</td>';
									echo '<td class="fixed_cell">'.mysql_result($run_game_query, 0, 'number_of_ots').'</td>';
									echo '<td class="highlight_cell"><a href="detail.php?team1='.$winning_team.'&team2='.$losing_team.'&game='.mysql_result($run_game_query, 0, 'id').'">'.$result_date.'</a></td>';
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
							<h2 class="sub_heading">Details</h2>
							<table class="stats_table tablesorter">
								<thead>
								<tr class="stats_table_header_row show_pointer">
									<th title="Row">Row</th>
									<th>Name</th>
									<th class="fixed_cell" title="Shots">S</th>
									<th class="fixed_cell" title="Hits">H</th>
									<th class="fixed_cell" title="Shooting Percentage">SP</th>
									<th class="fixed_cell" title="Bounces">B</th>
									<th class="fixed_cell" title="Gang-Bangs">GB</th>
									<th class="fixed_cell" title="Errors">E</th>
									<th class="fixed_cell" title="Number of Times Heating Up">HU</th>
									<th class="fixed_cell" title="Number of Times On Fire">OF</th>
									<th class="fixed_cell" title="Redemption Attempts">RAT</th>
									<th class="fixed_cell" title="Redemption Successes">RSU</th>
								</tr>
								</thead>
								<tbody>
								<?php 
									$i = 0;

									while ($i < mysql_num_rows($run_detail_query))
									{
										echo '<tr>';
										echo '<td></td>';
										echo '<td class="highlight_cell"><a href="player_profiles.php?name='.mysql_result($run_detail_query, $i, 'name').'">'.mysql_result($run_detail_query, $i, 'name').'</a></td>';
										echo '<td class="fixed_cell">'.mysql_result($run_detail_query, $i, 'shots').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_detail_query, $i, 'hits').'</td>';
										echo '<td class="fixed_cell">'.number_format(mysql_result($run_detail_query, $i, 'shot_perc'), 2, '.', '').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_detail_query, $i, 'bounces').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_detail_query, $i, 'gang_bangs').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_detail_query, $i, 'errors').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_detail_query, $i, 'heating_up').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_detail_query, $i, 'on_fire').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_detail_query, $i, 'redemp_atmps').'</td>';
										echo '<td class="fixed_cell">'.mysql_result($run_detail_query, $i, 'redemp_succs').'</td>';
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