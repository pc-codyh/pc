<?php

$databaseHost = "68.178.139.40";
$databaseName = "pchitmanhart33";
$databaseUsername = "pchitmanhart33";
$databasePassword = "Cooors33!";
$id_registrations = 0;

$ID_WINS = '0';
$ID_LOSSES = '1';
$ID_OT_LOSSES = '2';
$ID_CUP_DIF = '3';
$ID_WIN_STREAK = '4';
$ID_LOSS_STREAK = '5';
$ID_SHOTS = '6';
$ID_HITS = '7';
$ID_HIT_STREAK = '8';
$ID_MISS_STREAK = '9';
$ID_BOUNCES = '10';
$ID_GANG_BANGS = '11';
$ID_ERRORS = '12';
$ID_REDEMP_SHOTS = '13';
$ID_REDEMP_HITS = '14';
$ID_REDEMP_ATMPS = '15';
$ID_REDEMP_SUCCS = '16';
$ID_ELO_RATING = '17';
$ID_S10 = '18';
$ID_H10 = '19';
$ID_S9 = '20';
$ID_H9 = '21';
$ID_S8 = '22';
$ID_H8 = '23';
$ID_S7 = '24';
$ID_H7 = '25';
$ID_S6 = '26';
$ID_H6 = '27';
$ID_S5 = '28';
$ID_H5 = '29';
$ID_S4 = '30';
$ID_H4 = '31';
$ID_S3 = '32';
$ID_H3 = '33';
$ID_S2 = '34';
$ID_H2 = '35';
$ID_S1 = '36';
$ID_H1 = '37';
$ID_CUR_WIN_STREAK = '38';
$ID_CUR_LOSS_STREAK = '39';
$ID_HEATING_UP = '40';
$ID_ON_FIRE = '41';

mysql_connect($databaseHost, $databaseUsername, $databasePassword) or die(mysql_error());
mysql_select_db($databaseName) or die(mysql_error());

$queryID = "SELECT `id` FROM `registrations` WHERE `username`='".$_POST['username']."'";

