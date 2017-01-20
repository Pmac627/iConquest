<?php
/** tax_collection.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/collection_functions.php');
include ('functions/days_since_functions.php');
include ('functions/resource_functions.php');
include ('functions/civil_works_functions.php');
include ('functions/pollution_function.php');
include ('functions/citizens_function.php');
include ('functions/labor_force_function.php');
include ('functions/public_opinion_function.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Collect Taxes';
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
	if ($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		//otherwise they are shown the tax collection page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		//collect the nation information for display from the nation_variables table
		$nationstats = mysql_query("SELECT resource_1, resource_2, collection, treasury, land, infra, tech, capital, pollution, citizens, labor_force, opinion, last_tax, nat_rate, eco_power FROM nation_variables WHERE ID = '$ID'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			// Collect the raw data from the nation_variables table in the db
			$resource1 = stripslashes($row['resource_1']);
			$resource2 = stripslashes($row['resource_2']);
			$collection = stripslashes($row['collection']);
			$treasury = stripslashes($row['treasury']);
			$land = stripslashes($row['land']);
			$infra = stripslashes($row['infra']);
			$tech = stripslashes($row['tech']);
			$capital = stripslashes($row['capital']);
			$pollution_orig = stripslashes($row['pollution']);
			$citizens_orig = stripslashes($row['citizens']);
			$labor_force_orig = stripslashes($row['labor_force']);
			$opinion_orig = stripslashes($row['opinion']);
			$last_col = stripslashes($row['last_tax']);
			$national_rating = stripslashes($row['nat_rate']);
			$economic_power = stripslashes($row['eco_power']);
		}
		//collect the nation information for display from the nation table
		$nationstats = mysql_query("SELECT tax_rate FROM nations WHERE ID = '$ID'") or die(mysql_error());
		while($row2 = mysql_fetch_array( $nationstats ))
		{
			// Collect the raw data from the nations table in the db
			$tax_rate = stripslashes($row2['tax_rate']);
		}
		//collect the nation information for display from the military table
		$military = mysql_query("SELECT inf_1, inf_2, inf_3 FROM military WHERE ID = '$ID'") or die(mysql_error());
		while($mil = mysql_fetch_array( $military ))
		{
			// Collect the raw data from the military table in the db
			$inf_1 = stripslashes($mil['inf_1']);
			$inf_2 = stripslashes($mil['inf_2']);
			$inf_3 = stripslashes($mil['inf_3']);
		}
		//collect the nation information for display from the deployed table
		$deploy = mysql_query("SELECT inf_1_deploy, inf_2_deploy, inf_3_deploy FROM deployed WHERE ID = '$ID'") or die(mysql_error());
		while($dep = mysql_fetch_array( $deploy ))
		{
			// Collect the raw data from the deployed table in the db 
			$inf_1_deploy = stripslashes($dep['inf_1_deploy']);
			$inf_2_deploy = stripslashes($dep['inf_2_deploy']);
			$inf_3_deploy = stripslashes($dep['inf_3_deploy']);
		}
		//collect the nation information for display from the civil_works table
		$civil = mysql_query("SELECT small_farm, dock, pipeline, museum, regional_mint FROM civil_works WHERE ID = '$ID'") or die(mysql_error());
		while($cw = mysql_fetch_array( $civil ))
		{
			// Collect the raw data from the civil_works table in the db 
			$cw_small_farm = stripslashes($cw['small_farm']);
			$cw_dock = stripslashes($cw['dock']);
			$cw_pipeline = stripslashes($cw['pipeline']);
			$cw_museum = stripslashes($cw['museum']);
			$cw_regional_mint = stripslashes($cw['regional_mint']);
		}

		//Calculate total infantry
		$inf_total = ($inf_1 + $inf_2 + $inf_3);
		$total_inf_deployed = ($inf_1_deploy + $inf_2_deploy + $inf_3_deploy);
		$inf_total_home = ($inf_total - $total_inf_deployed);

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
				$resource4 = $trade_list[$count];
			}
			if($trade_list[$count] == 7)
			{
				$resource5 = $trade_list[$count];
			}
			if($trade_list[$count] == 8)
			{
				$resource6 = $trade_list[$count];
			}
			if($trade_list[$count] == 9)
			{
				$resource7 = $trade_list[$count];
			}
			if($trade_list[$count] == 13)
			{
				$resource8 = $trade_list[$count];
			}
			if($trade_list[$count] == 14)
			{
				$resource9 = $trade_list[$count];
			}
		$count++;
		}

		// Run the pollution calculation
		$pollution = pollution_calculation($pollution_orig);
		$pollution = resource_pollution($pollution, $resource9, $national_rating);
		$pollution = civil_works_pollution($pollution, $cw_pipeline);

		// Run the citizens calculation
		$citizens = citizens_calculation($infra, $land, $tech, $capital, $citizens_orig);
		$citizens = resource_total_population($citizens, $resource4, $resource8, $cw_small_farm, $cw_dock);

		// Run the labor_force calculation
		$labor_force = labor_force_calculation($citizens, $inf_total_home);
		$labor_force = resource_labor_force($labor_force, $resource6, $economic_power);

		// Run public opinion calculation		
		$opinion = public_opinion_calculation($pollution, $citizens, $inf_total_home);
		$opinion = resource_opinion($opinion, $resource7);
		$opinion = civil_works_opinion($opinion, $cw_museum, $resource5);

		// Run collection calculation
		$collection = tax_calculation($collection_orig, $labor_force, $opinion, $tax_rate);
		$collection = resource_collection($collection, $resource5);
		$collection = civil_works_collection($collection, $cw_regional_mint);

		// Run the days_since function (no time) on collection
		$days_diff = days_since_calculation($last_col);
		$days_diff = number_format($days_diff, 0);

		// calculate exact collection times the number of days uncollected
		$tax_collection = ($collection * $days_diff);

		//if tax collection form is submitted
		if (isset($_POST['submit']))
		{
			// subtract new treasury from old
			$newtreasury = ($treasury + $tax_collection);

			// update Last Collection timestamp
			$date_update = gmdate('U');

			// update the treasury total to the new one!
			$insert = "UPDATE nation_variables SET treasury='" . $newtreasury . "', last_tax='" . $date_update . "' WHERE ID='" . $ID . "'";
			$add_member = mysql_query($insert);

			//then redirect them to the nation
			header("Location: nation.php?ID=$ID");
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='2'>Tax Collection</th>
			</tr>
			<tr>
				<td class='list_central_row_title' width='50%'>Treasury:</td>
				<td class='list_central_row_info'>$<?php
							$treasury = number_format($treasury,2);
							echo $treasury; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Daily Tax Collection:</td>
				<td class='list_central_row_info'>$<?php
							$collection = number_format($collection,2);
							echo $collection; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Accumulated Tax Collection:</td>
				<td class='list_central_row_info'>$<?php
							$tax_collection = number_format($tax_collection,2);
							echo $tax_collection; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Days Uncollected:</td>
				<td class='list_central_row_info'><?php echo $days_diff; ?></td>
			</tr>
			<?php 
			if($days_diff > 0)
			{
				echo "  <tr>
							<td class='button' colspan='2'><input type='submit' name='submit' value='Collect!' /></td>
						</tr>";
			}
			else
			{
				echo "  <tr>
							<td class='list_central_nav' colspan='2'>You have already collected taxes today.  Try again tomorrow!</td>
						</tr>";
			}
			?>
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