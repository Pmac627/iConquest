<?php
/** warfare_ground.php **/

require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/days_since_functions.php');
include ('functions/warfare_bonus_functions.php');
include ('functions/warfare_att_def_stats_functions.php');
include ('functions/warfare_units_bonus_functions.php');
include ('functions/warfare_dice_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Launch Ground Attack';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$pass = $_COOKIE['Key_i_Conquest'];
	$check = mysql_query("SELECT ID, password, mod_admin FROM users WHERE username = '$username'")or die(mysql_error());
	while($info = mysql_fetch_array( $check ))
	{
		$ID = $info['ID'];
		$info_pass = $info['password'];
		$mod_admin = $info['mod_admin'];
	}

	// If the cookie has the wrong password, they are taken to the expired session login page
	if($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		// Otherwise they are shown the warfare accept peace page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		$URL_ID_war = $_GET['ID_war'];
		$URL_ID_war = strip_tags($URL_ID_war);
		$URL_war = mysql_real_escape_string($URL_ID_war);

		if(isset($_POST['URL_war']))
		{
			$URL_war = $_POST['URL_war'];
		}

		$info = mysql_query("SELECT ID_defend FROM warfare WHERE ID_war = '$URL_war'") or die(mysql_error());
		while($warz = mysql_fetch_array( $info ))
		{
			$count_war++;
		}

		if($count_war != 1)
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=79");
		}

		$info2 = mysql_query("SELECT war_date, war_stat, last_combat FROM warfare WHERE ID_war = '$URL_war'") or die(mysql_error());
		while($war = mysql_fetch_array( $info2 ))
		{
			$war_date = stripslashes($war['war_date']);
			$war_stat = stripslashes($war['war_stat']);
			$last_combat = stripslashes($war['last_combat']);
		}

		if($war_stat == 3 || $war_stat == 5)
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=80");
		}

		$days_since_war = days_since_calculation($war_date);
		$days_since_war = number_format($days_since_war, 0);

		if($last_combat != 0)
		{
			$days_since_combat = days_since_calculation($last_combat);
			$days_since_combat = number_format($days_since_combat, 0);
		}
		else
		{
			$days_since_combat = 0;
		}

		if($days_since_war > 7)
		{
			$war_stat = 3;
			// Update to deleted
			$insert = "UPDATE warfare SET war_stat='" . $war_stat . "' WHERE ID_war='" . $URL_war . "'";
			$add_member = mysql_query($insert);

			// Redirect them to the error page
			header("Location: error_page.php?error=81");
		}
		elseif($days_since_war < 7 && $days_since_combat == 2 && $war_stat != 4)
		{
			$war_stat = 4;
			// Update to defacto ceasefire
			$insert = "UPDATE warfare SET war_stat='" . $war_stat . "' WHERE ID_war='" . $URL_war . "'";
			$add_member = mysql_query($insert);

			// Redirect them to the error page
			header("Location: error_page.php?error=82");
		}
		elseif($days_since_war < 7 && $days_since_combat >= 3)
		{
			$war_stat = 5;
			// Update to defacto peace
			$insert = "UPDATE warfare SET war_stat='" . $war_stat . "' WHERE ID_war='" . $URL_war . "'";
			$add_member = mysql_query($insert);

			// Redirect them to the error page
			header("Location: error_page.php?error=83");
		}
		else
		{
			// Grab the aggressor's info from the deployed table
			$info3 = mysql_query("SELECT inf_1_deploy, inf_2_deploy, inf_3_deploy, inf_1_used, inf_2_used, inf_3_used, inf_1_today, inf_2_today, inf_3_today, armor_1_deploy, armor_2_deploy, armor_3_deploy, armor_4_deploy, armor_5_deploy, armor_1_used, armor_2_used, armor_3_used, armor_4_used, armor_5_used, armor_1_today, armor_2_today, armor_3_today, armor_4_today, armor_5_today, deployed_today, last_deploy FROM deployed WHERE ID = '$ID'") or die(mysql_error());
			while($mil = mysql_fetch_array( $info3 ))
			{
				$inf1 = stripslashes($mil['inf_1_deploy']);
				$inf2 = stripslashes($mil['inf_2_deploy']);
				$inf3 = stripslashes($mil['inf_3_deploy']);
				$inf_1_used = stripslashes($mil['inf_1_used']);
				$inf_2_used = stripslashes($mil['inf_2_used']);
				$inf_3_used = stripslashes($mil['inf_3_used']);
				$inf_1_today = stripslashes($mil['inf_1_today']);
				$inf_2_today = stripslashes($mil['inf_2_today']);
				$inf_3_today = stripslashes($mil['inf_3_today']);
				$armor1 = stripslashes($mil['armor_1_deploy']);
				$armor2 = stripslashes($mil['armor_2_deploy']);
				$armor3 = stripslashes($mil['armor_3_deploy']);
				$armor4 = stripslashes($mil['armor_4_deploy']);
				$armor5 = stripslashes($mil['armor_5_deploy']);
				$armor_1_used = stripslashes($mil['armor_1_used']);
				$armor_2_used = stripslashes($mil['armor_2_used']);
				$armor_3_used = stripslashes($mil['armor_3_used']);
				$armor_4_used = stripslashes($mil['armor_4_used']);
				$armor_5_used = stripslashes($mil['armor_5_used']);
				$armor_1_today = stripslashes($mil['armor_1_today']);
				$armor_2_today = stripslashes($mil['armor_2_today']);
				$armor_3_today = stripslashes($mil['armor_3_today']);
				$armor_4_today = stripslashes($mil['armor_4_today']);
				$armor_5_today = stripslashes($mil['armor_5_today']);
				$deployed_today = stripslashes($mil['deployed_today']);
				$last_deploy = stripslashes($mil['last_deploy']);
			}

			$last_deploy_days = days_since_calculation($last_deploy);
			$last_deploy_days = number_format($last_deploy_days, 0);

			##############################################
			// FOR REFERENCE:
			// DEPLOYED: $x_#_deploy (ex: $inf1) is the total of living units in the region of combat
			// TODAY: $x_#_today (ex: $inf_1_today) is the total units committed to a battle; losses do not affect
			// USED: $x_#_used (ex: $inf_1_used) is the total of living units that survived all the battles
			##############################################

			$total_inf_used = ($inf_1_used + $inf_2_used + $inf_3_used);
			$total_armor_used = ($armor_1_used + $armor_2_used + $armor_3_used + $armor_4_used + $armor_5_used);

			$total_inf = ($inf1 + $inf2 + $inf3);

			if($total_inf_used > 0 && $days_since_combat > 0)
			{
				// If the ruler hasn't used infantry today, reset the USED units
				$new_inf_1_used = 0;
				$new_inf_2_used = 0;
				$new_inf_3_used = 0;
				$new_armor_1_used = 0;
				$new_armor_2_used = 0;
				$new_armor_3_used = 0;
				$new_armor_4_used = 0;
				$new_armor_5_used = 0;

				// Update to unused
				$insert = "UPDATE deployed SET inf_1_used='" . $new_inf_1_used . "', inf_2_used='" . $new_inf_2_used . "', inf_3_used='" . $new_inf_3_used . "', armor_1_used='" . $new_armor_1_used . "', armor_2_used='" . $new_armor_2_used . "', armor_3_used='" . $new_armor_3_used . "', armor_4_used='" . $new_armor_4_used . "', armor_5_used='" . $new_armor_5_used . "' WHERE ID='" . $ID . "'";
				$add_member = mysql_query($insert);

				// Then redirect them to the ground warfare page
				header("Location: warfare_ground.php");
			}
			elseif($inf1 <= $inf_1_used && $inf2 <= $inf_2_used && $inf3 <= $inf_3_used && $last_deploy_days > 0)
			{
				$deployed_fubar = "Perhaps you should deploy some units first.";
			}
			elseif($inf_1_today <= $inf_1_used && $inf_2_today <= $inf_2_used && $inf_3_today <= $inf_3_used)
			{
				$deployed_fubar = "You cannot attack anymore.  Every unit has been deployed on operations.<br />Try again tomorrow.";
			}
			elseif($total_inf == 0 && $deployed_today == 0)
			{
				$deployed_fubar = "You do not have any infantry deployed!<br />Deploy some!";
			}
			else
			{
				$deployed_fubar = "<input type='submit' name='attack_regular' value='Regular Attack' />";
			}

			// If regular attack form is submitted
			if (isset($_POST['attack_regular']))
			{
				$_POST['URL_war'] = strip_tags($_POST['URL_war']);
				$_POST['ID_me'] = strip_tags($_POST['ID_me']);
				$_POST['inf1'] = strip_tags($_POST['inf1']);
				$_POST['inf2'] = strip_tags($_POST['inf2']);
				$_POST['inf3'] = strip_tags($_POST['inf3']);
				$_POST['armor1'] = strip_tags($_POST['armor1']);
				$_POST['armor2'] = strip_tags($_POST['armor2']);
				$_POST['armor3'] = strip_tags($_POST['armor3']);
				$_POST['armor4'] = strip_tags($_POST['armor4']);
				$_POST['armor5'] = strip_tags($_POST['armor5']);

				if(isset($_POST['URL_war'], $_POST['ID_me'], $_POST['inf1'], $_POST['inf2'], $_POST['inf3'], $_POST['armor1'], $_POST['armor2'], $_POST['armor3'], $_POST['armor4'], $_POST['armor5']))
				{
					if(sanity_check($_POST['URL_war'], 'numeric', 7) != FALSE && sanity_check($_POST['ID_me'], 'numeric', 6) != FALSE && sanity_check($_POST['inf1'], 'numeric', 4) != FALSE && sanity_check($_POST['inf2'], 'numeric', 4) != FALSE && sanity_check($_POST['inf3'], 'numeric', 4) != FALSE && sanity_check($_POST['armor1'], 'numeric', 4) != FALSE && sanity_check($_POST['armor2'], 'numeric', 4) != FALSE && sanity_check($_POST['armor3'], 'numeric', 4) != FALSE && sanity_check($_POST['armor4'], 'numeric', 4) != FALSE && sanity_check($_POST['armor5'], 'numeric', 4) != FALSE)
					{
						$ID_war = mysql_real_escape_string($_POST['URL_war']);
						$ID_me = mysql_real_escape_string($_POST['ID_me']);
						$attacking_inf1 = mysql_real_escape_string($_POST['inf1']);
						$attacking_inf2 = mysql_real_escape_string($_POST['inf2']);
						$attacking_inf3 = mysql_real_escape_string($_POST['inf3']);
						$attacking_armor1 = mysql_real_escape_string($_POST['armor1']);
						$attacking_armor2 = mysql_real_escape_string($_POST['armor2']);
						$attacking_armor3 = mysql_real_escape_string($_POST['armor3']);
						$attacking_armor4 = mysql_real_escape_string($_POST['armor4']);
						$attacking_armor5 = mysql_real_escape_string($_POST['armor5']);
					}
					else
					{
						// Redirect them to the error page
						header("Location: error_page.php?error=84");
					}
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=1");
				}

				// Start with grabbing all the war info
				$war_info = mysql_query("SELECT ID_attack, ID_defend, war_stat, war_date, att_inf_cas, def_inf_cas, att_armor_cas, def_armor_cas, open_shot FROM warfare WHERE ID_war = '$ID_war'") or die(mysql_error(war_list));
				while($war_list = mysql_fetch_array( $war_info ))
				{
					$ID_aggressor = stripslashes($war_list['ID_attack']);
					$ID_defender = stripslashes($war_list['ID_defend']);
					$war_stat = stripslashes($war_list['war_stat']);
					$war_date = stripslashes($war_list['war_date']);
					$att_inf_cas = stripslashes($war_list['att_inf_cas']);
					$def_inf_cas = stripslashes($war_list['def_inf_cas']);
					$att_armor_cas = stripslashes($war_list['att_armor_cas']);
					$def_armor_cas = stripslashes($war_list['def_armor_cas']);
					$open_shot = stripslashes($war_list['open_shot']);
				}

				// Makes sure the passed ID is involved.  A fake ID passed will also trigger this message.
				// If I am the attacker, I get the aggressive mark, otherwise they do.
				if($ID_aggressor == $ID_me)
				{
					$aggres_me = 0.995;
					$aggres_them = 1;
					$ID_attack = $ID_me;
					$ID_defend = $ID_defender;
				}
				elseif($ID_defender == $ID_me)
				{
					$aggres_me = 1;
					$aggres_them = 0.995;
					$ID_attack = $ID_me;
					$ID_defend = $ID_aggressor;
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=85");
				}

				// Grab the defender's info from the military table
				$att_info4 = mysql_query("SELECT inf_loss, armor_loss FROM military WHERE ID = '$ID_attack'") or die(mysql_error(def_info3));
				while($att_military = mysql_fetch_array( $att_info4 ))
				{
					$att_inf_loss = stripslashes($att_military['inf_loss']);
					$att_armor_loss = stripslashes($att_military['armor_loss']);
				}

				// Grab the attacker's info from the nations table
				$att_info1 = mysql_query("SELECT nation, zone, creed, ethnicity FROM nations WHERE ID = '$ID_attack'") or die(mysql_error(att_info1));
				while($att_nations = mysql_fetch_array( $att_info1 ))
				{
					$att_nation = stripslashes($att_nations['nation']);
					$att_zone = stripslashes($att_nation['zone']);
					$att_creed = stripslashes($att_nation['creed']);
					$att_ethnicity = stripslashes($att_nation['ethnicity']);
				}
				// Grab the attacker's info from the nation_variables table
				$att_info2 = mysql_query("SELECT opinion, infra, land, tech, capital FROM nation_variables WHERE ID = '$ID_attack'") or die(mysql_error(att_info2));
				while($att_nation_var = mysql_fetch_array( $att_info2 ))
				{
					$att_opinion = stripslashes($att_nation_var['opinion']);
					$att_infra = stripslashes($att_nation_var['infra']);
					$att_land = stripslashes($att_nation_var['land']);
					$att_tech = stripslashes($att_nation_var['tech']);
					$att_capital = stripslashes($att_nation_var['capital']);
				}
				// Grab the attacker's info from the deployed table
				$att_info3 = mysql_query("SELECT inf_1_deploy, inf_2_deploy, inf_3_deploy, inf_1_used, inf_2_used, inf_3_used, armor_1_deploy, armor_2_deploy, armor_3_deploy, armor_4_deploy, armor_5_deploy, armor_1_used, armor_2_used, armor_3_used, armor_4_used, armor_5_used, deployed_today FROM deployed WHERE ID = '$ID_attack'") or die(mysql_error(att_info3));
				while($att_deploy = mysql_fetch_array( $att_info3 ))
				{
					$att_inf1 = stripslashes($att_deploy['inf_1_deploy']);
					$att_inf2 = stripslashes($att_deploy['inf_2_deploy']);
					$att_inf3 = stripslashes($att_deploy['inf_3_deploy']);
					$att_inf_1_used = stripslashes($att_deploy['inf_1_used']);
					$att_inf_2_used = stripslashes($att_deploy['inf_2_used']);
					$att_inf_3_used = stripslashes($att_deploy['inf_3_used']);
					$att_armor1 = stripslashes($att_deploy['armor_1_deploy']);
					$att_armor2 = stripslashes($att_deploy['armor_2_deploy']);
					$att_armor3 = stripslashes($att_deploy['armor_3_deploy']);
					$att_armor4 = stripslashes($att_deploy['armor_4_deploy']);
					$att_armor5 = stripslashes($att_deploy['armor_5_deploy']);
					$att_armor_1_used = stripslashes($att_deploy['armor_1_used']);
					$att_armor_2_used = stripslashes($att_deploy['armor_2_used']);
					$att_armor_3_used = stripslashes($att_deploy['armor_3_used']);
					$att_armor_4_used = stripslashes($att_deploy['armor_4_used']);
					$att_armor_5_used = stripslashes($att_deploy['armor_5_used']);
					$att_deployed_today = stripslashes($att_deploy['deployed_today']);
				}

				// Grab the defender's info from the nations table
				$def_info1 = mysql_query("SELECT nation, zone, creed, ethnicity FROM nations WHERE ID = '$ID_defend'") or die(mysql_error(def_info1));
				while($def_nations = mysql_fetch_array( $def_info1 ))
				{
					$def_nation = stripslashes($def_nations['nation']);
					$def_zone = stripslashes($def_nation['zone']);
					$def_creed = stripslashes($def_nation['creed']);
					$def_ethnicity = stripslashes($def_nation['ethnicity']);
				}
				// Grab the defender's info from the nation_variables table
				$def_info2 = mysql_query("SELECT opinion, infra, land, tech, capital FROM nation_variables WHERE ID = '$ID_defend'") or die(mysql_error(def_info2));
				while($def_nation_var = mysql_fetch_array( $def_info2 ))
				{
					$def_opinion = stripslashes($def_nation_var['opinion']);
					$def_infra = stripslashes($def_nation_var['infra']);
					$def_land = stripslashes($def_nation_var['land']);
					$def_tech = stripslashes($def_nation_var['tech']);
					$def_capital = stripslashes($def_nation_var['capital']);
				}
				// Grab the defender's info from the military table
				$def_info3 = mysql_query("SELECT inf_1, inf_2, inf_3, armor_1, armor_2, armor_3, armor_4, armor_5, inf_loss, armor_loss FROM military WHERE ID = '$ID_defend'") or die(mysql_error(def_info3));
				while($def_military = mysql_fetch_array( $def_info3 ))
				{
					$def_inf1 = stripslashes($def_military['inf_1']);
					$def_inf2 = stripslashes($def_military['inf_2']);
					$def_inf3 = stripslashes($def_military['inf_3']);
					$def_armor1 = stripslashes($def_military['armor_1']);
					$def_armor2 = stripslashes($def_military['armor_2']);
					$def_armor3 = stripslashes($def_military['armor_3']);
					$def_armor4 = stripslashes($def_military['armor_4']);
					$def_armor5 = stripslashes($def_military['armor_5']);
					$def_inf_loss = stripslashes($def_military['inf_loss']);
					$def_armor_loss = stripslashes($def_military['armor_loss']);
				}

				$att_total_inf_used = ($att_inf_1_used + $att_inf_2_used + $att_inf_3_used);

				if($att_inf1 < $attacking_inf1 || $att_inf2 < $attacking_inf2 || $att_inf3 < $attacking_inf3 || $att_armor1 < $attacking_armor1 || $att_armor2 < $attacking_armor2 || $att_armor3 < $attacking_armor3 || $att_armor4 < $attacking_armor4 || $att_armor5 < $attacking_armor5)
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=86");
				}

				// $att_inf1 IS DEPLOYED; $att_inf_1_used IS USED; $attacking_inf1 IS ONES ATTACKING NOW!

				$usable_inf_1 = ($att_inf1 - $att_inf_1_used);
				$usable_inf_2 = ($att_inf2 - $att_inf_2_used);
				$usable_inf_3 = ($att_inf3 - $att_inf_3_used);
				$usable_armor_1 = ($att_armor1 - $att_armor_1_used);
				$usable_armor_2 = ($att_armor2 - $att_armor_2_used);
				$usable_armor_3 = ($att_armor3 - $att_armor_3_used);
				$usable_armor_4 = ($att_armor4 - $att_armor_4_used);
				$usable_armor_5 = ($att_armor5 - $att_armor_5_used);

				if($usable_inf_1 < $attacking_inf1 || $usable_inf_2 < $attacking_inf2 || $usable_inf_3 < $attacking_inf3 || $usable_armor_1 < $attacking_armor1 || $usable_armor_2 < $attacking_armor2 || $usable_armor_3 < $attacking_armor3 || $usable_armor_4 < $attacking_armor4 || $usable_armor_5 < $attacking_armor5)
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=87");
				}

				// Run all the impacting values through their respective calculators
				$att_unit_value = warfare_aggressor_units($attacking_inf1, $attacking_inf2, $attacking_inf3, $attacking_armor1, $attacking_armor2, $attacking_armor3, $attacking_armor4, $attacking_armor5);
				$def_unit_value = warfare_defender_units($def_inf1, $def_inf2, $def_inf3, $def_armor1, $def_armor2, $def_armor3, $def_armor4, $def_armor5);
				$opinion_bonus = warfare_opinion_bonus($att_opinion, $def_opinion);
				$zone_bonus = warfare_zone_bonus($att_zone, $def_zone);
				$creed_bonus = warfare_creed_bonus($att_creed, $def_creed);
				$ethnicity_bonus = warfare_ethnicity_bonus($att_ethnicity, $def_ethnicity);
				$surprise_bonus = warfare_surprise_bonus($open_shot);

				// Combine values and multiply them by the multipliers
				$aggressor_raw = warfare_aggressor_stats($att_unit_value, $opinion_bonus, $zone_bonus, $creed_bonus, $ethnicity_bonus, $surprise_bonus, $att_infra, $att_land, $att_tech, $att_capital, $aggres_me);
				$defender_raw = warfare_defender_stats($def_unit_value, $def_infra, $def_land, $def_tech, $def_capital, $aggres_them);

				// Roll the dice for the battle and determine the winner
				$aggressor_number = warfare_dice_ground($aggressor_raw);
				$defender_number = warfare_dice_ground($defender_raw);

				// Produce the outcome percentage and restrict it to xx.xx% format
				$aggressor_perc = chance_percent($aggressor_number, $defender_number);
				$aggressor_perc = number_format($aggressor_perc, 2);
				$defender_perc = chance_percent($defender_number, $aggressor_number);
				$defender_perc = number_format($defender_perc, 2);

				// Throw in the wildcard for victory.  54.01% or greater means that they will probably win... maybe.
				if($aggressor_perc > 54)
				{
					// If the aggressor has more than a 8% change of winning, the wildcard gets played for victory
					$luck = rand(1,10);
					$luckier = rand(1,50);
					if($luck == $luckier)
					{
						$who_wins = "defender";
					}
					else
					{
						$who_wins = "attacker";
					}
				}
				elseif($defender_perc > 54)
				{
					// If the defender has more than a 8% change of winning, the wildcard gets played for victory
					$luck = rand(1,10);
					$luckier = rand(1,50);
					if($luck == $luckier)
					{
						$who_wins = "attacker";
					}
					else
					{
						$who_wins = "defender";
					}
				}
				else
				{
					// If there is less than 8% seperating both sides, the wildcard gets played for a tie
					$luck_att = rand(1,10);
					$luckier_att = rand(1,50);
					$luck_def = rand(1,10);
					$luckier_def = rand(1,50);
					if($luck_att == $luckier_att)
					{
						$who_wins = "attacker";
					}
					elseif($luck_def == $luckier_def)
					{
						$who_wins = "defender";
					}
					else
					{
						$who_wins = "tie";
					}
				}

				// Now that we have a winner, lets see how many losses they get
				if($who_wins == "attacker")
				{
					$multiplier_att = (sqrt($aggressor_perc) * 2);
					$total_inf_att = ($attacking_inf1 + $attacking_inf2 + $attacking_inf3);
					$total_armor_att = ($attacking_armor1 + $attacking_armor2 + $attacking_armor3 + $attacking_armor4 + $attacking_armor5);
					$one_perc_inf_att = ($total_inf_att / 100);
					$one_perc_armor_att = ($total_armor_att / 100);
					$att_inf_losses = ($one_perc_inf_att * $multiplier_att);
					$att_armor_losses = ($one_perc_armor_att * $multiplier_att);

					$multiplier_def = (sqrt($defender_perc) * 4);
					$total_inf_def = ($def_inf1 + $def_inf2 + $def_inf3);
					$total_armor_def = ($def_armor1 + $def_armor2 + $def_armor3 + $def_armor4 + $def_armor5);
					$one_perc_inf_def = ($total_inf_def / 100);
					$one_perc_armor_def = ($total_armor_def / 100);
					$def_inf_losses = ($one_perc_inf_def * $multiplier_def);
					$def_armor_losses = ($one_perc_armor_def * $multiplier_def);
				}
				elseif($who_wins == "defender")
				{
					$multiplier_att = (sqrt($aggressor_perc) * 4);
					$total_inf_att = ($attacking_inf1 + $attacking_inf2 + $attacking_inf3);
					$total_armor_att = ($attacking_armor1 + $attacking_armor2 + $attacking_armor3 + $attacking_armor4 + $attacking_armor5);
					$one_perc_inf_att = ($total_inf_att / 100);
					$one_perc_armor_att = ($total_armor_att / 100);
					$att_inf_losses = ($one_perc_inf_att * $multiplier_att);
					$att_armor_losses = ($one_perc_armor_att * $multiplier_att);

					$multiplier_def = (sqrt($defender_perc) * 2);
					$total_inf_def = ($def_inf1 + $def_inf2 + $def_inf3);
					$total_armor_def = ($def_armor1 + $def_armor2 + $def_armor3 + $def_armor4 + $def_armor5);
					$one_perc_inf_def = ($total_inf_def / 100);
					$one_perc_armor_def = ($total_armor_def / 100);
					$def_inf_losses = ($one_perc_inf_def * $multiplier_def);
					$def_armor_losses = ($one_perc_armor_def * $multiplier_def);
				}
				else
				{
					$multiplier_att = (sqrt($aggressor_perc) * 2);
					$total_inf_att = ($attacking_inf1 + $attacking_inf2 + $attacking_inf3);
					$total_armor_att = ($attacking_armor1 + $attacking_armor2 + $attacking_armor3 + $attacking_armor4 + $attacking_armor5);
					$one_perc_inf_att = ($total_inf_att / 100);
					$one_perc_armor_att = ($total_armor_att / 100);
					$att_inf_losses = ($one_perc_inf_att * $multiplier_att);
					$att_armor_losses = ($one_perc_armor_att * $multiplier_att);

					$multiplier_def = (sqrt($defender_perc) * 2);
					$total_inf_def = ($def_inf1 + $def_inf2 + $def_inf3);
					$total_armor_def = ($def_armor1 + $def_armor2 + $def_armor3 + $def_armor4 + $def_armor5);
					$one_perc_inf_def = ($total_inf_def / 100);
					$one_perc_armor_def = ($total_armor_def / 100);
					$def_inf_losses = ($one_perc_inf_def * $multiplier_def);
					$def_armor_losses = ($one_perc_armor_def * $multiplier_def);
				}

				// Once a loss is decided, we determine how many defending infantry are lost.
				// After that, we determine how many of each infantry type are lost.
				if($def_inf1 !=0 && $def_inf2 != 0 && $def_inf3 != 0)
				{
					$defender_GCF = warfare_GRF_three($def_inf1, $def_inf2, $def_inf3);
					$total_loss_GCF = ($def_inf_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_inf1_loss = (($def_inf1 / $defender_GCF) * $total_loss_GCF);
					$def_inf2_loss = (($def_inf2 / $defender_GCF) * $total_loss_GCF);
					$def_inf3_loss = (($def_inf3 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_inf1 == 0 && $def_inf2 != 0 && $def_inf3 != 0)
				{
					$defender_GCF = warfare_GRF_two($def_inf2, $def_inf3);
					$total_loss_GCF = ($def_inf_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_inf1_loss = 0;
					$def_inf2_loss = (($def_inf2 / $defender_GCF) * $total_loss_GCF);
					$def_inf3_loss = (($def_inf3 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_inf1 != 0 && $def_inf2 == 0 && $def_inf3 != 0)
				{
					$defender_GCF = warfare_GRF_two($def_inf1, $def_inf3);
					$total_loss_GCF = ($def_inf_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_inf1_loss = (($def_inf1 / $defender_GCF) * $total_loss_GCF);
					$def_inf2_loss = 0;
					$def_inf3_loss = (($def_inf3 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_inf1 != 0 && $def_inf2 != 0 && $def_inf3 == 0)
				{
					$defender_GCF = warfare_GRF_two($def_inf1, $def_inf2);
					$total_loss_GCF = ($def_inf_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_inf1_loss = (($def_inf1 / $defender_GCF) * $total_loss_GCF);
					$def_inf2_loss = (($def_inf2 / $defender_GCF) * $total_loss_GCF);
					$def_inf3_loss = 0;
				}
				elseif($def_inf1 != 0 && $def_inf2 == 0 && $def_inf3 == 0)
				{
					$def_inf1_loss = $def_inf_losses;
					$def_inf2_loss = 0;
					$def_inf3_loss = 0;
				}
				elseif($def_inf1 == 0 && $def_inf2 != 0 && $def_inf3 == 0)
				{
					$def_inf1_loss = 0;
					$def_inf2_loss = $def_inf_losses;
					$def_inf3_loss = 0;
				}
				elseif($def_inf1 == 0 && $def_inf2 == 0 && $def_inf3 != 0)
				{
					$def_inf1_loss = 0;
					$def_inf2_loss = 0;
					$def_inf3_loss = $def_inf_losses;
				}
				else
				{
					$def_inf1_loss = 0;
					$def_inf2_loss = 0;
					$def_inf3_loss = 0;
				}

				// Once a loss is decided, we determine how many attacking infantry are lost.
				// After that, we determine how many of each infantry type are lost.
				if($attacking_inf1 !=0 && $attacking_inf2 != 0 && $attacking_inf3 != 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_inf1, $attacking_inf2, $attacking_inf3);
					$total_loss_GCF = ($att_inf_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_inf1_loss = (($attacking_inf1 / $attacker_GCF) * $total_loss_GCF);
					$att_inf2_loss = (($attacking_inf2 / $attacker_GCF) * $total_loss_GCF);
					$att_inf3_loss = (($attacking_inf3 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_inf1 == 0 && $attacking_inf2 != 0 && $attacking_inf3 != 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_inf2, $attacking_inf3);
					$total_loss_GCF = ($att_inf_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_inf1_loss = 0;
					$att_inf2_loss = (($attacking_inf2 / $attacker_GCF) * $total_loss_GCF);
					$att_inf3_loss = (($attacking_inf3 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_inf1 != 0 && $attacking_inf2 == 0 && $attacking_inf3 != 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_inf1, $attacking_inf3);
					$total_loss_GCF = ($att_inf_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_inf1_loss = (($attacking_inf1 / $attacker_GCF) * $total_loss_GCF);
					$att_inf2_loss = 0;
					$att_inf3_loss = (($attacking_inf3 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_inf1 != 0 && $attacking_inf2 != 0 && $attacking_inf3 == 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_inf1, $attacking_inf2);
					$total_loss_GCF = ($att_inf_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_inf1_loss = (($attacking_inf1 / $attacker_GCF) * $total_loss_GCF);
					$att_inf2_loss = (($attacking_inf2 / $attacker_GCF) * $total_loss_GCF);
					$att_inf3_loss = 0;
				}
				elseif($attacking_inf1 != 0 && $attacking_inf2 == 0 && $attacking_inf3 == 0)
				{
					$att_inf1_loss = $att_inf_losses;
					$att_inf2_loss = 0;
					$att_inf3_loss = 0;
				}
				elseif($attacking_inf1 == 0 && $attacking_inf2 != 0 && $attacking_inf3 == 0)
				{
					$att_inf1_loss = 0;
					$att_inf2_loss = $att_inf_losses;
					$att_inf3_loss = 0;
				}
				elseif($attacking_inf1 == 0 && $attacking_inf2 == 0 && $attacking_inf3 != 0)
				{
					$att_inf1_loss = 0;
					$att_inf2_loss = 0;
					$att_inf3_loss = $att_inf_losses;
				}
				else
				{
					$att_inf1_loss = 0;
					$att_inf2_loss = 0;
					$att_inf3_loss = 0;
				}

				// Once a loss is decided, we determine how many defending armor are lost.
				// After that, we determine how many of each armor type are lost.
				if($def_armor1 !=0 && $def_armor2 != 0 && $def_armor3 != 0 && $def_armor4 != 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_five($def_armor1, $def_armor2, $def_armor3, $def_armor4, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 ==0 && $def_armor2 != 0 && $def_armor3 != 0 && $def_armor4 != 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_four($def_armor2, $def_armor3, $def_armor4, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 !=0 && $def_armor2 == 0 && $def_armor3 != 0 && $def_armor4 != 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_four($def_armor1, $def_armor3, $def_armor4, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = 0;
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 !=0 && $def_armor2 != 0 && $def_armor3 == 0 && $def_armor4 != 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_four($def_armor1, $def_armor2, $def_armor4, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = 0;
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 !=0 && $def_armor2 != 0 && $def_armor3 != 0 && $def_armor4 == 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_four($def_armor1, $def_armor2, $def_armor3, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = 0;
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 !=0 && $def_armor2 != 0 && $def_armor3 != 0 && $def_armor4 != 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_four($def_armor1, $def_armor2, $def_armor3, $def_armor4);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 != 0 && $def_armor2 != 0 && $def_armor3 != 0 && $def_armor4 == 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_three($def_armor1, $def_armor2, $def_armor3);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = 0;
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 != 0 && $def_armor2 != 0 && $def_armor3 == 0 && $def_armor4 != 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_three($def_armor1, $def_armor2, $def_armor4);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = 0;
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 != 0 && $def_armor2 != 0 && $def_armor3 == 0 && $def_armor4 == 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_three($def_armor1, $def_armor2, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = 0;
					$def_armor4_loss = 0;
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 == 0 && $def_armor2 != 0 && $def_armor3 != 0 && $def_armor4 != 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_three($def_armor2, $def_armor3, $def_armor4);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 == 0 && $def_armor2 == 0 && $def_armor3 != 0 && $def_armor4 != 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_three($def_armor3, $def_armor4, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = 0;
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 != 0 && $def_armor2 == 0 && $def_armor3 == 0 && $def_armor4 != 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_three($def_armor1, $def_armor4, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = 0;
					$def_armor3_loss = 0;
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 == 0 && $def_armor2 != 0 && $def_armor3 == 0 && $def_armor4 != 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_three($def_armor2, $def_armor4, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = 0;
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 == 0 && $def_armor2 != 0 && $def_armor3 != 0 && $def_armor4 == 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_three($def_armor2, $def_armor3, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = 0;
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 != 0 && $def_armor2 == 0 && $def_armor3 != 0 && $def_armor4 != 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_three($def_armor1, $def_armor3, $def_armor4);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = 0;
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 != 0 && $def_armor2 == 0 && $def_armor3 != 0 && $def_armor4 == 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_three($def_armor1, $def_armor3, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = 0;
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = 0;
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 != 0 && $def_armor2 != 0 && $def_armor3 == 0 && $def_armor4 == 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_two($def_armor1, $def_armor2);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = 0;
					$def_armor4_loss = 0;
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 == 0 && $def_armor2 != 0 && $def_armor3 != 0 && $def_armor4 == 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_two($def_armor2, $def_armor3);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = 0;
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 == 0 && $def_armor2 == 0 && $def_armor3 != 0 && $def_armor4 != 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_two($def_armor3, $def_armor4);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = 0;
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 == 0 && $def_armor2 == 0 && $def_armor3 == 0 && $def_armor4 != 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_two($def_armor4, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = 0;
					$def_armor3_loss = 0;
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 != 0 && $def_armor2 == 0 && $def_armor3 != 0 && $def_armor4 == 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_two($def_armor1, $def_armor3);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = 0;
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = 0;
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 != 0 && $def_armor2 == 0 && $def_armor3 == 0 && $def_armor4 != 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_two($def_armor1, $def_armor4);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = 0;
					$def_armor3_loss = 0;
					$def_armor4_loss = (($def_armor4 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 != 0 && $def_armor2 == 0 && $def_armor3 == 0 && $def_armor4 == 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_two($def_armor1, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = (($def_armor1 / $defender_GCF) * $total_loss_GCF);
					$def_armor2_loss = 0;
					$def_armor3_loss = 0;
					$def_armor4_loss = 0;
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 == 0 && $def_armor2 == 0 && $def_armor3 != 0 && $def_armor4 == 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_two($def_armor3, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = 0;
					$def_armor3_loss = (($def_armor3 / $defender_GCF) * $total_loss_GCF);
					$def_armor4_loss = 0;
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 == 0 && $def_armor2 != 0 && $def_armor3 == 0 && $def_armor4 == 0 && $def_armor5 != 0)
				{
					$defender_GCF = warfare_GRF_two($def_armor2, $def_armor5);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = 0;
					$def_armor4_loss = 0;
					$def_armor5_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
				}
				elseif($def_armor1 == 0 && $def_armor2 != 0 && $def_armor3 == 0 && $def_armor4 != 0 && $def_armor5 == 0)
				{
					$defender_GCF = warfare_GRF_two($def_armor2, $def_armor4);
					$total_loss_GCF = ($def_armor_losses / $defender_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$def_armor1_loss = 0;
					$def_armor2_loss = (($def_armor2 / $defender_GCF) * $total_loss_GCF);
					$def_armor3_loss = 0;
					$def_armor4_loss = (($def_armor5 / $defender_GCF) * $total_loss_GCF);
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 != 0 && $def_armor2 == 0 && $def_armor3 == 0 && $def_armor4 == 0 && $def_armor5 == 0)
				{
					$def_armor1_loss = $def_armor_losses;
					$def_armor2_loss = 0;
					$def_armor3_loss = 0;
					$def_armor4_loss = 0;
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 == 0 && $def_armor2 != 0 && $def_armor3 == 0 && $def_armor4 == 0 && $def_armor5 == 0)
				{
					$def_armor1_loss = 0;
					$def_armor2_loss = $def_armor_losses;
					$def_armor3_loss = 0;
					$def_armor4_loss = 0;
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 == 0 && $def_armor2 == 0 && $def_armor3 != 0 && $def_armor4 == 0 && $def_armor5 == 0)
				{
					$def_armor1_loss = 0;
					$def_armor2_loss = 0;
					$def_armor3_loss = $def_armor_losses;
					$def_armor4_loss = 0;
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 == 0 && $def_armor2 == 0 && $def_armor3 == 0 && $def_armor4 != 0 && $def_armor5 == 0)
				{
					$def_armor1_loss = 0;
					$def_armor2_loss = 0;
					$def_armor3_loss = 0;
					$def_armor4_loss = $def_armor_losses;
					$def_armor5_loss = 0;
				}
				elseif($def_armor1 == 0 && $def_armor2 == 0 && $def_armor3 == 0 && $def_armor4 == 0 && $def_armor5 != 0)
				{
					$def_armor1_loss = 0;
					$def_armor2_loss = 0;
					$def_armor3_loss = 0;
					$def_armor4_loss = 0;
					$def_armor5_loss = $def_armor_losses;
				}
				else
				{
					$def_armor1_loss = 0;
					$def_armor2_loss = 0;
					$def_armor3_loss = 0;
					$def_armor4_loss = 0;
					$def_armor5_loss = 0;
				}

				// Once a loss is decided, we determine how many attacking armor are lost.
				// After that, we determine how many of each armor type are lost.
				if($attacking_armor1 !=0 && $attacking_armor2 != 0 && $attacking_armor3 != 0 && $attacking_armor4 != 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_five($attacking_armor1, $attacking_armor2, $attacking_armor3, $attacking_armor4, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 ==0 && $attacking_armor2 != 0 && $attacking_armor3 != 0 && $attacking_armor4 != 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_four($attacking_armor2, $attacking_armor3, $attacking_armor4, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 !=0 && $attacking_armor2 == 0 && $attacking_armor3 != 0 && $attacking_armor4 != 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_four($attacking_armor1, $attacking_armor3, $attacking_armor4, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = 0;
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 !=0 && $attacking_armor2 != 0 && $attacking_armor3 == 0 && $attacking_armor4 != 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_four($attacking_armor1, $attacking_armor2, $attacking_armor4, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = 0;
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 !=0 && $attacking_armor2 != 0 && $attacking_armor3 != 0 && $attacking_armor4 == 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_four($attacking_armor1, $attacking_armor2, $attacking_armor3, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = 0;
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 !=0 && $attacking_armor2 != 0 && $attacking_armor3 != 0 && $attacking_armor4 != 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_four($attacking_armor1, $attacking_armor2, $attacking_armor3, $attacking_armor4);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 != 0 && $attacking_armor3 != 0 && $attacking_armor4 == 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_armor1, $attacking_armor2, $attacking_armor3);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = 0;
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 != 0 && $attacking_armor3 == 0 && $attacking_armor4 != 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_armor1, $attacking_armor2, $attacking_armor4);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = 0;
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 != 0 && $attacking_armor3 == 0 && $attacking_armor4 == 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_armor1, $attacking_armor2, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = 0;
					$att_armor4_loss = 0;
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 != 0 && $attacking_armor3 != 0 && $attacking_armor4 != 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_armor2, $attacking_armor3, $attacking_armor4);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 == 0 && $attacking_armor3 != 0 && $attacking_armor4 != 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_armor3, $attacking_armor4, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = 0;
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 == 0 && $attacking_armor3 == 0 && $attacking_armor4 != 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_armor1, $attacking_armor4, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = 0;
					$att_armor3_loss = 0;
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 != 0 && $attacking_armor3 == 0 && $attacking_armor4 != 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_armor2, $attacking_armor4, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = 0;
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 != 0 && $attacking_armor3 != 0 && $attacking_armor4 == 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_armor2, $attacking_armor3, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = 0;
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 == 0 && $attacking_armor3 != 0 && $attacking_armor4 != 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_armor1, $attacking_armor3, $attacking_armor4);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = 0;
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 == 0 && $attacking_armor3 != 0 && $attacking_armor4 == 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_three($attacking_armor1, $attacking_armor3, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = 0;
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = 0;
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 != 0 && $attacking_armor3 == 0 && $attacking_armor4 == 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_armor1, $attacking_armor2);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = 0;
					$att_armor4_loss = 0;
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 != 0 && $attacking_armor3 != 0 && $attacking_armor4 == 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_armor2, $attacking_armor3);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = 0;
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 == 0 && $attacking_armor3 != 0 && $attacking_armor4 != 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_armor3, $attacking_armor4);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = 0;
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 == 0 && $attacking_armor3 == 0 && $attacking_armor4 != 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_armor4, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = 0;
					$att_armor3_loss = 0;
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 == 0 && $attacking_armor3 != 0 && $attacking_armor4 == 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_armor1, $attacking_armor3);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = 0;
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = 0;
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 == 0 && $attacking_armor3 == 0 && $attacking_armor4 != 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_armor1, $attacking_armor4);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = 0;
					$att_armor3_loss = 0;
					$att_armor4_loss = (($attacking_armor4 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 == 0 && $attacking_armor3 == 0 && $attacking_armor4 == 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_armor1, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = (($attacking_armor1 / $attacker_GCF) * $total_loss_GCF);
					$att_armor2_loss = 0;
					$att_armor3_loss = 0;
					$att_armor4_loss = 0;
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 == 0 && $attacking_armor3 != 0 && $attacking_armor4 == 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_armor3, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = 0;
					$att_armor3_loss = (($attacking_armor3 / $attacker_GCF) * $total_loss_GCF);
					$att_armor4_loss = 0;
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 != 0 && $attacking_armor3 == 0 && $attacking_armor4 == 0 && $attacking_armor5 != 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_armor2, $attacking_armor5);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = 0;
					$att_armor4_loss = 0;
					$att_armor5_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 != 0 && $attacking_armor3 == 0 && $attacking_armor4 != 0 && $attacking_armor5 == 0)
				{
					$attacker_GCF = warfare_GRF_two($attacking_armor2, $attacking_armor4);
					$total_loss_GCF = ($att_armor_losses / $attacker_GCF);
					$total_loss_GCF = number_format($total_loss_GCF, 0);
					$att_armor1_loss = 0;
					$att_armor2_loss = (($attacking_armor2 / $attacker_GCF) * $total_loss_GCF);
					$att_armor3_loss = 0;
					$att_armor4_loss = (($attacking_armor5 / $attacker_GCF) * $total_loss_GCF);
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 != 0 && $attacking_armor2 == 0 && $attacking_armor3 == 0 && $attacking_armor4 == 0 && $attacking_armor5 == 0)
				{
					$att_armor1_loss = $att_armor_losses;
					$att_armor2_loss = 0;
					$att_armor3_loss = 0;
					$att_armor4_loss = 0;
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 != 0 && $attacking_armor3 == 0 && $attacking_armor4 == 0 && $attacking_armor5 == 0)
				{
					$att_armor1_loss = 0;
					$att_armor2_loss = $att_armor_losses;
					$att_armor3_loss = 0;
					$att_armor4_loss = 0;
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 == 0 && $attacking_armor3 != 0 && $attacking_armor4 == 0 && $attacking_armor5 == 0)
				{
					$att_armor1_loss = 0;
					$att_armor2_loss = 0;
					$att_armor3_loss = $att_armor_losses;
					$att_armor4_loss = 0;
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 == 0 && $attacking_armor3 == 0 && $attacking_armor4 != 0 && $attacking_armor5 == 0)
				{
					$att_armor1_loss = 0;
					$att_armor2_loss = 0;
					$att_armor3_loss = 0;
					$att_armor4_loss = $att_armor_losses;
					$att_armor5_loss = 0;
				}
				elseif($attacking_armor1 == 0 && $attacking_armor2 == 0 && $attacking_armor3 == 0 && $attacking_armor4 == 0 && $attacking_armor5 != 0)
				{
					$att_armor1_loss = 0;
					$att_armor2_loss = 0;
					$att_armor3_loss = 0;
					$att_armor4_loss = 0;
					$att_armor5_loss = $att_armor_losses;
				}
				else
				{
					$att_armor1_loss = 0;
					$att_armor2_loss = 0;
					$att_armor3_loss = 0;
					$att_armor4_loss = 0;
					$att_armor5_loss = 0;
				}

				// Format the losses
				$att_inf1_loss = number_format($att_inf1_loss, 0);
				$att_inf2_loss = number_format($att_inf2_loss, 0);
				$att_inf3_loss = number_format($att_inf3_loss, 0);
				$att_armor1_loss = number_format($att_armor1_loss, 0);
				$att_armor2_loss = number_format($att_armor2_loss, 0);
				$att_armor3_loss = number_format($att_armor3_loss, 0);
				$att_armor4_loss = number_format($att_armor4_loss, 0);
				$att_armor5_loss = number_format($att_armor5_loss, 0);
				$att_inf_losses = number_format($att_inf_losses, 0);
				$att_armor_losses = number_format($att_armor_losses, 0);

				$def_inf1_loss = number_format($def_inf1_loss, 0);
				$def_inf2_loss = number_format($def_inf2_loss, 0);
				$def_inf3_loss = number_format($def_inf3_loss, 0);
				$def_armor1_loss = number_format($def_armor1_loss, 0);
				$def_armor2_loss = number_format($def_armor2_loss, 0);
				$def_armor3_loss = number_format($def_armor3_loss, 0);
				$def_armor4_loss = number_format($def_armor4_loss, 0);
				$def_armor5_loss = number_format($def_armor5_loss, 0);
				$def_inf_losses = number_format($def_inf_losses, 0);
				$def_armor_losses = number_format($def_armor_losses, 0);

				// Determine who gets what in the warfare table
				if($ID_aggressor == $ID_me)
				{
					$new_att_inf_cas = ($att_inf_cas + $att_inf_losses);
					$new_def_inf_cas = ($def_inf_cas + $def_inf_losses);
					$new_att_armor_cas = ($att_armor_cas + $att_armor_losses);
					$new_def_armor_cas = ($def_armor_cas + $def_armor_losses);
				}
				elseif($ID_defender == $ID_me)
				{
					$new_att_inf_cas = ($att_inf_cas + $def_inf_losses);
					$new_def_inf_cas = ($def_inf_cas + $att_inf_losses);
					$new_att_armor_cas = ($att_armor_cas + $def_armor_losses);
					$new_def_armor_cas = ($def_armor_cas + $att_armor_losses);
				}
				else
				{
					$new_att_inf_cas = ($def_inf_cas + $def_inf_losses);
					$new_def_inf_cas = ($att_inf_cas + $att_inf_losses);
					$new_att_armor_cas = ($def_armor_cas + $def_armor_losses);
					$new_def_armor_cas = ($att_armor_cas + $att_armor_losses);
				}

				// Calculate the losses to take from the deployed table
				$att_inf1_change = ($att_inf1 - $att_inf1_loss);
				$att_inf2_change = ($att_inf2 - $att_inf2_loss);
				$att_inf3_change = ($att_inf3 - $att_inf3_loss);
				$att_armor1_change = ($att_armor1 - $att_armor1_loss);
				$att_armor2_change = ($att_armor2 - $att_armor2_loss);
				$att_armor3_change = ($att_armor3 - $att_armor3_loss);
				$att_armor4_change = ($att_armor4 - $att_armor4_loss);
				$att_armor5_change = ($att_armor5 - $att_armor5_loss);

				// Remove dead units from the units that just attacked before adding the survivors to the USED table
				$just_used_inf1 = ($attacking_inf1 - $att_inf1_loss);
				$just_used_inf2 = ($attacking_inf2 - $att_inf2_loss);
				$just_used_inf3 = ($attacking_inf3 - $att_inf3_loss);
				$just_used_armor1 = ($attacking_armor1 - $att_armor1_loss);
				$just_used_armor2 = ($attacking_armor2 - $att_armor2_loss);
				$just_used_armor3 = ($attacking_armor3 - $att_armor3_loss);
				$just_used_armor4 = ($attacking_armor4 - $att_armor4_loss);
				$just_used_armor5 = ($attacking_armor5 - $att_armor5_loss);

				// Add the exausted units to the USED table
				$att_inf1_used_all = ($just_used_inf1 + $att_inf_1_used);
				$att_inf2_used_all = ($just_used_inf2 + $att_inf_2_used);
				$att_inf3_used_all = ($just_used_inf3 + $att_inf_3_used);
				$att_armor1_used_all = ($just_used_armor1 + $att_armor_1_used);
				$att_armor2_used_all = ($just_used_armor2 + $att_armor_2_used);
				$att_armor3_used_all = ($just_used_armor3 + $att_armor_3_used);
				$att_armor4_used_all = ($just_used_armor4 + $att_armor_4_used);
				$att_armor5_used_all = ($just_used_armor5 + $att_armor_5_used);

				// Calculate the losses to take from the military table
				$def_inf1_change = ($def_inf1 - $def_inf1_loss);
				$def_inf2_change = ($def_inf2 - $def_inf2_loss);
				$def_inf3_change = ($def_inf3 - $def_inf3_loss);
				$def_armor1_change = ($def_armor1 - $def_armor1_loss);
				$def_armor2_change = ($def_armor2 - $def_armor2_loss);
				$def_armor3_change = ($def_armor3 - $def_armor3_loss);
				$def_armor4_change = ($def_armor4 - $def_armor4_loss);
				$def_armor5_change = ($def_armor5 - $def_armor5_loss);

				$att_inf_losses_now = ($att_inf_losses + $att_inf_loss);
				$def_inf_losses_now = ($def_inf_losses + $def_inf_loss);

				$att_armor_losses_now = ($att_armor_losses + $att_armor_loss);
				$def_armor_losses_now = ($def_armor_losses + $def_armor_loss);

				// Format the private message
				$ID_recip = $ID_defend;
				$ID_send = $ID_attack;

				// Collect the username that corresponds with the sender
				$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_send'") or die(mysql_error());
				$IDcheck3 = mysql_fetch_array($result2) or die(mysql_error());
				$sender = stripslashes($IDcheck3['username']);

				$subject = "You have been attacked!";
				$subject = mysql_real_escape_string($subject);
				if($who_wins == "defender")
				{
					$body = "You have been attacked by " . $sender . ".\n\nYour ground forces have achieved victory. They eliminated " . $att_inf_losses . " infantry and " . $att_armor_losses . " armor while suffering " . $def_inf_losses . " infantry and " . $def_armor_losses . " armor losses.\n\nThe iC Moderation Team";
				}
				elseif($who_wins == "attacker")
				{
					$body = "You have been attacked by " . $sender . ".\n\nYour ground forces have been defeated. They eliminated " . $att_inf_losses . " infantry and " . $att_armor_losses . " armor while suffering " . $def_inf_losses . " infantry and " . $def_armor_losses . " armor losses.\n\nThe iC Moderation Team";
				}
				else
				{
					$body = "You have been attacked by " . $sender . ".\n\nYour ground forces have achieved a draw. They eliminated " . $att_inf_losses . " infantry and " . $att_armor_losses . " armor while suffering " . $def_inf_losses . " infantry and " . $def_armor_losses . " armor losses.\n\nThe iC Moderation Team";
				}

				$body = mysql_real_escape_string($body);
				$time_sent = gmdate('U');

				// Create the private message in the database
				$insert1 = "INSERT INTO private_message (ID_recip, ID_send, subject, time_sent, body) VALUES ('" . $ID_recip . "', '" . $ID_send . "', '" . $subject . "', '" . $time_sent . "', '" . $body . "')";
				$add_message1 = mysql_query($insert1);

				// Update the military database for attacker
				$insert2 = "UPDATE military SET inf_loss='" . $att_inf_losses_now . "', armor_loss='" . $att_armor_losses_now . "' WHERE ID = '" . $ID_attack . "'";
				$add_message2 = mysql_query($insert2);
				// Update the warfare database for attacker & defender
				$insert3 = "UPDATE warfare SET att_inf_cas='" . $new_att_inf_cas . "', att_armor_cas='" . $new_att_armor_cas . "', def_inf_cas='" . $new_def_inf_cas . "', def_armor_cas='" . $new_def_armor_cas . "', open_shot='1', last_combat='" . $time_sent . "' WHERE ID_war = '" . $ID_war . "'";
				$add_message3 = mysql_query($insert3);
				// Update the deployed database for attacker
				$insert4 = "UPDATE deployed SET inf_1_deploy='" . $att_inf1_change . "', inf_2_deploy='" . $att_inf2_change . "', inf_3_deploy='" . $att_inf3_change . "', armor_1_deploy='" . $att_armor1_change . "', armor_2_deploy='" . $att_armor2_change . "', armor_3_deploy='" . $att_armor3_change . "', armor_4_deploy='" . $att_armor4_change . "', armor_5_deploy='" . $att_armor5_change . "', inf_1_used='" . $att_inf1_used_all . "', inf_2_used='" . $att_inf2_used_all . "', inf_3_used='" . $att_inf3_used_all . "', armor_1_used='" . $att_armor1_used_all . "', armor_2_used='" . $att_armor2_used_all . "', armor_3_used='" . $att_armor3_used_all . "', armor_4_used='" . $att_armor4_used_all . "', armor_5_used='" . $att_armor5_used_all . "' WHERE ID = '" . $ID_attack . "'";
				$add_message4 = mysql_query($insert4);

				// Update the military database for defender
				$insert5 = "UPDATE military SET inf_1='" . $def_inf1_change . "', inf_2='" . $def_inf2_change . "', inf_3='" . $def_inf3_change . "', armor_1='" . $def_armor1_change . "', armor_2='" . $def_armor2_change . "', armor_3='" . $def_armor3_change . "', armor_4='" . $def_armor4_change . "', armor_5='" . $def_armor5_change . "', inf_loss='" . $def_inf_losses_now . "', armor_loss='" . $def_armor_losses_now . "' WHERE ID = '" . $ID_defend . "'";
				$add_message5 = mysql_query($insert5);

				// Then redirect them to the nation
				header("Location: military_command.php");
			}
			include ('header.php');
			
			// Determine which side menu to use
			which_side_menu($ID, $mod_admin, $site_area);
			?>
			<td>
			<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
			<table class='list_central'>
				<tr>
					<th class='list_central_header' colspan='2'>Commence Ground Attack</th>
				</tr>
				<tr>
					<td class='list_central_instructions' colspan='2'>  You are preparing to commence a ground attack. Please beware that
																		the aggressor has a slight bonus for the inital attack, but after the
																		opening salvos, the defender holds a slight home advantage. Many factors
																		impact the outcome of any given battle and after all modifiers and
																		reductions, it comes down to a roll of the dice. Please input the
																		number of infantry and armor you want to commit to the upcoming battle.</td>
				</tr>
				<tr>
					<td class='list_central_row_info' colspan='2'>You have used <?php echo $total_inf_used; ?> infantry today.</td>
				</tr>
				<tr>
					<td class='list_central_row_info' colspan='2'>You have used <?php echo $total_armor_used; ?> armor today.</td>
				</tr>
				<tr>
					<td class='list_central_row_title'>Use Lv 1 Infantry (<?php echo $inf1; ?> deployed)</td>
					<td class='list_central_row_info'><input type='text' name='inf1' value='0' maxlength='4' /></td>
				</tr>
				<tr>
					<td class='list_central_row_title'>Use Lv 2 Infantry (<?php echo $inf2; ?> deployed)</td>
					<td class='list_central_row_info'><input type='text' name='inf2' value='0' maxlength='4' /></td>
				</tr>
				<tr>
					<td class='list_central_row_title'>Use Lv 3 Infantry (<?php echo $inf3; ?> deployed)</td>
					<td class='list_central_row_info'><input type='text' name='inf3' value='0' maxlength='4' /></td>
				</tr>
				<tr>
					<td class='list_central_row_title'>Use Lv 1 Armor (<?php echo $armor1; ?> deployed)</td>
					<td class='list_central_row_info'><input type='text' name='armor1' value='0' maxlength='4' /></td>
				</tr>
				<tr>
					<td class='list_central_row_title'>Use Lv 2 Armor (<?php echo $armor2; ?> deployed)</td>
					<td class='list_central_row_info'><input type='text' name='armor2' value='0' maxlength='4' /></td>
				</tr>
				<tr>
					<td class='list_central_row_title'>Use Lv 3 Armor (<?php echo $armor3; ?> deployed)</td>
					<td class='list_central_row_info'><input type='text' name='armor3' value='0' maxlength='4' /></td>
				</tr>
				<tr>
					<td class='list_central_row_title'>Use Lv 4 Armor (<?php echo $armor4; ?> deployed)</td>
					<td class='list_central_row_info'><input type='text' name='armor4' value='0' maxlength='4' /></td>
				</tr>
				<tr>
					<td class='list_central_row_title'>Use Lv 5 Armor (<?php echo $armor5; ?> deployed)</td>
					<td class='list_central_row_info'><input type='text' name='armor5' value='0' maxlength='4' /></td>
				</tr>
				<tr>
					<td class='button' colspan='2'><?php echo $deployed_fubar; ?>
						<?php echo "<input type='hidden' name='URL_war' value='" . $URL_war . "' />
									<input type='hidden' name='ID_me' value='" . $ID . "' />" ?></td>
				</tr>
			</table>
			</form>
			</td>
			</tr>
			</table>
			<?php
			include ('footer.php');
		}
	}
}
else
{
	// If the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>