<?php
/** purchase_infantry.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/citizens_function.php');
include ('functions/resource_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Training Infantry Units';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

################
/** Hardcoded Base Prices**/
$price_inf_1 = 10;
$price_inf_2 = 30;
$price_inf_3 = 50;
################

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

	//If the cookie has the wrong password, they are taken to the expired session login page
	if($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		// Otherwise they are shown the infra purchase page

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
		$military = mysql_query("SELECT inf_1, inf_2, inf_3 FROM military WHERE ID = '$ID'") or die(mysql_error());
		while($mil = mysql_fetch_array( $military ))
		{
			// Collect the raw data from the military table in the db
			$old_inf_1 = stripslashes($mil['inf_1']);
			$old_inf_2 = stripslashes($mil['inf_2']);
			$old_inf_3 = stripslashes($mil['inf_3']);
		}

		//collect the nation information for display from the deployed table
		$deploy = mysql_query("SELECT inf_1_deploy, inf_2_deploy, inf_3_deploy FROM deployed WHERE ID = '$ID'") or die(mysql_error());
		while($dep = mysql_fetch_array( $deploy ))
		{
			// Collect the raw data from the deployed table in the db 
			$inf_1_deployed = stripslashes($dep['inf_1_deploy']);
			$inf_2_deployed = stripslashes($dep['inf_2_deploy']);
			$inf_3_deployed = stripslashes($dep['inf_3_deploy']);
		}

		//collect the nation information for display from the civil_works table
		$civil = mysql_query("SELECT small_farm, dock FROM civil_works WHERE ID = '$ID'") or die(mysql_error());
		while($cw = mysql_fetch_array( $civil ))
		{
			// Collect the raw data from the civil_works table in the db 
			$cw_small_farm = stripslashes($cw['small_farm']);
			$cw_dock = stripslashes($cw['dock']);
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
			if($trade_list[$count] == 5)
			{
				$resource5 = $trade_list[$count];
			}
			if($trade_list[$count] == 13)
			{
				$resource7 = $trade_list[$count];
			}
		$count++;
		}

		$citizens = citizens_calculation($infra, $land, $tech, $capital, $citizens_orig);
		$citizens = resource_total_population($citizens, $resource5, $resource7, $cw_small_farm, $cw_dock);
		
		$max_infantry_limit = (($citizens / 100) * 80);

		$infantry_total_count = ($inf_1_deployed + $inf_2_deployed + $inf_3_deployed + $old_inf_1 + $old_inf_2 + $old_inf_3);

		if($max_infantry_limit >= $infantry_total_count)
		{
			$purchase_fubar = "<input type='submit' name='submit' value='Purchase!' />";
		}
		else
		{
			$purchase_fubar = "You cannot purchase any more infantry.  You have too many!";
		}

		//if infantry purchase form is submitted
		if(isset($_POST['submit']))
		{
			$_POST['inf_1_change'] = strip_tags($_POST['inf_1_change']);
			$_POST['inf_2_change'] = strip_tags($_POST['inf_2_change']);
			$_POST['inf_3_change'] = strip_tags($_POST['inf_3_change']);

			if(isset($_POST['inf_1_change'], $_POST['inf_2_change'], $_POST['inf_3_change']))
			{
				if(sanity_check($_POST['inf_1_change'], 'numeric', 6) != FALSE && sanity_check($_POST['inf_2_change'], 'numeric', 6) != FALSE && sanity_check($_POST['inf_3_change'], 'numeric', 6) != FALSE)
				{
					$inf_1_change = $_POST['inf_1_change'];
					$inf_2_change = $_POST['inf_2_change'];
					$inf_3_change = $_POST['inf_3_change'];
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=51");
					die("");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			// calculate exact price of requested transaction amount
			$inf_1_cost = ($price_inf_1 * $inf_1_change);
			$inf_2_cost = ($price_inf_2 * $inf_2_change);
			$inf_3_cost = ($price_inf_3 * $inf_3_change);

			$totalcost = ($inf_1_cost + $inf_2_cost + $inf_3_cost);

			// add or subtract new infantry amount from old
			$new_inf_1 = ($old_inf_1 + $inf_1_change);
			$new_inf_2 = ($old_inf_2 + $inf_2_change);
			$new_inf_3 = ($old_inf_3 + $inf_3_change);

			// subtract new treasury from old
			$newtreasury = ($treasury - $totalcost);

			// check to see if the requested purchase will bankrupt nation
			if($newtreasury <= 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=52");
			}

			// update the infantry totals to the new ones!
			$insert = "UPDATE military SET inf_1='" . $new_inf_1 . "', inf_2='" . $new_inf_2 . "', inf_3='" . $new_inf_3 . "' WHERE ID='" . $ID . "'";
			$add_military = mysql_query($insert);

			// update the treasury total to the new one!
			$insert = "UPDATE nation_variables SET treasury='" . $newtreasury . "' WHERE ID='" . $ID . "'";
			$add_treasury = mysql_query($insert);

			//then redirect them to the nation
			header("Location: purchase_infantry.php");
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='3'>Train Infantry</th>
			</tr>
			<tr>
				<td class='list_central_row_title'>Current Population:</td>
				<td class='list_central_row_title'>Max Inf Allowed:</td>
				<td class='list_central_row_title'>Current Treasury:</td>
			</tr>
				<td class='list_central_row_info'><?php echo
											$citizens = number_format($citizens,0);
											$citizens; ?></td>
				<td class='list_central_row_info'><?php echo
											$max_infantry_limit = number_format($max_infantry_limit, 0);
											$max_infantry_limit; ?></td>
				<td class='list_central_row_info'> $<?php echo
											$treasury = number_format($treasury, 2);
											$treasury; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Infantry 1 Home (Deployed)</td>
				<td class='list_central_row_title'>Infantry 2 Home (Deployed)</td>
				<td class='list_central_row_title'>Infantry 3 Home (Deployed)</td>
			</tr>
			<tr>
				<td class='list_central_row_info'><?php echo $old_inf_1 . " (" . $inf_1_deployed . ")"; ?></td>
				<td class='list_central_row_info'><?php echo $old_inf_2 . " (" . $inf_2_deployed . ")"; ?></td>
				<td class='list_central_row_info'><?php echo $old_inf_3 . " (" . $inf_3_deployed . ")"; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title' colspan='3'>Current Cost of 1 Infantry at Each Level</td>
			</tr>
			<tr>
				<td class='list_central_row_info'> $<?php echo
								$price_inf_1 = number_format($price_inf_1,2);
								$price_inf_1; ?></td>
				<td class='list_central_row_info'> $<?php echo
								$price_inf_2 = number_format($price_inf_2,2);
								$price_inf_2; ?></td>
				<td class='list_central_row_info'> $<?php echo
								$price_inf_3 = number_format($price_inf_3,2);
								$price_inf_3; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title' colspan='3'>Purchase/Dismiss Infantry</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Buy Infantry 1</td>
				<td class='list_central_row_title'>Buy Infantry 2</td>
				<td class='list_central_row_title'>Buy Infantry 3</td>
			</tr>
			<tr>
				<td class='list_central_row_info'><input type='text' name='inf_1_change' maxlength='6' value='0' /></td>
				<td class='list_central_row_info'><input type='text' name='inf_2_change' maxlength='6' value='0' /></td>
				<td class='list_central_row_info'><input type='text' name='inf_3_change' maxlength='6' value='0' /></td>
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