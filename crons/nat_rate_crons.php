<?php
/* nat_rate_crons.php */
// Resets every nation's National Rating, Economic Power & Military Power
function database_connection()
{
	// Connects to your Database
	mysql_connect("localhost", "username", "password") or die(mysql_error());
	mysql_select_db("database") or die(mysql_error());
}
database_connection();

function days_since_calculation($last_day)
{
	$right_now = gmdate('U');

	$last_day_formatted = date('z', $last_day);
	$right_now_formatted = date('z', $right_now);

	if($right_now_formatted > $last_day_formatted)
	{
		$days_since = ($right_now_formatted - $last_day_formatted);
	}
	else
	{
		$days_since = ($last_day_formatted - $right_now_formatted);
	}

	return $days_since;
}

function economic_power($infra, $tech, $capital, $land, $cw_total, $opinion, $labor_force, $res_total)
{
	$infra_value = ($infra * 10);
	$tech_value = ($tech * 5);
	$capital_value = ($capital / 2);
	$land_value = ($land * 5);
	$cw_value = ($cw_total * 100);
	$opinion_value = ($opinion * 5);
	$lf_value = ($labor_force * 3);
	$res_value = ($res_total * 50);

	$econ_total = ($infra_value + $tech_value + $capital_value + $land_value + $cw_value + $opinion_value + $lf_value + $res_value);

	return $econ_total;
}

function military_power($inf_1, $inf_2, $inf_3, $armor_1, $armor_2, $armor_3, $armor_4, $armor_5, $creation_date)
{
	$inf_1_value = ($inf_1 * 10);
	$inf_2_value = (($inf_2 * 2) * 10);
	$inf_3_value = (($inf_3 * 3) * 10);
	$armor_1_value = ($armor_1 * 15);
	$armor_2_value = (($armor_2 * 2) * 15);
	$armor_3_value = (($armor_3 * 3) * 15);
	$armor_4_value = (($armor_4 * 4) * 15);
	$armor_5_value = (($armor_5 * 5) * 15);

	$creation_date_redux = days_since_calculation($creation_date);
	$creation_value_pre = number_format($creation_date_redux, 0, '.', '');
	$creation_value = ($creation_value_pre * 2);

	$mil_total = ($inf_1_value + $inf_2_value + $inf_3_value + $armor_1_value + $armor_2_value + $armor_3_value + $armor_4_value + $armor_5_value + $creation_value);

	return $mil_total;
}

function pollution_calculation($pollution)
{
	$pollution = $pollution;

	return $pollution;
}

function public_opinion_calculation($pollution, $citizens, $inf_total)
{
	$opinion = (20 - $pollution);

	// Determine the low-end of the range of acceptable troop levels (15% of citizens)
	$pop_var_low = ($citizens * 0.15);

	// Public Opinion's Infantry Impact Calculation
	if($inf_total < $pop_var_low)
	{
		// Check to see if the Infantry level is too low
		$opinion = ($opinion / 2);
	}

	return $opinion;
}

