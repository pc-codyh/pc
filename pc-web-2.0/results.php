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

	///////////////////
	// Query Results //
	$results_query = 'SELECT * FROM `games` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `id` DESC';
	$run_results_query = mysql_query($results_query);
	///////////////////
}

?>

<html>
<head>
<title>Results</title>
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
				<h1 class="main_heading">Overall Results</h1>
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
					<p>Please login above to view stats.</p>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php include 'footer.inc.php'; ?>
</body>
</html>