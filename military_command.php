<?php
/** military_command.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/days_since_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Military Headquarters';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

//checks cookies to make sure they are logged in
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

	//if the cookie has the wrong password, they are taken to the expired session login page
	if($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		//otherwise they are shown the war management page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='4'>Military Command</th>
			</tr>
			<tr>
				<td class='list_central_nav' colspan='4'><a class='link_inline' href='warfare_deploy_ground.php' title='Deploy Ground Units'>Deploy Ground Units</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Opponent</td>
				<td class='list_central_row_title' width='250'>Details</td>
				<td class='list_central_row_title' width='80'>Date/Time</td>
				<td class='list_central_row_title'>Status</td>
			</tr>
				<?php

					/* 
					Warfare is probably one of the most complex algorithms involved in the game.
					First, there are many different modes which haven't even been coded yet.
					(See the googledocs file about iConquest for more details)  The peace modes
					and how warfare is manages differs from most games.  When war is declared,
					the war begins (no confirmation needed).  Then either side can offer peace.
					Once peace is offered by both sides, the war ends.  War can expire after 7 days.
					Now the interesting part.  If no attacks are made over a 48 hour period, a state
					of defacto cease fire exists.  Attacking after this period hurts public opinion
					by increasing the war dislike value by 2.  After a 72 hour period of no attacks,
					a defacto peace exists and the war ends.
					*/

					// Display wars declared by this nation

					echo "  <tr>
								<th class='list_central_header' colspan='4'>Distant Theater</th>
							</tr>" ;

					$count_attack = 0;

					// Count the number of opponents a user has
					$war_list = mysql_query("SELECT ID_defend, ID_war, war_date, war_stat, att_inf_cas, def_inf_cas, att_armor_cas, def_armor_cas FROM warfare WHERE ID_attack = '$ID'") or die(mysql_error());
					while($battle_list = mysql_fetch_array( $war_list ))
					{
						$ID_defend = stripslashes($battle_list['ID_defend']);
						$ID_war = stripslashes($battle_list['ID_war']);
						$war_date = stripslashes($battle_list['war_date']);
						$war_stat = stripslashes($battle_list['war_stat']);
						$att_inf_cas = stripslashes($battle_list['att_inf_cas']);
						$def_inf_cas = stripslashes($battle_list['def_inf_cas']);
						$att_armor_cas = stripslashes($battle_list['att_armor_cas']);
						$def_armor_cas = stripslashes($battle_list['def_armor_cas']);
						$count_attack++;

						// Convert the creation Unix timestamp into a date & time
						$raw_time = $war_date;
						$formatted_war_date = date('Y-m-d', $raw_time);

						// collect the nation leader that corresponds with ID_attack
						$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_defend'") or die(mysql_error());
						while($ruler = mysql_fetch_array($result2))
						{
							$leader = stripslashes($ruler['username']);
						}

						// 0 = active; 1 = atk off peace; 2 = def off peace; 3 = expired; 4 = defacto ceasefire; 5 = defacto peace
						// If a war is 7 days old, make it expired.
						$a = $war_stat;
						$war_check = days_since_calculation($war_date);
						if($war_check > 7)
						{
							$war_stat = 3;
							// update to deleted
							$insert = "UPDATE warfare SET war_stat='" . $war_stat . "' WHERE ID_war='" . $ID_war . "'";
							$add_member = mysql_query($insert);
						}

						if($a == 0 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_defend . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the aggressor.<br />
										Your Infantry Losses: " . $att_inf_cas . "<br />
										Your Armor Losses: " . $att_armor_cas . "<br />
										Their Infantry Losses: " . $def_inf_cas . "<br />
										Their Armor Losses: " . $def_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'><a class='link_inline' href='warfare_ground.php?ID_war=" . $ID_war . "' title='Ground Attack'>Attack</a><br />
										<a class='link_inline' href='warfare_peace.php?ID=" . $ID_defend . "&war=" . $ID_war . "' title='Offer Peace'>Active</a></td>
								</tr>" ;
						}
						elseif($a == 1 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_defend . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the aggressor.<br />
										Your Infantry Losses: " . $att_inf_cas . "<br />
										Your Armor Losses: " . $att_armor_cas . "<br />
										Their Infantry Losses: " . $def_inf_cas . "<br />
										Their Armor Losses: " . $def_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'>You offered peace</td>
								</tr>" ;
						}
						elseif($a == 2 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_defend . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the aggressor.<br />
										Your Infantry Losses: " . $att_inf_cas . "<br />
										Your Armor Losses: " . $att_armor_cas . "<br />
										Their Infantry Losses: " . $def_inf_cas . "<br />
										Their Armor Losses: " . $def_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'><a class='link_inline' href='warfare_ground.php?ID_war=" . $ID_war . "' title='Ground Attack'>Attack</a><br />
										<a class='link_inline' href='warfare_accept_peace.php?ID=" . $ID_defend . "&war=" . $ID_war . "' title='Accept Peace'>They offered peace</a></td>
								</tr>" ;
						}
						elseif($a == 3 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_defend . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the aggressor.<br />
										Your Infantry Losses: " . $att_inf_cas . "<br />
										Your Armor Losses: " . $att_armor_cas . "<br />
										Their Infantry Losses: " . $def_inf_cas . "<br />
										Their Armor Losses: " . $def_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'>Peace Declared</td>
								</tr>" ;
						}
						elseif($a == 4 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_defend . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the aggressor.<br />
										Your Infantry Losses: " . $att_inf_cas . "<br />
										Your Armor Losses: " . $att_armor_cas . "<br />
										Their Infantry Losses: " . $def_inf_cas . "<br />
										Their Armor Losses: " . $def_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'><a class='link_inline' href='warfare_ground.php?ID_war=" . $ID_war . "' title='Ground Attack'>Attack</a><br />
										Defacto Ceasefire</td>
								</tr>" ;
						}
						elseif($a == 5 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_defend . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the aggressor.<br />
										Your Infantry Losses: " . $att_inf_cas . "<br />
										Your Armor Losses: " . $att_armor_cas . "<br />
										Their Infantry Losses: " . $def_inf_cas . "<br />
										Their Armor Losses: " . $def_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'>Defacto Peace</td>
								</tr>" ;
						}
						else
						{
						}
					}

					if($count_attack == 0)
					{
						echo "  <tr>
									<td class='list_central_nav' colspan='4'>You have no wars on distant fronts.</td>
								</tr>" ;
					}

					// Display wars declared by others on this nation

					echo "  <tr>
								<th class='list_central_header' colspan='4'>Home Theater</th>
							</tr>" ;

					$count_defend = 0;

					//count the number of opponents a user has
					$war_list = mysql_query("SELECT ID_attack, ID_war, war_date, war_stat, att_inf_cas, def_inf_cas, att_armor_cas, def_armor_cas FROM warfare WHERE ID_defend = '$ID'") or die(mysql_error());
					while($battle_list = mysql_fetch_array( $war_list ))
					{
						$ID_attack = stripslashes($battle_list['ID_attack']);
						$ID_war = stripslashes($battle_list['ID_war']);
						$war_date = stripslashes($battle_list['war_date']);
						$war_stat = stripslashes($battle_list['war_stat']);
						$att_inf_cas = stripslashes($battle_list['att_inf_cas']);
						$def_inf_cas = stripslashes($battle_list['def_inf_cas']);
						$att_armor_cas = stripslashes($battle_list['att_armor_cas']);
						$def_armor_cas = stripslashes($battle_list['def_armor_cas']);
						$count_defend++;

						// Convert the creation Unix timestamp into a date & time
						$raw_time = $war_date;
						$formatted_war_date = date('Y-m-d', $raw_time);

						// collect the nation leader that corresponds with ID_defend
						$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_attack'") or die(mysql_error());
						while($ruler = mysql_fetch_array($result2))
						{
							$leader = stripslashes($ruler['username']);
						}

						// 0 = active; 1 = atk off peace; 2 = def off peace; 3 = expired; 4 = defacto ceasefire; 5 = defacto peace
						// If a war is 7 days old, make it expired.
						$a = $war_stat;
						$war_check = days_since_calculation($war_date);
						if($war_check > 7)
						{
							$war_stat = 3;
							// update to deleted
							$insert = "UPDATE warfare SET war_stat='" . $war_stat . "' WHERE ID_war='" . $ID_war . "'";
							$add_member = mysql_query($insert);
						}

						if($a == 0 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_attack . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the defender.<br />
										Your Infantry Losses: " . $def_inf_cas . "<br />
										Your Armor Losses: " . $def_armor_cas . "<br />
										Their Infantry Losses: " . $att_inf_cas . "<br />
										Their Armor Losses: " . $att_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'><a class='link_inline' href='warfare_ground.php?ID_war=" . $ID_war . "' title='Ground Attack'>Attack</a><br />
										<a class='link_inline' href='warfare_peace.php?ID=" . $ID_attack . "&war=" . $ID_war . "' title='Offer Peace'>Active</a></td>
								</tr>" ;
						}
						elseif($a == 1 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_attack . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the defender.<br />
										Your Infantry Losses: " . $def_inf_cas . "<br />
										Your Armor Losses: " . $def_armor_cas . "<br />
										Their Infantry Losses: " . $att_inf_cas . "<br />
										Their Armor Losses: " . $att_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'><a class='link_inline' href='warfare_ground.php?ID_war=" . $ID_war . "' title='Ground Attack'>Attack</a><br />
										<a class='link_inline' href='warfare_accept_peace.php?ID=" . $ID_attack . "&war=" . $ID_war . "' title='Accept Peace'>They offered peace</a></td>
								</tr>" ;
						}
						elseif($a == 2 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_attack . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the defender.<br />
										Your Infantry Losses: " . $def_inf_cas . "<br />
										Your Armor Losses: " . $def_armor_cas . "<br />
										Their Infantry Losses: " . $att_inf_cas . "<br />
										Their Armor Losses: " . $att_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'>You offered peace</td>
								</tr>" ;
						}
						elseif($a == 3 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_attack . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the defender.<br />
										Your Infantry Losses: " . $def_inf_cas . "<br />
										Your Armor Losses: " . $def_armor_cas . "<br />
										Their Infantry Losses: " . $att_inf_cas . "<br />
										Their Armor Losses: " . $att_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'>Peace Declared</td>
								</tr>" ;
						}
						elseif($a == 4 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_attack . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the defender.<br />
										Your Infantry Losses: " . $def_inf_cas . "<br />
										Your Armor Losses: " . $def_armor_cas . "<br />
										Their Infantry Losses: " . $att_inf_cas . "<br />
										Their Armor Losses: " . $att_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'><a class='link_inline' href='warfare_ground.php?ID_war=" . $ID_war . "' title='Ground Attack'>Attack</a><br />
										Defacto Ceasefire</td>
								</tr>" ;
						}
						elseif($a == 5 && $war_check <= 14)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_attack . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>You are the defender.<br />
										Your Infantry Losses: " . $def_inf_cas . "<br />
										Your Armor Losses: " . $def_armor_cas . "<br />
										Their Infantry Losses: " . $att_inf_cas . "<br />
										Their Armor Losses: " . $att_armor_cas . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_war_date . "</td>
									<td class='list_central_row_info'>Defacto Peace</td>
								</tr>" ;
						}
						else
						{
						}
					}

					if($count_defend == 0)
					{
						echo "  <tr>
									<td class='list_central_nav' colspan='4'>You have no wars on home fronts.</td>
								</tr>" ;
					}
				?>
		</table>
		</td>
		</tr>
		</table>
		<?php
		include ('footer.php');
	}
}
else
{
	//if the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>