function trade_list($ID_recip)
{
	$var_1 = 2;
	// List trades offered by others to this nation
	// Count the number of trade partners a user has
	$trade_list = mysql_query("SELECT ID_offerer, ID_trade, trade_date, trade_stat FROM resource_trade WHERE ID_recip = '$ID_recip'") or die(mysql_error());
	while($partner_list = mysql_fetch_array( $trade_list ))
	{
		$ID_offerer = stripslashes($partner_list['ID_offerer']);
		$ID_trade = stripslashes($partner_list['ID_trade']);
		$trade_date = stripslashes($partner_list['trade_date']);
		$trade_stat = stripslashes($partner_list['trade_stat']);

		// Collect the resources that correspond with  ID_offerer
		$result3 = mysql_query("SELECT resource_1, resource_2 FROM nation_variables WHERE ID = '$ID_offerer'") or die(mysql_error());
		while($res = mysql_fetch_array($result3))
		{
			$var_1++;
			$resource[$var_1] = stripslashes($res['resource_1']);
			$var_1++;
			$resource[$var_1] = stripslashes($res['resource_2']);
		}
	}

	// List trades offered by this nation to others
	// Count the number of trade partners a user has
	$trade_list = mysql_query("SELECT ID_recip, ID_trade, trade_date, trade_stat FROM resource_trade WHERE ID_offerer = '$ID_recip'") or die(mysql_error());
	while($partner_list = mysql_fetch_array( $trade_list ))
	{
		$ID_recip = stripslashes($partner_list['ID_recip']);
		$ID_trade = stripslashes($partner_list['ID_trade']);
		$trade_date = stripslashes($partner_list['trade_date']);
		$trade_stat = stripslashes($partner_list['trade_stat']);

		// Collect the resources that correspond with  ID_offerer
		$result3 = mysql_query("SELECT resource_1, resource_2 FROM nation_variables WHERE ID = '$ID_recip'") or die(mysql_error());
		while($res = mysql_fetch_array($result3))
		{
			$var_1++;
			$resource[$var_1] = stripslashes($res['resource_1']);
			$var_1++;
			$resource[$var_1] = stripslashes($res['resource_2']);
		}
	}
	$var_1++;
	$resource[$var_1] = 99;

	return $resource;
}

function resource_land_area($land_area, $resource, $cw_animal_farm)
{
	// Livestock - Increases LAND AREA by 1%.
	// Animal Farm - Increases impact of LIVESTOCK by 1% each.
	if($resource == 2)
	{
		if($cw_animal_farm == 4)
		{
			$land_area_impact = ($land_area * 1.05);
		}
		elseif($cw_animal_farm == 3)
		{
			$land_area_impact = ($land_area * 1.04);
		}
		elseif($cw_animal_farm == 2)
		{
			$land_area_impact = ($land_area * 1.03);
		}
		elseif($cw_animal_farm == 1)
		{
			$land_area_impact = ($land_area * 1.02);
		}
		else
		{
			$land_area_impact = ($land_area * 1.01);
		}
	}
	else
	{
		$land_area_impact = $land_area;
	}

	return $land_area_impact;
}