if ($runQueryID = mysql_query($queryID))
{
	$row = mysql_fetch_assoc($runQueryID);

	$id_registrations = $row['id'];

	$queryName = "SELECT * FROM `players` WHERE `id_registrations`='".$id_registrations."' AND `name`='".$_POST['name']."'";

	if ($runQueryName = mysql_query($queryName))
	{
	    $otGamesPlayed = mysql_result($runQueryName, 0, "ot_games_played");

        if ($_POST['ot_count'] > 0)
        {
            $otGamesPlayed++;
        }
        
	    $statsQuery = "UPDATE `players` SET `wins`='".$_POST[$ID_WINS]."',`losses`='".$_POST[$ID_LOSSES]."',`ot_losses`='".$_POST[$ID_OT_LOSSES]."',`cup_dif`='".$_POST[$ID_CUP_DIF]."',`win_streak`='".$_POST[$ID_WIN_STREAK]."',`loss_streak`='".$_POST[$ID_LOSS_STREAK]."',`shots`='".$_POST[$ID_SHOTS]."',`hits`='".$_POST[$ID_HITS]."',`hit_streak`='".$_POST[$ID_HIT_STREAK]."',`miss_streak`='".$_POST[$ID_MISS_STREAK]."',`bounces`='".$_POST[$ID_BOUNCES]."',`gang_bangs`='".$_POST[$ID_GANG_BANGS]."',`errors`='".$_POST[$ID_ERRORS]."',`redemp_shots`='".$_POST[$ID_REDEMP_SHOTS]."',`redemp_hits`='".$_POST[$ID_REDEMP_HITS]."',`redemp_atmps`='".$_POST[$ID_REDEMP_ATMPS]."',`redemp_succs`='".$_POST[$ID_REDEMP_SUCCS]."',`elo_rating`='".$_POST[$ID_ELO_RATING]."',`s10`='".$_POST[$ID_S10]."',`h10`='".$_POST[$ID_H10]."',`s9`='".$_POST[$ID_S9]."',`h9`='".$_POST[$ID_H9]."',`s8`='".$_POST[$ID_S8]."',`h8`='".$_POST[$ID_H8]."',`s7`='".$_POST[$ID_S7]."',`h7`='".$_POST[$ID_H7]."',`s6`='".$_POST[$ID_S6]."',`h6`='".$_POST[$ID_H6]."',`s5`='".$_POST[$ID_S5]."',`h5`='".$_POST[$ID_H5]."',`s4`='".$_POST[$ID_S4]."',`h4`='".$_POST[$ID_H4]."',`s3`='".$_POST[$ID_S3]."',`h3`='".$_POST[$ID_H3]."',`s2`='".$_POST[$ID_S2]."',`h2`='".$_POST[$ID_H2]."',`s1`='".$_POST[$ID_S1]."',`h1`='".$_POST[$ID_H1]."',`cur_win_streak`='".$_POST[$ID_CUR_WIN_STREAK]."',`cur_loss_streak`='".$_POST[$ID_CUR_LOSS_STREAK]."',`heating_up`='".$_POST[$ID_HEATING_UP]."',`on_fire`='".$_POST[$ID_ON_FIRE]."', `ot_games_played`='".$otGamesPlayed."' WHERE `name`='".$_POST['name']."' AND `id_registrations`='".$id_registrations."'";     
        
        if ($runStatsQuery = mysql_query($statsQuery))
        {
            $rankQueryName = "SELECT * FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `elo_rating` DESC";

	        if ($rankQuery = mysql_query($rankQueryName))
	        {
		        $rankQueryNumRows = mysql_num_rows($rankQuery);

		        $i = 0;

		        while ($i < $rankQueryNumRows)
                {
                    $player = mysql_result($rankQuery, $i, "name");
                    $rating = mysql_result($rankQuery, $i, "elo_rating") + mysql_result($rankQuery, $i, "wins") + mysql_result($rankQuery, $i, "losses") + mysql_result($rankQuery, $i, "ot_losses");
                    $gamesPlayed = mysql_result($rankQuery, $i, "wins") + mysql_result($rankQuery, $i, "losses") + mysql_result($rankQuery, $i, "ot_losses");
                    $shootingPercentage = 0;
                    $redempShootingPercentage = 0;
                    $p10 = 0;
                    $p9 = 0;
                    $p8 = 0;
                    $p7 = 0;
                    $p6 = 0;
                    $p5 = 0;
                    $p4 = 0;
                    $p3 = 0;
                    $p2 = 0;
                    $p1 = 0;

                    if (mysql_result($rankQuery, $i, "shots") != 0)
                    {
                        $shootingPercentage = mysql_result($rankQuery, $i, "hits") / mysql_result($rankQuery, $i, "shots");
                        $rating = $rating * mysql_result($rankQuery, $i, "hits") / mysql_result($rankQuery, $i, "shots");
                    }
                    
                    if (mysql_result($rankQuery, $i, "redemp_shots") != 0)
                    {
                        $redempShootingPercentage = mysql_result($rankQuery, $i, "redemp_hits") / mysql_result($rankQuery, $i, "redemp_shots");
                    }
                    
                    if (mysql_result($rankQuery, $i, "s10") != 0)
                    {
                        $p10 = mysql_result($rankQuery, $i, "h10") / mysql_result($rankQuery, $i, "s10");
                    }
                    
                    if (mysql_result($rankQuery, $i, "s9") != 0)
                    {
                        $p9 = mysql_result($rankQuery, $i, "h9") / mysql_result($rankQuery, $i, "s9");
                    }
                    
                    if (mysql_result($rankQuery, $i, "s8") != 0)
                    {
                        $p8 = mysql_result($rankQuery, $i, "h8") / mysql_result($rankQuery, $i, "s8");
                    }
                    
                    if (mysql_result($rankQuery, $i, "s7") != 0)
                    {
                        $p7 = mysql_result($rankQuery, $i, "h7") / mysql_result($rankQuery, $i, "s7");
                    }
                    
                    if (mysql_result($rankQuery, $i, "s6") != 0)
                    {
                        $p6 = mysql_result($rankQuery, $i, "h6") / mysql_result($rankQuery, $i, "s6");
                    }
                    
                    if (mysql_result($rankQuery, $i, "s5") != 0)
                    {
                        $p5 = mysql_result($rankQuery, $i, "h5") / mysql_result($rankQuery, $i, "s5");
                    }
                    
                    if (mysql_result($rankQuery, $i, "s4") != 0)
                    {
                        $p4 = mysql_result($rankQuery, $i, "h4") / mysql_result($rankQuery, $i, "s4");
                    }
                    
                    if (mysql_result($rankQuery, $i, "s3") != 0)
                    {
                        $p3 = mysql_result($rankQuery, $i, "h3") / mysql_result($rankQuery, $i, "s3");
                    }
                    
                    if (mysql_result($rankQuery, $i, "s2") != 0)
                    {
                        $p2 = mysql_result($rankQuery, $i, "h2") / mysql_result($rankQuery, $i, "s2");
                    }
                    
                    if (mysql_result($rankQuery, $i, "s1") != 0)
                    {
                        $p1 = mysql_result($rankQuery, $i, "h1") / mysql_result($rankQuery, $i, "s1");
                    }

                    mysql_query("UPDATE `players` SET `compound_rating`='".$rating."', `games_played`='".$gamesPlayed."', `shooting_percentage`='".$shootingPercentage."', `redemp_shotperc`='".$redempShootingPercentage."', `p10`='".$p10."', `p9`='".$p9."', `p8`='".$p8."', `p7`='".$p7."', `p6`='".$p6."', `p5`='".$p5."', `p4`='".$p4."', `p3`='".$p3."', `p2`='".$p2."', `p1`='".$p1."' WHERE `name`='".$player."' AND `id_registrations`='".$id_registrations."'");

                    $i++;
                }
	        }

	        $ratingQueryName = "SELECT `name`, `compound_rating` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `compound_rating` DESC";

	        if ($ratingQuery = mysql_query($ratingQueryName))
	        {
                $ratingQueryNumRows = mysql_num_rows($ratingQuery);

                $j = 0;
                $rank = 1;

                while ($j < $ratingQueryNumRows)
                {
                    $player = mysql_result($ratingQuery, $j, "name");

                    mysql_query("UPDATE `players` SET `rank`='".$rank."' WHERE `name`='".$player."' AND `id_registrations`='".$id_registrations."'");

                    $j++;
                    $rank++;
                }
            }

            $achievementQueryName = "SELECT `a_shs`, `a_mj`, `a_cibav`, `a_bank`, `a_ck`, `a_hc`, `a_cwtpd`, `a_ps`, `a_sw`, `a_per`, `a_dbno`, `a_bb`, `a_bc`, `a_mar`, `a_fdm`, `a_skunk` FROM `players` WHERE `id_registrations`='".$id_registrations."' AND `name`='".$_POST['name']."'";

            if ($achievementQuery = mysql_query($achievementQueryName))
            {
                $a_shs = mysql_result($achievementQuery, 0, "a_shs") + $_POST['ach_0'];
                $a_mj = mysql_result($achievementQuery, 0, "a_mj") + $_POST['ach_1'];
                $a_cibav = mysql_result($achievementQuery, 0, "a_cibav") + $_POST['ach_2'];
                $a_bank = mysql_result($achievementQuery, 0, "a_bank") + $_POST['ach_3'];
                $a_ck = mysql_result($achievementQuery, 0, "a_ck") + $_POST['ach_4'];
                $a_hc = mysql_result($achievementQuery, 0, "a_hc") + $_POST['ach_5'];
                $a_cwtpd = mysql_result($achievementQuery, 0, "a_cwtpd") + $_POST['ach_6'];
                $a_ps = mysql_result($achievementQuery, 0, "a_ps") + $_POST['ach_7'];
                $a_sw = mysql_result($achievementQuery, 0, "a_sw") + $_POST['ach_8'];
                $a_per = mysql_result($achievementQuery, 0, "a_per") + $_POST['ach_9'];
                $a_dbno = mysql_result($achievementQuery, 0, "a_dbno") + $_POST['ach_10'];
                $a_bb = mysql_result($achievementQuery, 0, "a_bb") + $_POST['ach_12'];
                $a_bc = mysql_result($achievementQuery, 0, "a_bc") + $_POST['ach_13'];
                $a_mar = mysql_result($achievementQuery, 0, "a_mar") + $_POST['ach_14'];
                $a_fdm = mysql_result($achievementQuery, 0, "a_fdm") + $_POST['ach_15'];
                $a_skunk = mysql_result($achievementQuery, 0, "a_skunk") + $_POST['ach_16'];

                mysql_query("UPDATE `players` SET `a_shs`='".$a_shs."', `a_mj`='".$a_mj."', `a_cibav`='".$a_cibav."', `a_bank`='".$a_bank."', `a_ck`='".$a_ck."', `a_hc`='".$a_hc."', `a_cwtpd`='".$a_cwtpd."', `a_ps`='".$a_ps."', `a_sw`='".$a_sw."', `a_per`='".$a_per."', `a_dbno`='".$a_dbno."', `a_bb`='".$a_bb."', `a_bc`='".$a_bc."', `a_mar`='".$a_mar."', `a_fdm`='".$a_fdm."', `a_skunk`='".$a_skunk."' WHERE `name`='".$_POST['name']."' AND `id_registrations`='".$id_registrations."'");
            }
        
            echo 1;
        }
        else
        {
            echo 0;
        }
	}
}

mysql_close();

?>