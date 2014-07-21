<?php

// Every visible page requires these two files.
require 'connect.inc.php';

date_default_timezone_set('America/Los_Angeles');

function get_season($month)
{
	if ($month == '12' || $month == '01' || $month == '02')
	{
		return 'winter';
	}
	else if ($month == '03' || $month == '04' || $month == '05')
	{
		return 'spring';
	}
	else if ($month == '06' || $month == '07' || $month == '08')
	{
		return 'summer';
	}
	else if ($month == '09' || $month == '10' || $month == '11')
	{
		return 'fall';
	}

	return null;
}

$current_date = date('Y-m');
$index = strpos($current_date, '-');
$current_year = (trim(substr($current_date, 0, $index)));
$current_month = trim(substr($current_date, $index + 1));

if ($current_month == '12')
{
	$current_year = $current_year + 1;
}

$current_season = 'season_'.get_season($current_month).'_'.$current_year;

// Create TABLE for the new season if it doesn't exist yet.
// if (mysql_num_rows(mysql_query('SHOW TABLES LIKE "'.$current_season.'"')) == 0)
// {
$create_table_query ='CREATE TABLE IF NOT EXISTS `'.$current_season.'` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_registrations` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `wins` int(11) NOT NULL,
  `losses` int(11) NOT NULL,
  `ot_losses` int(11) NOT NULL,
  `cup_dif` int(11) NOT NULL,
  `win_streak` int(11) NOT NULL,
  `loss_streak` int(11) NOT NULL,
  `shots` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `hit_streak` int(11) NOT NULL,
  `miss_streak` int(11) NOT NULL,
  `bounces` int(11) NOT NULL,
  `gang_bangs` int(11) NOT NULL,
  `errors` int(11) NOT NULL,
  `redemp_shots` int(11) NOT NULL,
  `redemp_hits` int(11) NOT NULL,
  `redemp_atmps` int(11) NOT NULL,
  `redemp_succs` int(11) NOT NULL,
  `elo_rating` float NOT NULL,
  `s10` int(11) NOT NULL,
  `h10` int(11) NOT NULL,
  `s9` int(11) NOT NULL,
  `h9` int(11) NOT NULL,
  `s8` int(11) NOT NULL,
  `h8` int(11) NOT NULL,
  `s7` int(11) NOT NULL,
  `h7` int(11) NOT NULL,
  `s6` int(11) NOT NULL,
  `h6` int(11) NOT NULL,
  `s5` int(11) NOT NULL,
  `h5` int(11) NOT NULL,
  `s4` int(11) NOT NULL,
  `h4` int(11) NOT NULL,
  `s3` int(11) NOT NULL,
  `h3` int(11) NOT NULL,
  `s2` int(11) NOT NULL,
  `h2` int(11) NOT NULL,
  `s1` int(11) NOT NULL,
  `h1` int(11) NOT NULL,
  `cur_win_streak` int(11) NOT NULL,
  `cur_loss_streak` int(11) NOT NULL,
  `heating_up` int(11) NOT NULL,
  `on_fire` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `compound_rating` float NOT NULL,
  `games_played` int(11) NOT NULL,
  `shooting_percentage` float NOT NULL,
  `redemp_shotperc` float NOT NULL,
  `p10` float NOT NULL,
  `p9` float NOT NULL,
  `p8` float NOT NULL,
  `p7` float NOT NULL,
  `p6` float NOT NULL,
  `p5` float NOT NULL,
  `p4` float NOT NULL,
  `p3` float NOT NULL,
  `p2` float NOT NULL,
  `p1` float NOT NULL,
  `ot_games_played` int(11) NOT NULL,
  `a_shs` int(11) NOT NULL,
  `a_mj` int(11) NOT NULL,
  `a_cibav` int(11) NOT NULL,
  `a_bank` int(11) NOT NULL,
  `a_ck` int(11) NOT NULL,
  `a_hc` int(11) NOT NULL,
  `a_cwtpd` int(11) NOT NULL,
  `a_ps` int(11) NOT NULL,
  `a_sw` int(11) NOT NULL,
  `a_per` int(11) NOT NULL,
  `a_dbno` int(11) NOT NULL,
  `a_bb` int(11) NOT NULL,
  `a_bc` int(11) NOT NULL,
  `a_mar` int(11) NOT NULL,
  `a_fdm` int(11) NOT NULL,
  `a_skunk` int(11) NOT NULL,
  `ua_alc` int(11) NOT NULL,
  `ua_oah` int(11) NOT NULL,
  `ua_ce` int(11) NOT NULL,
  `ua_slip` int(11) NOT NULL,
  `ua_dciac` int(11) NOT NULL,
  `ua_dth` int(11) NOT NULL,
  `ua_show` int(11) NOT NULL,
  `last_game_ot` int(11) NOT NULL,
  PRIMARY KEY (`id`))';