function resource_total_population($citizens, $resource1, $resource2, $cw_small_farm, $cw_dock)
{
	// Grains - Increases TOTAL POPULATION by 2%.
	// Fish - Increases TOTAL POPULATION by 1%.
	// Small Farm - Increases impact of GRAINS by 1% each.
	// Docks - Increases impact of FISH by 1% each.
	if($resource1 == 5 && $resource2 == 13 || $resource1 == 13 && $resource2 == 5)
	{
		if($cw_small_farm == 4)
		{
			if($cw_dock == 4)
			{
				$population_impact = ($citizens * 1.11);
			}
			elseif($cw_dock == 3)
			{
				$population_impact = ($citizens * 1.10);
			}
			elseif($cw_dock == 2)
			{
				$population_impact = ($citizens * 1.09);
			}
			elseif($cw_dock == 1)
			{
				$population_impact = ($citizens * 1.08);
			}
			else
			{
				$population_impact = ($citizens * 1.07);
			}
		}
		elseif($cw_small_farm == 3)
		{
			if($cw_dock == 4)
			{
				$population_impact = ($citizens * 1.10);
			}
			elseif($cw_dock == 3)
			{
				$population_impact = ($citizens * 1.09);
			}
			elseif($cw_dock == 2)
			{
				$population_impact = ($citizens * 1.08);
			}
			elseif($cw_dock == 1)
			{
				$population_impact = ($citizens * 1.07);
			}
			else
			{
				$population_impact = ($citizens * 1.06);
			}
		}
		elseif($cw_small_farm == 2)
		{
			if($cw_dock == 4)
			{
				$population_impact = ($citizens * 1.09);
			}
			elseif($cw_dock == 3)
			{
				$population_impact = ($citizens * 1.08);
			}
			elseif($cw_dock == 2)
			{
				$population_impact = ($citizens * 1.07);
			}
			elseif($cw_dock == 1)
			{
				$population_impact = ($citizens * 1.06);
			}
			else
			{
				$population_impact = ($citizens * 1.05);
			}
		}
		elseif($cw_small_farm == 1)
		{
			if($cw_dock == 4)
			{
				$population_impact = ($citizens * 1.08);
			}
			elseif($cw_dock == 3)
			{
				$population_impact = ($citizens * 1.07);
			}
			elseif($cw_dock == 2)
			{
				$population_impact = ($citizens * 1.06);
			}
			elseif($cw_dock == 1)
			{
				$population_impact = ($citizens * 1.05);
			}
			else
			{
				$population_impact = ($citizens * 1.04);
			}
		}
		else
		{
			if($cw_dock == 4)
			{
				$population_impact = ($citizens * 1.07);
			}
			elseif($cw_dock == 3)
			{
				$population_impact = ($citizens * 1.06);
			}
			elseif($cw_dock == 2)
			{
				$population_impact = ($citizens * 1.05);
			}
			elseif($cw_dock == 1)
			{
				$population_impact = ($citizens * 1.04);
			}
			else
			{
				$population_impact = ($citizens * 1.03);
			}
		}
	}
	elseif($resource1 == 5 || $resource2 == 5)
	{
		if($cw_small_farm == 4)
		{
			$population_impact = ($citizens * 1.06);
		}
		elseif($cw_small_farm == 3)
		{
			$population_impact = ($citizens * 1.05);
		}
		elseif($cw_small_farm == 2)
		{
			$population_impact = ($citizens * 1.04);
		}
		elseif($cw_small_farm == 1)
		{
			$population_impact = ($citizens * 1.03);
		}
		else
		{
			$population_impact = ($citizens * 1.02);
		}
	}
	elseif($resource1 == 13 || $resource2 == 13)
	{
		if($cw_dock == 4)
		{
			$population_impact = ($citizens * 1.05);
		}
		elseif($cw_dock == 3)
		{
			$population_impact = ($citizens * 1.04);
		}
		elseif($cw_dock == 2)
		{
			$population_impact = ($citizens * 1.03);
		}
		elseif($cw_dock == 1)
		{
			$population_impact = ($citizens * 1.02);
		}
		else
		{
			$population_impact = ($citizens * 1.01);
		}
	}
	else
	{
		$population_impact = $citizens;
	}

	return $population_impact;
}

function labor_force_calculation($citizens, $inf_total)
{
	$labor_force = ($citizens * 0.5);

	// Determine the range of acceptable troop levels (15% - 75% of citizens)
	$pop_var_low = ($citizens * 0.15);
	$pop_var_high = ($citizens * 0.75);

	// Labor Force's Infantry Impact Calculation
	if($inf_total < $pop_var_low || $inf_total > $pop_var_high)
	{
		// Check to see if the Infantry level is too low
		$labor_force = ($labor_force / 2);
	}

	return $labor_force;
}

function resource_labor_force($labor_force, $resource, $economic_power)
{
	// Fresh Water - Increases LABOR FORCE by 2%. +1% labor force increase for every 1000 economic rating up to 3000.
	if($resource == 8)
	{
		if($economic_power >= 1000 && $economic_power < 2000)
		{
			$labor_force_impact = ($labor_force * 1.03);
		}
		elseif($economic_power >= 2000 && $economic_power < 3000)
		{
			$labor_force_impact = ($labor_force * 1.04);
		}
		elseif($economic_power >= 3000)
		{
			$labor_force_impact = ($labor_force * 1.05);
		}
		else
		{
			$labor_force_impact = ($labor_force * 1.02);
		}
	}
	else
	{
		$labor_force_impact = $labor_force;
	}

	return $labor_force_impact;
}

