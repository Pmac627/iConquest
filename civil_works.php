<?php
/** civil_works.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/citizens_function.php');
include ('functions/resource_functions.php');
include ('functions/civil_works_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/pollution_function.php');
include ('functions/labor_force_function.php');
include ('functions/public_opinion_function.php');
include ('functions/econ_power_function.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Civil Works Overview';
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
		// Otherwise they are shown the infra purchase page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		// Collect the nation information for display from the nation_variables table
		$nationstats = mysql_query("SELECT infra, tech, capital, land, citizens, pollution, resource_1, resource_2, treasury, nat_rate FROM nation_variables WHERE ID = '$ID'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			// Collect the raw data from the nation_variables table in the db
			$infra = stripslashes($row['infra']);
			$tech = stripslashes($row['tech']);
			$capital = stripslashes($row['capital']);
			$land = stripslashes($row['land']);
			$citizens_orig = stripslashes($row['citizens']);
			$pollution_orig = stripslashes($row['pollution']);
			$resource1 = stripslashes($row['resource_1']);
			$resource2 = stripslashes($row['resource_2']);
			$treasury = stripslashes($row['treasury']);
			$national_rating = stripslashes($row['nat_rate']);
		}

		// Collect the nation information for display from the military table
		$military = mysql_query("SELECT inf_1, inf_2, inf_3 FROM military WHERE ID = '$ID'") or die(mysql_error());
		while($mil = mysql_fetch_array( $military ))
		{
			// Collect the raw data from the military table in the db 
			$inf_1 = stripslashes($mil['inf_1']);
			$inf_2 = stripslashes($mil['inf_2']);
			$inf_3 = stripslashes($mil['inf_3']);
		}

		// Collect the nation information for display from the civil_works table
		$civil = mysql_query("SELECT iron_mine, ranch, small_farm, dock, war_factory, pipeline, museum, regional_mint, science_inst FROM civil_works WHERE ID = '$ID'") or die(mysql_error());
		while($cw = mysql_fetch_array( $civil ))
		{
			// Collect the raw data from the civil_works table in the db 
			$cw_iron_mine = stripslashes($cw['iron_mine']);
			$cw_ranch = stripslashes($cw['ranch']);
			$cw_small_farm = stripslashes($cw['small_farm']);
			$cw_dock = stripslashes($cw['dock']);
			$cw_war_factory = stripslashes($cw['war_factory']);
			$cw_pipeline = stripslashes($cw['pipeline']);
			$cw_museum = stripslashes($cw['museum']);
			$cw_regional_mint = stripslashes($cw['regional_mint']);
			$cw_science_inst = stripslashes($cw['science_inst']);
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
			if($trade_list[$count] == 9)
			{
				$resource3 = $trade_list[$count];
			}
			if($trade_list[$count] == 15)
			{
				$resource4 = $trade_list[$count];
			}
			if($trade_list[$count] == 5)
			{
				$resource5 = $trade_list[$count];
			}
			if($trade_list[$count] == 13)
			{
				$resource6 = $trade_list[$count];
			}
			if($trade_list[$count] == 7)
			{
				$resource7 = $trade_list[$count];
			}
			if($trade_list[$count] == 8)
			{
				$resource8 = $trade_list[$count];
			}
			if($trade_list[$count] == 14)
			{
				$resource9 = $trade_list[$count];
			}
		$count++;
		}

		$inf_total = ($inf_1 + $inf_2 + $inf_3);

		// Run the pollution calculation
		$pollution = pollution_calculation($pollution_orig);
		$pollution = resource_pollution($pollution, $resource9, $national_rating);
		$pollution = civil_works_pollution($pollution, $cw_pipeline);

		// Run the citizens calculation
		$citizens = citizens_calculation($infra, $land, $tech, $capital, $citizens_orig);
		$citizens = resource_total_population($citizens, $resource5, $resource6, $cw_small_farm, $cw_dock);

		// Run the labor_force calculation
		$labor_force = labor_force_calculation($citizens, $inf_total);
		$labor_force = resource_labor_force($labor_force, $resource8, $economic_power);
		$labor_force = number_format($labor_force,0,'.','');

		// Run public opinion calculation		
		$opinion = public_opinion_calculation($pollution, $citizens, $inf_total);
		$opinion = resource_opinion($opinion, $resource7);
		$opinion = civil_works_opinion($opinion, $cw_museum, $resource7);

		$res_count = ($count - 2);
		$res_total = $res_count;

		$cw_total = ($cw_iron_mine + $cw_ranch + $cw_small_farm + $cw_dock + $cw_war_factory + $cw_pipeline + $cw_museum + $cw_regional_mint + $cw_science_inst);
		$econ_power = economic_power($infra, $tech, $capital, $land, $cw_total, $opinion, $labor_force, $res_total);

		$cw_limit_raw = ($econ_power / 1000);
		$cw_limit_cut = number_format($cw_limit_raw, 0, '.', '');
		if($cw_limit_cut > $cw_limit_raw)
		{
			$cw_limit = ($cw_limit_cut - 1);
		}
		else
		{
			$cw_limit = $cw_limit_cut;
		}

		$cw_limit_reach = ($cw_limit - $cw_total);

		$cw_price = 100000;
		$cw_price = resource_cw_purchase($cw_price, $resource4);
		$cw_price = civil_works_cw_cost($cw_price, $cw_iron_mine);
		$cw_iron_mine_cost = $cw_price;
		$cw_ranch_cost = $cw_price;
		$cw_small_farm_cost = $cw_price;
		$cw_dock_cost = $cw_price;
		$cw_war_factory_cost = $cw_price;
		$cw_pipeline_cost = $cw_price;
		$cw_museum_cost = $cw_price;
		$cw_regional_mint_cost = $cw_price;
		$cw_science_inst_cost = $cw_price;

		if($cw_iron_mine < 4 && $cw_limit_reach > 0)
		{
			$cw_iron_mine_fubar = "<a href='civil_works_purchase.php?cw=iron_mine'>Purchase</a>";
		}
		else
		{
			$cw_iron_mine_fubar = "N/A";
		}

		if($cw_ranch < 4 && $cw_limit_reach > 0)
		{
			$cw_ranch_fubar = "<a href='civil_works_purchase.php?cw=ranch'>Purchase</a>";
		}
		else
		{
			$cw_ranch_fubar = "N/A";
		}

		if($cw_small_farm < 4 && $cw_limit_reach > 0)
		{
			$cw_small_farm_fubar = "<a href='civil_works_purchase.php?cw=small_farm'>Purchase</a>";
		}
		else
		{
			$cw_small_farm_fubar = "N/A";
		}

		if($cw_dock < 4 && $cw_limit_reach > 0)
		{
			$cw_dock_fubar = "<a href='civil_works_purchase.php?cw=dock'>Purchase</a>";
		}
		else
		{
			$cw_dock_fubar = "N/A";
		}

		if($cw_war_factory < 1 && $cw_limit_reach > 0)
		{
			$cw_war_factory_fubar = "<a href='civil_works_purchase.php?cw=war_factory'>Purchase</a>";
		}
		else
		{
			$cw_war_factory_fubar = "N/A";
		}

		if($cw_pipeline < 2 && $cw_limit_reach > 0)
		{
			$cw_pipeline_fubar = "<a href='civil_works_purchase.php?cw=pipeline'>Purchase</a>";
		}
		else
		{
			$cw_pipeline_fubar = "N/A";
		}

		if($cw_museum < 4 && $cw_limit_reach > 0)
		{
			$cw_museum_fubar = "<a href='civil_works_purchase.php?cw=museum'>Purchase</a>";
		}
		else
		{
			$cw_museum_fubar = "N/A";
		}

		if($cw_regional_mint < 4 && $cw_limit_reach > 0 && $resource3 == 9)
		{
			$cw_regional_mint_fubar = "<a href='civil_works_purchase.php?cw=regional_mint'>Purchase</a>";
		}
		else
		{
			$cw_regional_mint_fubar = "N/A";
		}

		if($cw_science_inst < 4 && $cw_limit_reach > 0)
		{
			$cw_science_inst_fubar = "<a href='civil_works_purchase.php?cw=science_inst'>Purchase</a>";
		}
		else
		{
			$cw_science_inst_fubar = "N/A";
		}

		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='3'>Purchase Civil Works</th>
			</tr>
			<tr>
				<td class='list_central_row_title'>Current Economic Power:</td>
				<td class='list_central_row_title'>Treasury:</td>
				<td class='list_central_row_title'>Total Civil Works Allowed:<br /><em>(Remaining Allowance:)</em></td>
			</tr>
			<tr>
				<td class='list_central_row_info'><?php 
														$econ_power_blah = number_format($econ_power, 3);
														echo $econ_power_blah; ?></td>
				<td class='list_central_row_info'>$<?php 
														$treasury_blah = number_format($treasury, 2);
														echo $treasury_blah; ?></td>
				<td class='list_central_row_info'><?php echo $cw_limit; ?> (<?php echo $cw_limit_reach; ?>)</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Iron Mine (<?php echo $cw_iron_mine; ?>)</td>
				<td class='list_central_row_info'>$<?php 
														$cw_iron_mine_cost_blah = number_format($cw_iron_mine_cost, 2);
														echo $cw_iron_mine_cost_blah; ?></td>
				<td class='list_central_row_info'><?php echo $cw_iron_mine_fubar; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Ranch (<?php echo $cw_ranch; ?>)</td>
				<td class='list_central_row_info'>$<?php 
														$cw_ranch_cost_blah = number_format($cw_ranch_cost, 2);
														echo $cw_ranch_cost_blah; ?></td>
				<td class='list_central_row_info'><?php echo $cw_ranch_fubar; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Small Farm (<?php echo $cw_small_farm; ?>)</td>
				<td class='list_central_row_info'>$<?php 
														$cw_small_farm_cost_blah = number_format($cw_small_farm_cost, 2);
														echo $cw_small_farm_cost_blah; ?></td>
				<td class='list_central_row_info'><?php echo $cw_small_farm_fubar; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Dock (<?php echo $cw_dock; ?>)</td>
				<td class='list_central_row_info'>$<?php 
														$cw_dock_cost_blah = number_format($cw_dock_cost, 2);
														echo $cw_dock_cost_blah; ?></td>
				<td class='list_central_row_info'><?php echo $cw_dock_fubar; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>War Factory (<?php echo $cw_war_factory; ?>)</td>
				<td class='list_central_row_info'>$<?php 
														$cw_war_factory_cost_blah = number_format($cw_war_factory_cost, 2);
														echo $cw_war_factory_cost_blah; ?></td>
				<td class='list_central_row_info'><?php echo $cw_war_factory_fubar; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Pipeline (<?php echo $cw_pipeline; ?>)</td>
				<td class='list_central_row_info'>$<?php 
														$cw_pipeline_cost_blah = number_format($cw_pipeline_cost, 2);
														echo $cw_pipeline_cost_blah; ?></td>
				<td class='list_central_row_info'><?php echo $cw_pipeline_fubar; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Museum (<?php echo $cw_museum; ?>)</td>
				<td class='list_central_row_info'>$<?php 
														$cw_museum_cost_blah = number_format($cw_museum_cost, 2);
														echo $cw_museum_cost_blah; ?></td>
				<td class='list_central_row_info'><?php echo $cw_museum_fubar; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Regional Mint (<?php echo $cw_regional_mint; ?>)</td>
				<td class='list_central_row_info'>$<?php 
														$cw_regional_mint_cost_blah = number_format($cw_regional_mint_cost, 2);
														echo $cw_regional_mint_cost_blah; ?></td>
				<td class='list_central_row_info'><?php echo $cw_regional_mint_fubar; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Science Institute (<?php echo $cw_science_inst; ?>)</td>
				<td class='list_central_row_info'>$<?php 
														$cw_science_inst_cost_blah = number_format($cw_science_inst_cost, 2);
														echo $cw_science_inst_cost_blah; ?></td>
				<td class='list_central_row_info'><?php echo $cw_science_inst_fubar; ?></td>
			</tr>
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
	// If the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>