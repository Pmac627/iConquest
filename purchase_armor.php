<?php
/** purchase_armor.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/citizens_function.php');
include ('functions/resource_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Building Armor Units';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

################
/** Hardcoded Base Prices**/
$price_armor_1 = 100;
$price_armor_2 = 200;
$price_armor_3 = 300;
$price_armor_4 = 400;
$price_armor_5 = 500;
################

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
		//otherwise they are shown the armor purchase page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		//collect the nation information for display from the nation_variables table
		$nationstats = mysql_query("SELECT resource_1, resource_2, citizens, treasury, land, infra, tech, capital FROM nation_variables WHERE ID = '$ID'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			// Collect the raw data from the nation_variables table in the db
			$resource1 = stripslashes($row['resource_1']);
			$resource2 = stripslashes($row['resource_2']);
			$citizens_orig = stripslashes($row['citizens']);
			$treasury = stripslashes($row['treasury']);
			$land = stripslashes($row['land']);
			$infra = stripslashes($row['infra']);
			$tech = stripslashes($row['tech']);
			$capital = stripslashes($row['capital']);
		}

		//collect the nation information for display from the military table
		$military = mysql_query("SELECT inf_1, inf_2, inf_3, armor_1, armor_2, armor_3, armor_4, armor_5 FROM military WHERE ID = '$ID'") or die(mysql_error());
		while($mil = mysql_fetch_array( $military ))
		{
			// Collect the raw data from the military table in the db
			$old_inf_1 = stripslashes($mil['inf_1']);
			$old_inf_2 = stripslashes($mil['inf_2']);
			$old_inf_3 = stripslashes($mil['inf_3']);
			$old_armor_1 = stripslashes($mil['armor_1']);
			$old_armor_2 = stripslashes($mil['armor_2']);
			$old_armor_3 = stripslashes($mil['armor_3']);
			$old_armor_4 = stripslashes($mil['armor_4']);
			$old_armor_5 = stripslashes($mil['armor_5']);
		}

		//collect the nation information for display from the deployed table
		$deploy = mysql_query("SELECT inf_1_deploy, inf_2_deploy, inf_3_deploy, armor_1_deploy, armor_2_deploy, armor_3_deploy, armor_4_deploy, armor_5_deploy FROM deployed WHERE ID = '$ID'") or die(mysql_error());
		while($dep = mysql_fetch_array( $deploy ))
		{
			// Collect the raw data from the deployed table in the db 
			$inf_1_deployed = stripslashes($dep['inf_1_deploy']);
			$inf_2_deployed = stripslashes($dep['inf_2_deploy']);
			$inf_3_deployed = stripslashes($dep['inf_3_deploy']);
			$armor_1_deployed = stripslashes($dep['armor_1_deploy']);
			$armor_2_deployed = stripslashes($dep['armor_2_deploy']);
			$armor_3_deployed = stripslashes($dep['armor_3_deploy']);
			$armor_4_deployed = stripslashes($dep['armor_4_deploy']);
			$armor_5_deployed = stripslashes($dep['armor_5_deploy']);
		}

		//collect the nation information for display from the civil_works table
		$civil = mysql_query("SELECT small_farm, dock, war_factory, iron_mine FROM civil_works WHERE ID = '$ID'") or die(mysql_error());
		while($cw = mysql_fetch_array( $civil ))
		{
			// Collect the raw data from the civil_works table in the db 
			$cw_small_farm = stripslashes($cw['small_farm']);
			$cw_dock = stripslashes($cw['dock']);
			$cw_war_factory = stripslashes($cw['war_factory']);
			$cw_iron_mine = stripslashes($cw['iron_mine']);
		}

		// Compile a master array full of ALL trades this nation has
		// The included function 'trade_list' already uses stripslashes() on the resource list
		$trade_list = trade_list($ID);
		$trade_list[1] = $resource1;
		$trade_list[2] = $resource2;
		sort($trade_list);
		$trade_list = array_unique($trade_list);

		while($trade_list[$count] != 99)
		{
			if($trade_list[$count] == 4)
			{
				$resource4 = 4;
			}
			if($trade_list[$count] == 5)
			{
				$resource5 = 5;
			}
			if($trade_list[$count] == 6)
			{
				$resource6 = 6;
			}
			if($trade_list[$count] == 13)
			{
				$resource7 = 13;
			}
		$count++;
		}

		$citizens = citizens_calculation($infra, $land, $tech, $capital, $citizens_orig);
		$citizens = resource_total_population($citizens, $resource5, $resource7, $cw_small_farm, $cw_dock);

		$infantry_total_count = ($inf_1_deployed + $inf_2_deployed + $inf_3_deployed + $old_inf_1 + $old_inf_2 + $old_inf_3);
		$armor_total_count = ($armor_1_deployed + $armor_2_deployed + $armor_3_deployed + $armor_4_deployed + $armor_5_deployed + $old_armor_1 + $old_armor_2 + $old_armor_3 + $old_armor_4 + $old_armor_5);

		// The max armor limit is 20% of infantry levels
		$max_armor_limit = (($infantry_total_count / 100) * 20);
		$max_armor_limit = resource_armor_limit($max_armor_limit, $resource6);

		$price_armor_1 = resource_armor_cost($price_armor_1, $resource4, $cw_iron_mine);
		$price_armor_2 = resource_armor_cost($price_armor_2, $resource4, $cw_iron_mine);
		$price_armor_3 = resource_armor_cost($price_armor_3, $resource4, $cw_iron_mine);
		$price_armor_4 = resource_armor_cost($price_armor_4, $resource4, $cw_iron_mine);
		$price_armor_5 = resource_armor_cost($price_armor_5, $resource4, $cw_iron_mine);

		if($cw_war_factory == 0)
		{
			$purchase_fubar = "You need to build a War Factory to be able to purchase armor";
		}
		elseif($tech < 25)
		{
			$purchase_fubar = "You need at least 25 technology to purchase armor";
		}
		elseif($capital < 3)
		{
			$purchase_fubar = "You need at least 3 capital to purchase armor";
		}
		elseif($max_armor_limit < $armor_total_count)
		{
			$purchase_fubar = "You cannot purchase any more armor.  You have too many!";
		}
		else
		{
			$purchase_fubar = "<input type='submit' name='submit' value='Purchase!' />";
		}

		//if armor purchase form is submitted
		if(isset($_POST['submit']))
		{
			$_POST['armor_1_change'] = strip_tags($_POST['armor_1_change']);
			$_POST['armor_2_change'] = strip_tags($_POST['armor_2_change']);
			$_POST['armor_3_change'] = strip_tags($_POST['armor_3_change']);
			$_POST['armor_4_change'] = strip_tags($_POST['armor_4_change']);
			$_POST['armor_5_change'] = strip_tags($_POST['armor_5_change']);

			if(isset($_POST['armor_1_change'], $_POST['armor_2_change'], $_POST['armor_3_change'], $_POST['armor_4_change'], $_POST['armor_5_change']))
			{
				if(sanity_check($_POST['armor_1_change'], 'numeric', 5) != FALSE && sanity_check($_POST['armor_2_change'], 'numeric', 5) != FALSE && sanity_check($_POST['armor_3_change'], 'numeric', 5) != FALSE && sanity_check($_POST['armor_4_change'], 'numeric', 5) != FALSE && sanity_check($_POST['armor_5_change'], 'numeric', 5) != FALSE)
				{
					$armor_1_change = $_POST['armor_1_change'];
					$armor_2_change = $_POST['armor_2_change'];
					$armor_3_change = $_POST['armor_3_change'];
					$armor_4_change = $_POST['armor_4_change'];
					$armor_5_change = $_POST['armor_5_change'];
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=49");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			// calculate exact price of requested transaction amount
			$armor_1_cost = ($price_armor_1 * $armor_1_change);
			$armor_2_cost = ($price_armor_2 * $armor_2_change);
			$armor_3_cost = ($price_armor_3 * $armor_3_change);
			$armor_4_cost = ($price_armor_4 * $armor_4_change);
			$armor_5_cost = ($price_armor_5 * $armor_5_change);

			$totalcost = ($armor_1_cost + $armor_2_cost + $armor_3_cost + $armor_4_cost + $armor_5_cost);

			// add or subtract new armor amount from old
			$new_armor_1 = ($old_armor_1 + $armor_1_change);
			$new_armor_2 = ($old_armor_2 + $armor_2_change);
			$new_armor_3 = ($old_armor_3 + $armor_3_change);
			$new_armor_4 = ($old_armor_4 + $armor_4_change);
			$new_armor_5 = ($old_armor_5 + $armor_5_change);

			// subtract new treasury from old
			$newtreasury = ($treasury - $totalcost);

			// check to see if the requested purchase will bankrupt nation
			if($newtreasury <= 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=50");
			}

			// update the armor totals to the new ones!
			$insert = "UPDATE military SET armor_1='" . $new_armor_1 . "', armor_2='" . $new_armor_2 . "', armor_3='" . $new_armor_3 . "', armor_4='" . $new_armor_4 . "', armor_5='" . $new_armor_5 . "' WHERE ID='" . $ID . "'";
			$add_military = mysql_query($insert);

			// update the treasury total to the new one!
			$insert = "UPDATE nation_variables SET treasury='" . $newtreasury . "' WHERE ID='" . $ID . "'";
			$add_treasury = mysql_query($insert);

			//then redirect them to the same page
			header("Location: purchase_armor.php");
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='3'>Build Armor</th>
			</tr>
			<tr>
				<td class='list_central_row_title'>Current Treasury Total:</td>
				<td class='list_central_row_info' colspan='2'> $<?php echo
											$treasury = number_format($treasury,2);
											$treasury; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Current Population:</td>
				<td class='list_central_row_info' colspan='2'><?php echo
											$citizens = number_format($citizens,0);
											$citizens; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Max Armor Allowed:</td>
				<td class='list_central_row_info' colspan='2'><?php echo
											$max_armor_limit = number_format($max_armor_limit, 0);
											$max_armor_limit; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Armor Unit</td>
				<td class='list_central_row_title'>Owned (Deployed)</td>
				<td class='list_central_row_title'>Cost of 1 Unit</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Lv 1 Armor</td>
				<td class='list_central_row_info'><?php echo $old_armor_1 . " (" . $armor_1_deployed . ")"; ?></td>
				<td class='list_central_row_info'> $<?php echo
								$price_armor_1 = number_format($price_armor_1,2);
								$price_armor_1; ?></td>
			</tr>
				<td class='list_central_row_title'>Lv 2 Armor</td>
				<td class='list_central_row_info'><?php echo $old_armor_2 . " (" . $armor_2_deployed . ")"; ?></td>
				<td class='list_central_row_info'> $<?php echo
								$price_armor_2 = number_format($price_armor_2,2);
								$price_armor_2; ?></td>
			</tr>
				<td class='list_central_row_title'>Lv 3 Armor</td>
				<td class='list_central_row_info'><?php echo $old_armor_3 . " (" . $armor_3_deployed . ")"; ?></td>
				<td class='list_central_row_info'> $<?php echo
								$price_armor_3 = number_format($price_armor_3,2);
								$price_armor_3; ?></td>
			</tr>
				<td class='list_central_row_title'>Lv 4 Armor</td>
				<td class='list_central_row_info'><?php echo $old_armor_4 . " (" . $armor_4_deployed . ")"; ?></td>
				<td class='list_central_row_info'> $<?php echo
								$price_armor_4 = number_format($price_armor_4,2);
								$price_armor_4; ?></td>
			</tr>
				<td class='list_central_row_title'>Lv 5 Armor</td>
				<td class='list_central_row_info'><?php echo $old_armor_5 . " (" . $armor_5_deployed . ")"; ?></td>
				<td class='list_central_row_info'> $<?php echo
								$price_armor_5 = number_format($price_armor_5,2);
								$price_armor_5; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title' colspan='3'>Purchase/Dismantle Armor</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Buy Armor 1</td>
				<td class='list_central_row_info' colspan='2'><input type='text' name='armor_1_change' maxlength='5' value='0' /></td>
			</tr>
				<td class='list_central_row_title'>Buy Armor 2</td>
				<td class='list_central_row_info' colspan='2'><input type='text' name='armor_2_change' maxlength='5' value='0' /></td>
			</tr>
				<td class='list_central_row_title'>Buy Armor 3</td>
				<td class='list_central_row_info' colspan='2'><input type='text' name='armor_3_change' maxlength='5' value='0' /></td>
			</tr>
				<td class='list_central_row_title'>Buy Armor 4</td>
				<td class='list_central_row_info' colspan='2'><input type='text' name='armor_4_change' maxlength='5' value='0' /></td>
			</tr>
				<td class='list_central_row_title'>Buy Armor 5</td>
				<td class='list_central_row_info' colspan='2'><input type='text' name='armor_5_change' maxlength='5' value='0' /></td>
			</tr>
			<tr>
				<td class='button' colspan='3'><?php echo $purchase_fubar; ?></td>
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
else
{
	//if the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>