function resource_opinion($opinion, $resource)
{
	// Precious Metals Deposits - Increases PUBLIC OPINION by 1%. Allows the construction of Regional Mints.
	if($resource == 9)
	{
		$opinion_impact = ($opinion * 1.01);
	}
	else
	{
		$opinion_impact = $opinion;
	}

	return $opinion_impact;
}

function resource_pollution($pollution, $resource, $national_rating)
{
	// Uranium - Increases POLLUTION by 4% after a nation passes 10,000 national rating. Allows nuclear weapons.
	if($resource == 14 && $national_rating >= 10000)
	{
		$pollution_impact = ($pollution * 1.04);
	}
	else
	{
		$pollution_impact = $pollution;
	}

	return $pollution_impact;
}

function citizens_calculation($infra, $land, $tech, $capital)
{
	// Citizens Calculation
	$citizens = (($infra*'10') + ($land*'2') + ($tech*'1') + ($capital*'0.5') + 100);

	return $citizens;
}

function civil_works_opinion($opinion, $cw_museum, $resource)
{
	// Museum - Increases PUBLIC OPINION by 1% each, 1.5% each with Diamond Deposits.
	// Diamonds are currently resource #7
	if($cw_museum == 4)
	{
		if($resource == 7)
		{
			$opinion_impact = ($opinion * 1.06);
		}
		else
		{
			$opinion_impact = ($opinion * 1.04);
		}
	}
	elseif($cw_museum == 3)
	{
		if($resource == 7)
		{
			$opinion_impact = ($opinion * 1.045);
		}
		else
		{
			$opinion_impact = ($opinion * 1.03);
		}
	}
	elseif($cw_museum == 2)
	{
		if($resource == 7)
		{
			$opinion_impact = ($opinion * 1.03);
		}
		else
		{
			$opinion_impact = ($opinion * 1.02);
		}
	}
	elseif($cw_museum == 1)
	{
		if($resource == 7)
		{
			$opinion_impact = ($opinion * 1.015);
		}
		else
		{
			$opinion_impact = ($opinion * 1.01);
		}
	}
	else
	{
		$opinion_impact = $opinion;
	}

	return $opinion_impact;
}

function civil_works_pollution($pollution, $cw_pipeline)
{
	// Pipelines - Increases POLLUTION by 1%. Limit 2.
	if($cw_pipeline == 2)
	{
		$pollution_impact = ($pollution * 1.02);
	}
	elseif($cw_pipeline == 1)
	{
		$pollution_impact = ($pollution * 1.01);
	}
	else
	{
		$pollution_impact = $pollution;
	}

	return $pollution_impact;
}



