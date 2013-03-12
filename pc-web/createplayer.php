<?php

$databaseHost = "68.178.139.40";
$databaseName = "pchitmanhart33";
$databaseUsername = "pchitmanhart33";
$databasePassword = "Cooors33!";
$id_registrations = 0;

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
		$numRows = mysql_num_rows($runQueryName);
		
		if ($numRows == 0)
		{
			mysql_query("INSERT INTO `players`(`id_registrations`,`name`) VALUES ('".$id_registrations."','".$_POST['name']."')");
			
			$rankQueryName = "SELECT `name`, `wins`, `losses`, `ot_losses`, `shots`, `hits`, `elo_rating` FROM `players` WHERE `id_registrations`='".$id_registrations."' ORDER BY `elo_rating` DESC";

	        if ($rankQuery = mysql_query($rankQueryName))
	        {
		        $rankQueryNumRows = mysql_num_rows($rankQuery);
		
		        $i = 0;
		
		        while ($i < $rankQueryNumRows)
                {
                    $player = mysql_result($rankQuery, $i, "name");
                    $rating = mysql_result($rankQuery, $i, "elo_rating") + mysql_result($rankQuery, $i, "wins") + mysql_result($rankQuery, $i, "losses") + mysql_result($rankQuery, $i, "ot_losses");
                    
                    if (mysql_result($rankQuery, $i, "shots") != 0)
                    {
                        $rating = $rating * mysql_result($rankQuery, $i, "hits") / mysql_result($rankQuery, $i, "shots");
                    }
            
                    mysql_query("UPDATE `players` SET `compound_rating`='".$rating."' WHERE `name`='".$player."' AND `id_registrations`='".$id_registrations."'");
            
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