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

	if (isset($_GET['player_one']) && isset($_GET['player_two']))
	{
		// Check for SQL injection.
		if (!stripos($_GET['player_one'], 'drop database') && !stripos($_GET['player_two'], 'drop database'))
		{
			$playerOne = $_GET['player_one'];
			$playerTwo = $_GET['player_two'];
		}
	}

	///////////////////
	// Query Players //
	$players_query = 'SELECT `name` FROM `players` WHERE `id_registrations`="'.$_SESSION['userid'].'" ORDER BY `name` ASC';
	$run_players_query = mysql_query($players_query);
	///////////////////
}

?>

<html>
<head>
<title>Team Profiles</title>
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
					<form action="team_profiles.php" method="get">
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
									<span> and </span>
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
								</td>
								<td>
									<input type="submit" name="submit" value="View Profile" class="form_submit">
								</td>
							</tr>
						</table>
					</form>
				</div>
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