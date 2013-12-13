<div id="top">
	<img src="imgs/pongchamp_large.png" alt="PongChamp" width="600" height="123" />
</div>
<div id="middle">
	<?php require 'login.inc.php'; ?>
</div>
<?php if ($is_logged_in) { ?>
	<div id="recent_results">
		<ul id="marquee" class="marquee">
			<?php
				while ($i < $count)
				{
					$winning_team = mysql_result($run_recent_results_query, $i, 'winning_team');
					$losing_team = mysql_result($run_recent_results_query, $i, 'team_one_name');
					$cups_rem = mysql_result($run_recent_results_query, $i, 'team_two_cups_remaining');

					if ($winning_team == $losing_team)
					{
						$losing_team = mysql_result($run_recent_results_query, $i, 'team_two_name');
						$cups_rem = mysql_result($run_recent_results_query, $i, 'team_one_cups_remaining');
					}

					$date = getdate(strtotime(mysql_result($run_recent_results_query, $i, 'date')));
					$date = $date['month'].' '.$date['mday'].', '.$date['year'];

					$num_ots = mysql_result($run_recent_results_query, $i, 'number_of_ots');

					$overtime_text = ' ';
					$cups_text = ' cups';

					if ($num_ots > 0)
					{
						$overtime_text = ' (OT'.$num_ots.')';
					}

					if ($cups_rem == 1)
					{
						$cups_text = ' cup';
					}

					echo '<li>Recent Results: <span class="bold">'.$winning_team.'</span> def. '.$losing_team.' by '.$cups_rem.$cups_text.$overtime_text.' on '.$date.'</li>';

					$i++;
				}

				if ($count == 0)
				{
					echo '<li>There are no recent results to display.</li>';
				}
			?>
		</ul>
	</div>
<?php } ?>