$run_create_table_query = mysql_query($create_table_query);

// Add the new season to the seasons table so we can populate the dropdown list.
$add_season_query = 'SELECT * FROM `seasons` WHERE `season`="'.$current_season.'"';

if ($run_add_season_query = mysql_query($add_season_query))
{
	if (mysql_num_rows($run_add_season_query) == 0)
	{
		mysql_query('INSERT INTO `seasons`(`season`) VALUES ("'.$current_season.'")');
	}
}

// Only do something if there is valid POST data.
if (isset($_POST['username']))
{
	$user_id = mysql_query('SELECT `id` FROM `registrations` WHERE `username`="'.$_POST['username'].'"');
	$user_id = mysql_result($user_id, 0, 'id');

	//////////////////////////////////////////////////
	// ADD INDIVIDUAL RESULT TO DETAIL SEASON TABLE //
	//////////////////////////////////////////////////
	$name = $_POST['name'];

	$latest_game_query = 'SELECT `id` FROM `games` WHERE `id_registrations`="'.$user_id.'" ORDER BY `id` DESC';
	$run_latest_game_query = mysql_query($latest_game_query);
	$latest_game = (mysql_result($run_latest_game_query, 0, 'id') + 1);

	$shots = $_POST['shots'];
	$hits = $_POST['hits'];

	if ($shots > 0)
	{
		$shot_perc = (((float) $hits / (float) $shots) * 100.0);
	}
	else
	{
		$shot_perc = 0.0;
	}
	 
	$hit_streak = $_POST['hit_streak'];
	$miss_streak = $_POST['miss_streak'];
	$bounces = $_POST['bounces'];
	$gang_bangs = $_POST['gang_bangs'];
	$errors = $_POST['errors'];
	$heating_up = $_POST['heating_up'];
	$on_fire = $_POST['on_fire'];
	$redemp_shots = $_POST['redemp_shots'];
	$redemp_hits = $_POST['redemp_hits'];
	$redemp_atmps = $_POST['redemp_atmps'];
	$redemp_succs = $_POST['redemp_succs'];
	$result = $_POST['result'];
	$cup_dif = $_POST['cup_dif'];
	$overtime = $_POST['overtime'];

	$add_result_query = 'INSERT INTO `season_detail_results`(`id_registrations`, `id_games`, `name`, `shots`, `hits`, `shot_perc`, `hit_streak`, `miss_streak`, `bounces`, `gang_bangs`, `errors`, `heating_up`, `on_fire`, `redemp_shots`, `redemp_hits`, `redemp_atmps`, `redemp_succs`) VALUES ("'.$user_id.'", "'.$latest_game.'", "'.$name.'", "'.$shots.'", "'.$hits.'", "'.$shot_perc.'", "'.$hit_streak.'", "'.$miss_streak.'", "'.$bounces.'", "'.$gang_bangs.'", "'.$errors.'", "'.$heating_up.'", "'.$on_fire.'", "'.$redemp_shots.'", "'.$redemp_hits.'", "'.$redemp_atmps.'", "'.$redemp_succs.'")';

	// Add the rows to the database.
	$run_add_result_query = mysql_query($add_result_query);
	
	////////////////////////////////////
	// UPDATE SEASON STATS FOR PLAYER //
	////////////////////////////////////

	$get_player_query = 'SELECT * FROM `'.$current_season.'` WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$name.'"';

	if ($run_get_player_query = mysql_query($get_player_query))
	{
		// The player doesn't exist yet, so add it to the database.
		if (mysql_num_rows($run_get_player_query) == 0)
		{
			mysql_query('INSERT INTO `'.$current_season.'`(`id_registrations`, `name`) VALUES ("'.$user_id.'", "'.$name.'")');
		}

		$run_get_player_query = mysql_query($get_player_query);

		// Update ELO Rating once.
		if (isset($_POST['ELO']))
		{
			$K_FACTOR = 20.0;
			$RATE_OF_CHANGE = 400.0;

			$teamELO = 0;
			$opponentELO = 0;

			$teamELO = (mysql_result(mysql_query('SELECT `elo_rating` FROM `'.$current_season.'` WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$name.'"'), 0, 'elo_rating') + mysql_result(mysql_query('SELECT `elo_rating` FROM `'.$current_season.'` WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$_POST['teammate'].'"'), 0, 'elo_rating'));

			$opponentELO = (mysql_result(mysql_query('SELECT `elo_rating` FROM `'.$current_season.'` WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$_POST['opponent1'].'"'), 0, 'elo_rating') + mysql_result(mysql_query('SELECT `elo_rating` FROM `'.$current_season.'` WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$_POST['opponent2'].'"'), 0, 'elo_rating'));

			$expectedValueA = ((1.0) / (1.0 + pow(10.0, ($teamELO - $opponentELO) / $RATE_OF_CHANGE)));
			$expectedValueB = (1.0 - $expectedValueA);

			// Win. ELO goes up.
			if ($result == 2)
			{
				// Go up by the smaller expected value.
				if ($teamELO > $opponentELO)
				{
					// $elo_rating = mysql_result($run_get_player_query, 0, 'elo_rating') + ($K_FACTOR * min($expectedValueA, $expectedValueB));
					$elo_rating_change = ($K_FACTOR * min($expectedValueA, $expectedValueB));
				}
				// Go up by the larger expected value.
				else
				{
					// $elo_rating = mysql_result($run_get_player_query, 0, 'elo_rating') + ($K_FACTOR * max($expectedValueA, $expectedValueB));
					$elo_rating_change = ($K_FACTOR * max($expectedValueA, $expectedValueB));
				}
			}
			// Loss. ELO goes down.
			else
			{
				// Go down by the larger expected value.
				if ($teamELO > $opponentELO)
				{
					// $elo_rating = mysql_result($run_get_player_query, 0, 'elo_rating') - ($K_FACTOR * max($expectedValueA, $expectedValueB));
					$elo_rating_change = (-1.0 * $K_FACTOR * max($expectedValueA, $expectedValueB));
				}
				// Go down by the smaller expected value.
				else
				{
					// $elo_rating = mysql_result($run_get_player_query, 0, 'elo_rating') - ($K_FACTOR * min($expectedValueA, $expectedValueB));
					$elo_rating_change = (-1.0 * $K_FACTOR * min($expectedValueA, $expectedValueB));
				}
			}

			// $elo_rating_change = (float) $elo_rating;
			// $opp_elo_rating_change = (float) (-1.0 * $elo_rating);
			$opp_elo_rating_change = (-1.0 * $elo_rating_change);
		}
		else
		{
			// $elo_rating = mysql_result($run_get_player_query, 0, 'elo_rating');
		}

		// WIN.
		if ($result == 2)
		{
			$wins = mysql_result($run_get_player_query, 0, 'wins') + 1;
			$losses = mysql_result($run_get_player_query, 0, 'losses');
			$ot_losses = mysql_result($run_get_player_query, 0, 'ot_losses');

			if ($overtime == 0)
			{
				$cup_dif = mysql_result($run_get_player_query, 0, 'cup_dif') + $cup_dif;
			}
			else
			{
				$cup_dif = mysql_result($run_get_player_query, 0, 'cup_dif');
			}

			$cur_win_streak = mysql_result($run_get_player_query, 0, 'cur_win_streak') + 1;

			if ($cur_win_streak > mysql_result($run_get_player_query, 0, 'win_streak'))
			{
				$win_streak = $cur_win_streak;
			}
			else
			{
				$win_streak = mysql_result($run_get_player_query, 0, 'win_streak');
			}

			$cur_loss_streak = 0;
			$loss_streak = mysql_result($run_get_player_query, 0, 'loss_streak');
		}
		// OT LOSS.
		else if ($result == 1)
		{
			$wins = mysql_result($run_get_player_query, 0, 'wins');
			$losses = mysql_result($run_get_player_query, 0, 'losses');
			$ot_losses = mysql_result($run_get_player_query, 0, 'ot_losses') + 1;
			$cup_dif = mysql_result($run_get_player_query, 0, 'cup_dif');

			$cur_loss_streak = mysql_result($run_get_player_query, 0, 'cur_loss_streak') + 1;

			if ($cur_loss_streak > mysql_result($run_get_player_query, 0, 'loss_streak'))
			{
				$loss_streak = $cur_loss_streak;
			}
			else
			{
				$loss_streak = mysql_result($run_get_player_query, 0, 'loss_streak');
			}

			$cur_win_streak = 0;
			$win_streak = mysql_result($run_get_player_query, 0, 'win_streak');
		}
		// LOSS.
		else if ($result == 0)
		{
			$wins = mysql_result($run_get_player_query, 0, 'wins');
			$losses = mysql_result($run_get_player_query, 0, 'losses') + 1;
			$ot_losses = mysql_result($run_get_player_query, 0, 'ot_losses');
			$cup_dif = mysql_result($run_get_player_query, 0, 'cup_dif') + $cup_dif;

			$cur_loss_streak = mysql_result($run_get_player_query, 0, 'cur_loss_streak') + 1;

			if ($cur_loss_streak > mysql_result($run_get_player_query, 0, 'loss_streak'))
			{
				$loss_streak = $cur_loss_streak;
			}
			else
			{
				$loss_streak = mysql_result($run_get_player_query, 0, 'loss_streak');
			}

			$cur_win_streak = 0;
			$win_streak = mysql_result($run_get_player_query, 0, 'win_streak');
		}

		if ($overtime == 1)
		{
			$ot_games_played = mysql_result($run_get_player_query, 0, 'ot_games_played') + 1;
		}
		else
		{
			$ot_games_played = mysql_result($run_get_player_query, 0, 'ot_games_played');
		}

		$shots = mysql_result($run_get_player_query, 0, 'shots') + $shots;
		$hits = mysql_result($run_get_player_query, 0, 'hits') + $hits;

		if ($hit_streak <= mysql_result($run_get_player_query, 0, 'hit_streak'))
		{
			$hit_streak = mysql_result($run_get_player_query, 0, 'hit_streak');
		}

		if ($miss_streak <= mysql_result($run_get_player_query, 0, 'miss_streak'))
		{
			$miss_streak = mysql_result($run_get_player_query, 0, 'miss_streak');
		}

		$bounces = mysql_result($run_get_player_query, 0, 'bounces') + $bounces;
		$gang_bangs = mysql_result($run_get_player_query, 0, 'gang_bangs') + $gang_bangs;
		$errors = mysql_result($run_get_player_query, 0, 'errors') + $errors;

		$redemp_shots = mysql_result($run_get_player_query, 0, 'redemp_shots') + $redemp_shots;
		$redemp_hits = mysql_result($run_get_player_query, 0, 'redemp_hits') + $redemp_hits;
		$redemp_atmps = mysql_result($run_get_player_query, 0, 'redemp_atmps') + $redemp_atmps;
		$redemp_succs = mysql_result($run_get_player_query, 0, 'redemp_succs') + $redemp_succs;

		$heating_up = mysql_result($run_get_player_query, 0, 'heating_up') + $heating_up;
		$on_fire = mysql_result($run_get_player_query, 0, 'on_fire') + $on_fire;

		$games_played = mysql_result($run_get_player_query, 0, 'games_played') + 1;

		if ($shots > 0)
		{
			$shooting_percentage = ((float) $hits / (float) $shots);
		}
		else
		{
			$shooting_percentage = 0.0;
		}

		if ($redemp_shots > 0)
		{
			$redemp_shotperc = ((float) $redemp_hits / (float) $redemp_shots);
		}
		else
		{
			$redemp_shotperc = 0.0;
		}

		// Shots per cup.
		for ($i = 0; $i < 10; $i++)
		{
			$shotsPerCup[$i] = mysql_result($run_get_player_query, 0, 's'.($i + 1)) + $_POST['s'.($i + 1)];
		}

		// Hits per cup.
		for ($i = 0; $i < 10; $i++)
		{
			$hitsPerCup[$i] = mysql_result($run_get_player_query, 0, 'h'.($i + 1)) + $_POST['h'.($i + 1)];
		}

		// Percentages per cup.
		for ($i = 0; $i < 10; $i ++)
		{
			if ($shotsPerCup[$i] > 0)
			{
				$percsPerCup[$i] = ((float) $hitsPerCup[$i] / (float) $shotsPerCup[$i]);
			}
			else
			{
				$percsPerCup[$i] = 0.0;
			}
		}

		$a_shs = mysql_result($run_get_player_query, 0, 'a_shs') + $_POST['ach0'];
		$a_mj = mysql_result($run_get_player_query, 0, 'a_mj') + $_POST['ach1'];
		$a_cibav = mysql_result($run_get_player_query, 0, 'a_cibav') + $_POST['ach2'];
		$a_bank = mysql_result($run_get_player_query, 0, 'a_bank') + $_POST['ach3'];
		$a_ck = mysql_result($run_get_player_query, 0, 'a_ck') + $_POST['ach4'];
		$a_hc = mysql_result($run_get_player_query, 0, 'a_hc') + $_POST['ach5'];
		$a_cwtpd = mysql_result($run_get_player_query, 0, 'a_cwtpd') + $_POST['ach6'];
		$a_ps = mysql_result($run_get_player_query, 0, 'a_ps') + $_POST['ach7'];
		$a_sw = mysql_result($run_get_player_query, 0, 'a_sw') + $_POST['ach8'];
		$a_per = mysql_result($run_get_player_query, 0, 'a_per') + $_POST['ach9'];
		$a_dbno = mysql_result($run_get_player_query, 0, 'a_dbno') + $_POST['ach10'];
		$a_bb = mysql_result($run_get_player_query, 0, 'a_bb') + $_POST['ach12'];
		$a_bc = mysql_result($run_get_player_query, 0, 'a_bc') + $_POST['ach13'];
		$a_mar = mysql_result($run_get_player_query, 0, 'a_mar') + $_POST['ach14'];
		$a_fdm = mysql_result($run_get_player_query, 0, 'a_fdm') + $_POST['ach15'];
		$a_skunk = mysql_result($run_get_player_query, 0, 'a_skunk') + $_POST['ach16'];

		$update_season_query = 'UPDATE `'.$current_season.'` SET `wins`="'.$wins.'",`losses`="'.$losses.'",`ot_losses`="'.$ot_losses.'",`cup_dif`="'.$cup_dif.'",`win_streak`="'.$win_streak.'",`loss_streak`="'.$loss_streak.'",`shots`="'.$shots.'",`hits`="'.$hits.'",`hit_streak`="'.$hit_streak.'",`miss_streak`="'.$miss_streak.'",`bounces`="'.$bounces.'",`gang_bangs`="'.$gang_bangs.'",`errors`="'.$errors.'",`redemp_shots`="'.$redemp_shots.'",`redemp_hits`="'.$redemp_hits.'",`redemp_atmps`="'.$redemp_atmps.'",`redemp_succs`="'.$redemp_succs.'",`s10`="'.$shotsPerCup[9].'",`h10`="'.$hitsPerCup[9].'",`s9`="'.$shotsPerCup[8].'",`h9`="'.$hitsPerCup[8].'",`s8`="'.$shotsPerCup[7].'",`h8`="'.$hitsPerCup[7].'",`s7`="'.$shotsPerCup[6].'",`h7`="'.$hitsPerCup[6].'",`s6`="'.$shotsPerCup[5].'",`h6`="'.$hitsPerCup[5].'",`s5`="'.$shotsPerCup[4].'",`h5`="'.$hitsPerCup[4].'",`s4`="'.$shotsPerCup[3].'",`h4`="'.$hitsPerCup[3].'",`s3`="'.$shotsPerCup[2].'",`h3`="'.$hitsPerCup[2].'",`s2`="'.$shotsPerCup[1].'",`h2`="'.$hitsPerCup[1].'",`s1`="'.$shotsPerCup[0].'",`h1`="'.$hitsPerCup[0].'",`cur_win_streak`="'.$cur_win_streak.'",`cur_loss_streak`="'.$cur_loss_streak.'",`heating_up`="'.$heating_up.'",`on_fire`="'.$on_fire.'",`games_played`="'.$games_played.'",`shooting_percentage`="'.$shooting_percentage.'",`redemp_shotperc`="'.$redemp_shotperc.'",`p10`="'.$percsPerCup[9].'",`p9`="'.$percsPerCup[8].'",`p8`="'.$percsPerCup[7].'",`p7`="'.$percsPerCup[6].'",`p6`="'.$percsPerCup[5].'",`p5`="'.$percsPerCup[4].'",`p4`="'.$percsPerCup[3].'",`p3`="'.$percsPerCup[2].'",`p2`="'.$percsPerCup[1].'",`p1`="'.$percsPerCup[0].'",`ot_games_played`="'.$ot_games_played.'",`a_shs`="'.$a_shs.'",`a_mj`="'.$a_mj.'",`a_cibav`="'.$a_cibav.'",`a_bank`="'.$a_bank.'",`a_ck`="'.$a_ck.'",`a_hc`="'.$a_hc.'",`a_cwtpd`="'.$a_cwtpd.'",`a_ps`="'.$a_ps.'",`a_sw`="'.$a_sw.'",`a_per`="'.$a_per.'",`a_dbno`="'.$a_dbno.'",`a_bb`="'.$a_bb.'",`a_bc`="'.$a_bc.'",`a_mar`="'.$a_mar.'",`a_fdm`="'.$a_fdm.'",`a_skunk`="'.$a_skunk.'" WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$name.'"';

		$run_update_season_query = mysql_query($update_season_query);

		// Finally, need to update the compound_rating and rank for all players.
		if (isset($_POST['ELO']))
		{
			// FINALLY, update the ELO ratings.

			// Self.
			$elo_rating = (mysql_result($run_get_player_query, 0, 'elo_rating') + $elo_rating_change);

			if ($elo_rating < 0.0)
			{
				$elo_rating = 0.0;
			}

			mysql_query('UPDATE `'.$current_season.'` SET `elo_rating`="'.$elo_rating.'" WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$name.'"');

			// Teammate.
			$run_teammate_query = mysql_query('SELECT `elo_rating` FROM `'.$current_season.'` WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$_POST['teammate'].'"');

			$elo_rating = (mysql_result($run_teammate_query, 0, 'elo_rating') + $elo_rating_change);

			if ($elo_rating < 0.0)
			{
				$elo_rating = 0.0;
			}

			mysql_query('UPDATE `'.$current_season.'` SET `elo_rating`="'.$elo_rating.'" WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$_POST['teammate'].'"');

			// Opponent1.
			$run_opponent1_query = mysql_query('SELECT `elo_rating` FROM `'.$current_season.'` WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$_POST['opponent1'].'"');

			$elo_rating = (mysql_result($run_opponent1_query, 0, 'elo_rating') + $opp_elo_rating_change);

			if ($elo_rating < 0.0)
			{
				$elo_rating = 0.0;
			}

			mysql_query('UPDATE `'.$current_season.'` SET `elo_rating`="'.$elo_rating.'" WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$_POST['opponent1'].'"');

			// Opponent2.
			$run_opponent2_query = mysql_query('SELECT `elo_rating` FROM `'.$current_season.'` WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$_POST['opponent2'].'"');

			$elo_rating = (mysql_result($run_opponent2_query, 0, 'elo_rating') + $opp_elo_rating_change);

			if ($elo_rating < 0.0)
			{
				$elo_rating = 0.0;
			}

			mysql_query('UPDATE `'.$current_season.'` SET `elo_rating`="'.$elo_rating.'" WHERE `id_registrations`="'.$user_id.'" AND `name`="'.$_POST['opponent2'].'"');

			// UPDATE COMPOUND RATING AND RANK AFTER ELO RATINGS //

			$all_players_query = 'SELECT * FROM `'.$current_season.'` WHERE `id_registrations`="'.$user_id.'"';

			$run_all_players_query = mysql_query($all_players_query);

			$i = 0;

			while ($i < mysql_num_rows($run_all_players_query))
			{
				$compound_rating = ((mysql_result($run_all_players_query, $i, 'elo_rating') + mysql_result($run_all_players_query, $i, 'games_played')) * mysql_result($run_all_players_query, $i, 'shooting_percentage'));

				mysql_query('UPDATE `'.$current_season.'` SET `compound_rating`="'.$compound_rating.'" WHERE `id_registrations`="'.$user_id.'" AND `name`="'.mysql_result($run_all_players_query, $i, 'name').'"');

				$i++;
			}

			$all_players_query = 'SELECT `name`, `compound_rating`, `rank` FROM `'.$current_season.'` WHERE `id_registrations`="'.$user_id.'" ORDER BY `compound_rating` DESC';

			$run_all_players_query = mysql_query($all_players_query);

			$i = 0;

			while ($i < mysql_num_rows($run_all_players_query))
			{
				$i++;

				mysql_query('UPDATE `'.$current_season.'` SET `rank`="'.$i.'" WHERE `id_registrations`="'.$user_id.'" AND `name`="'.mysql_result($run_all_players_query, ($i - 1), 'name').'"');
			}
		}
	}
}

?>