$cron_run_count = 0;
$cron_time_begin = gmdate("M d Y H:i:s");
// Get the user ID's to use for this loop
$user_stats = mysql_query("SELECT ID FROM users WHERE nat_exist = '1' ORDER BY ID DESC") or die(mysql_error());
while($rows = mysql_fetch_array( $user_stats ))
{
	$cron_run_count++;
	// Collect the raw data from the nations table in the db 
	$ID = stripslashes($rows['ID']);

	// Collect the nation information for display from the nations table
	$nationstats = mysql_query("SELECT creation FROM nations WHERE ID = '$ID'") or die(mysql_error());
	while($row = mysql_fetch_array( $nationstats ))
	{
		// Collect the raw data from the nations table in the db 
		$creation = stripslashes($row['creation']);
	}

	// Collect the nation information for display from the nation_variables table
	$nationstats2 = mysql_query("SELECT resource_1, resource_2, land, infra, tech, capital, pollution, citizens, nat_rate, eco_power FROM nation_variables WHERE ID = '$ID'") or die(mysql_error());
	while($row2 = mysql_fetch_array( $nationstats2 ))
	{
		// Collect the raw data from the nation_variables table in the db 
		$resource1 = stripslashes($row2['resource_1']);
		$resource2 = stripslashes($row2['resource_2']);
		$land = stripslashes($row2['land']);
		$infra = stripslashes($row2['infra']);
		$tech = stripslashes($row2['tech']);
		$capital = stripslashes($row2['capital']);
		$pollution_orig = stripslashes($row2['pollution']);
		$citizens_orig = stripslashes($row2['citizens']);
		$national_rating = stripslashes($row['nat_rate']);
		$economic_power = stripslashes($row['eco_power']);
	}

	// Collect the nation information for display from the military table
	$military = mysql_query("SELECT inf_1, inf_2, inf_3, armor_1, armor_2, armor_3, armor_4, armor_5 FROM military WHERE ID = '$ID'") or die(mysql_error());
	while($mil = mysql_fetch_array( $military ))
	{
		// Collect the raw data from the military table in the db 
		$inf_1 = stripslashes($mil['inf_1']);
		$inf_2 = stripslashes($mil['inf_2']);
		$inf_3 = stripslashes($mil['inf_3']);
		$armor_1 = stripslashes($mil['armor_1']);
		$armor_2 = stripslashes($mil['armor_2']);
		$armor_3 = stripslashes($mil['armor_3']);
		$armor_4 = stripslashes($mil['armor_4']);
		$armor_5 = stripslashes($mil['armor_5']);
	}

	// Collect the nation information for display from the deployed table
	$deploy = mysql_query("SELECT inf_1_deploy, inf_2_deploy, inf_3_deploy FROM deployed WHERE ID = '$ID'") or die(mysql_error());
	while($dep = mysql_fetch_array( $deploy ))
	{
		// Collect the raw data from the deployed table in the db 
		$inf_1_deploy = stripslashes($dep['inf_1_deploy']);
		$inf_2_deploy = stripslashes($dep['inf_2_deploy']);
		$inf_3_deploy = stripslashes($dep['inf_3_deploy']);
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
	$trade_list = trade_list($ID);
	$trade_list[1] = $resource1;
	$trade_list[2] = $resource2;
	sort($trade_list);
	$trade_list = array_unique($trade_list);
	$count = 1;

	while($trade_list[$count] != 99)
	{
		if($trade_list[$count] == 2)
		{
			$resource = $trade_list[$count];
		}
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

	// Calculate land
	$land = resource_land_area($land, $resource, $cw_ranch);

	// Calculate total infantry
	$total_inf_deployed = ($inf_1_deploy + $inf_2_deploy + $inf_3_deploy);
	$inf_total_my_nation = ($inf_1 + $inf_2 + $inf_3);
	$inf_total_home = ($inf_total_my_nation - $total_inf_deployed);

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

	$res_count = ($count - 2);
	$res_total = $res_count;

	$cw_total = ($cw_iron_mine + $cw_ranch + $cw_small_farm + $cw_dock + $cw_war_factory + $cw_pipeline + $cw_museum + $cw_regional_mint + $cw_science_inst);
	$econ_power = economic_power($infra, $tech, $capital, $land, $cw_total, $opinion, $labor_force, $res_total);
	$mil_power = military_power($inf_1, $inf_2, $inf_3, $armor_1, $armor_2, $armor_3, $armor_4, $armor_5, $creation);
	$nat_rating = ($mil_power + $econ_power);

	// Update the ratings
	$insert = "UPDATE nation_variables SET nat_rate='" . $nat_rating . "', eco_power='" . $econ_power . "', mil_power='" . $mil_power . "' WHERE ID='" . $ID . "'";
	$add_member = mysql_query($insert);
}

// Send an email with the confirmation of the Cron Job
$cron_time_end = gmdate("M d Y H:i:s");

$email = "pat.macmannis@gmail.com";
$subject = "nat_rate_crons.php was run";
$message = "nat_rate_crons.php was run on the iC server.
Total Rows Updated: $cron_run_count
Time Began: $cron_time_begin
Time Ended: $cron_time_end";

mail($email, $subject, $message, "From: iC Server - Automated - Cron");
?>