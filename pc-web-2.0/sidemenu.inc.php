<?php $count = 0; ?>

<div id="side_menu_container">
	<ul id="side_menu">
		<a href="index.php">
			<li class="<?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Home</li>
		</a>
		<a href="help.php">
			<li class="side_menu_parent <?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Help
				<ul>
					<a href="new_features.php">
						<li class="side_menu_first_child <?php $value = $count % 2; echo 'row'.$value; $count++; ?>">What's New</li>
					</a>
					<a href="stat_list.php">
						<li class="side_menu_first_child <?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Stat List</li>
					</a>
					<a href="achievement_list.php">
						<li class="<?php $value = $count % 2; echo 'row'.$value; ?>">Achievement List</li>
					</a>
				</ul>
			</li>
		</a>
		<a href="playerstats.php">
			<li class="side_menu_parent <?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Player Stats
				<ul>
					<a href="overall_stats.php">
						<li class="side_menu_first_child <?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Overall</li>
					</a>
					<a href="seasonal_stats.php">
						<li class="<?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Seasonal</li>
					</a>
				</ul>
			</li>
		</a>
		<a href="choose_profile_type.php">
			<li class="<?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Player Profiles
				<ul>
					<a href="profiles.php">
						<li class="side_menu_first_child <?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Overall</li>
					</a>
					<a href="seasonal_profiles.php">
						<li class="<?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Seasonal</li>
					</a>
				</ul>
			</li>
		</a>
		<a href="team_stats.php">
			<li class="<?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Team Stats</li>
		</a>
		<a href="team_profile.php">
			<li class="<?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Team Profiles</li>
		</a>
		<a href="results.php">
			<li class="<?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Game Results</li>
		</a>
		<a href="head_to_head.php">
			<li class="<?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Head-to-Head Results</li>
		</a>
		<a href="achievements.php">
			<li class="<?php $value = $count % 2; echo 'row'.$value; $count++; ?>">Achievements</li>
		</a>
	</ul>
	<div class="get_app"><a href="https://play.google.com/store/apps/details?id=com.pongchamp.pc&feature=search_result#?t=W251bGwsMSwyLDEsImNvbS5wb25nY2hhbXAucGMiXQ.."><img src="imgs/pongchamp_icon_large.png" alt="PC" width="80" height="100" /></a></div>
	<a href="https://play.google.com/store/apps/details?id=com.pongchamp.pc&feature=search_result#?t=W251bGwsMSwyLDEsImNvbS5wb25nY2hhbXAucGMiXQ.." class="get_app_prompt"><p>Download Now</p></a>
	<p class="get_app_desc">Get the Pong Champ mobile app for free. Available exclusively for Android devices.</p>
</div>