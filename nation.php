<?php
/** nation.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/bills_functions.php');
include ('functions/collection_functions.php');
include ('functions/days_since_functions.php');
include ('functions/alerts_functions.php');
include ('functions/resource_functions.php');
include ('functions/civil_works_functions.php');
include ('functions/flag_to_image.php');
include ('functions/land_type_name.php');
include ('functions/zone_name.php');
include ('functions/poli_sci_name.php');
include ('functions/currency_name.php');
include ('functions/ethnicity_name.php');
include ('functions/language_name.php');
include ('functions/creed_name.php');
include ('functions/peace_war_name.php');
include ('functions/pollution_function.php');
include ('functions/citizens_function.php');
include ('functions/labor_force_function.php');
include ('functions/public_opinion_function.php');
include ('functions/nation_paragraph_function.php');
include ('functions/form_input_check_functions.php');
include ('functions/budget_balance_function.php');
include ('functions/econ_power_function.php');
include ('functions/mil_power_function.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
// $page_title_name is determined with $var_ID approximately 60 lines down

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$pass = $_COOKIE['Key_i_Conquest'];
	$check = mysql_query("SELECT password, mod_admin FROM users WHERE username = '$username'")or die(mysql_error());
	while($info = mysql_fetch_array( $check ))
	{
		$info_pass = $info['password'];
		$mod_admin = $info['mod_admin'];
	}

	// If the cookie has the wrong password, they are taken to the expired session login page
	if ($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		// Update cookie time
		$hour = time() + 3600;
		setcookie(ID_i_Conquest, $username, $hour);
		setcookie(Key_i_Conquest, $pass, $hour);

		// Otherwise they are shown the nation page

		// Pull the desired ID variable from the URL for page display determination
		$var_ID = $_GET['ID'];

		if(sanity_check($var_ID, 'numeric', 11) != FALSE)
		{
			// Collect the nation ID that corresponds with the username
			$result = mysql_query("SELECT ID FROM users WHERE username = '$username'") or die(mysql_error());
			$IDcheck = mysql_fetch_array($result) or die(mysql_error());
			$ID = $IDcheck['ID'];

			$check3 = mysql_query("SELECT ID FROM users WHERE ID = '$var_ID'")
			or die(mysql_error());
			$check4 = mysql_num_rows($check3);

			// Determine if we are viewing someone's nation or the users nation
			if($check4 == 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=37");
			}
			elseif($var_ID == $ID)
			{
				$ID_use = $ID;
				$page_title_name = 'My Nation Overview';
			}
			else
			{
				$ID_use = $var_ID;
				$page_title_name = 'Foreign Nation Overview';
			}
		}
		else
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=37");
		}

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		// Collect the nation information for display from the nations table
		$user_stats = mysql_query("SELECT username FROM users WHERE ID = '$ID_use'") or die(mysql_error());
		while($user = mysql_fetch_array( $user_stats ))
		{
			// Collect the raw data from the users table in the db 
			$leader = stripslashes($user['username']);
		}

		// Collect the nation information for display from the nations table
		$nationstats = mysql_query("SELECT nation, region, capitol, title, creation, tax_rate, land_type, zone, poli_sci, currency, ethnicity, language, creed, peace_war, flag FROM nations WHERE ID = '$ID_use'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			// Collect the raw data from the nations table in the db 
			$nation = stripslashes($row['nation']);
			$region = stripslashes($row['region']);
			$capitol = stripslashes($row['capitol']);
			$title = stripslashes($row['title']);
			$creation = stripslashes($row['creation']);
			$tax_rate = stripslashes($row['tax_rate']);
			$land_type = stripslashes($row['land_type']);
			$zone = stripslashes($row['zone']);
			$poli_sci = stripslashes($row['poli_sci']);
			$currency = stripslashes($row['currency']);
			$ethnicity = stripslashes($row['ethnicity']);
			$language = stripslashes($row['language']);
			$creed = stripslashes($row['creed']);
			$peace_war = stripslashes($row['peace_war']);
			$flag = stripslashes($row['flag']);
		}

		// Collect the nation information for display from the nation_variables table
		$nationstats2 = mysql_query("SELECT resource_1, resource_2, treasury, collection, bills, land, infra, tech, capital, labor_force, pollution, last_bill, last_tax, history, nat_rate, eco_power, mil_power FROM nation_variables WHERE ID = '$ID_use'") or die(mysql_error());
		while($row2 = mysql_fetch_array( $nationstats2 ))
		{
			// Collect the raw data from the nation_variables table in the db 
			$resource1 = stripslashes($row2['resource_1']);
			$resource2 = stripslashes($row2['resource_2']);
			$treasury = stripslashes($row2['treasury']);
			$collection_orig = stripslashes($row2['collection']);
			$bills_orig = stripslashes($row2['bills']);
			$land = stripslashes($row2['land']);
			$infra = stripslashes($row2['infra']);
			$tech = stripslashes($row2['tech']);
			$capital = stripslashes($row2['capital']);
			$labor_force_orig = stripslashes($row2['labor_force']);
			$pollution_orig = stripslashes($row2['pollution']);
			$last_col_bill = stripslashes($row2['last_bill']);
			$last_col_tax = stripslashes($row2['last_tax']);
			$history = stripslashes($row2['history']);
			$national_rating = stripslashes($row['nat_rate']);
			$economic_power = stripslashes($row['eco_power']);
			$military_power = stripslashes($row['mil_power']);
		}

		// Collect the nation information for display from the military table
		$military = mysql_query("SELECT inf_1, inf_2, inf_3, inf_loss, armor_1, armor_2, armor_3, armor_4, armor_5, armor_loss FROM military WHERE ID = '$ID_use'") or die(mysql_error());
		while($mil = mysql_fetch_array( $military ))
		{
			// Collect the raw data from the military table in the db 
			$inf_1 = stripslashes($mil['inf_1']);
			$inf_2 = stripslashes($mil['inf_2']);
			$inf_3 = stripslashes($mil['inf_3']);
			$inf_losses = stripslashes($mil['inf_loss']);
			$armor_1 = stripslashes($mil['armor_1']);
			$armor_2 = stripslashes($mil['armor_2']);
			$armor_3 = stripslashes($mil['armor_3']);
			$armor_4 = stripslashes($mil['armor_4']);
			$armor_5 = stripslashes($mil['armor_5']);
			$armor_losses = stripslashes($mil['armor_loss']);
		}

		// Collect the nation information for display from the deployed table
		$deploy = mysql_query("SELECT inf_1_deploy, inf_2_deploy, inf_3_deploy, armor_1_deploy, armor_2_deploy, armor_3_deploy, armor_4_deploy, armor_5_deploy FROM deployed WHERE ID = '$ID_use'") or die(mysql_error());
		while($dep = mysql_fetch_array( $deploy ))
		{
			// Collect the raw data from the deployed table in the db 
			$inf_1_deploy = stripslashes($dep['inf_1_deploy']);
			$inf_2_deploy = stripslashes($dep['inf_2_deploy']);
			$inf_3_deploy = stripslashes($dep['inf_3_deploy']);
			$armor_1_deploy = stripslashes($dep['armor_1_deploy']);
			$armor_2_deploy = stripslashes($dep['armor_2_deploy']);
			$armor_3_deploy = stripslashes($dep['armor_3_deploy']);
			$armor_4_deploy = stripslashes($dep['armor_4_deploy']);
			$armor_5_deploy = stripslashes($dep['armor_5_deploy']);
		}

		// Collect the nation information for display from the civil_works table
		$civil = mysql_query("SELECT iron_mine, ranch, small_farm, dock, war_factory, pipeline, museum, regional_mint, science_inst FROM civil_works WHERE ID = '$ID_use'") or die(mysql_error());
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

		$total_inf_deployed = ($inf_1_deploy + $inf_2_deploy + $inf_3_deploy);
		$total_armor_deployed = ($armor_1_deploy + $armor_2_deploy + $armor_3_deploy + $armor_4_deploy + $armor_5_deploy);

		// Collect the private message information for display from the private_message table
		$message_list = mysql_query("SELECT ID_message FROM private_message WHERE ID_recip = '$ID_use' AND read_pm = '0'") or die(mysql_error());
		while($pm_list = mysql_fetch_array( $message_list ))
		{
			$new_pm_count++;
		}

		// Convert the creation Unix timestamp into a date & time
		$raw_time_creation = $creation;
		$formatted_creation = date('F jS, Y \@ g:i:s a', $raw_time_creation);

		// Convert the $last_col_tax Unix timestamp into a date & time for last update
		$raw_time_tax = $last_col_tax;
		$formatted_last_update = date('F jS, Y \@ g:i:s a', $raw_time_tax);

		if($raw_time_creation == $raw_time_tax)
		{
			$formatted_last_update = 'Never!';
		}

		// Calculate land
		$land = resource_land_area($land, $resource, $cw_ranch);

		// Calculate total infantry
		$inf_total_my_nation = ($inf_1 + $inf_2 + $inf_3);
		$inf_total = ($total_inf_deployed + $inf_1 + $inf_2 + $inf_3);
		$inf_total_home = ($inf_total_my_nation - $total_inf_deployed);

		$armor_total_my_nation = ($armor_1 + $armor_2 + $armor_3 + $armor_4 + $armor_5);
		$armor_total = ($total_armor_deployed + $armor_1 + $armor_2 + $armor_3 + $armor_4 + $armor_5);

		// Compile a master array full of ALL trades this nation has
		$trade_list = trade_list($ID_use);
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
			if($trade_list[$count] == 3)
			{
				$resource3 = $trade_list[$count];
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

		if($var_ID == $ID)
		{
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

			$bills = bills_calculation($infra, $tech, $capital, $land);
			$bills = resource_bills($bills, $resource3);
		}
		else
		{
			// Run the citizens calculation
			$citizens = citizens_calculation($infra, $land, $tech, $capital, $citizens_orig);
			$citizens = resource_total_population($citizens, $resource4, $resource8, $cw_small_farm, $cw_dock);

			// Run the labor_force calculation
			$labor_force = labor_force_calculation($citizens, $inf_total_home);
			$labor_force = resource_labor_force($labor_force, $resource6, $economic_power);
		}

		$res_count = ($count - 2);
		$res_total = $res_count;

		$cw_total = ($cw_iron_mine + $cw_ranch + $cw_small_farm + $cw_dock + $cw_war_factory + $cw_pipeline + $cw_museum + $cw_regional_mint + $cw_science_inst);
		$econ_power = economic_power($infra, $tech, $capital, $land, $cw_total, $opinion, $labor_force, $res_total);
		$mil_power = military_power($inf_1, $inf_2, $inf_3, $armor_1, $armor_2, $armor_3, $armor_4, $armor_5, $creation);
		$nat_rating = ($mil_power + $econ_power);

		// Run the days_since function (no time) on collection
		$days_diff_tax = days_since_calculation($last_col_tax);

		// Run the days_since function (no time) on bills
		$days_diff_bill = days_since_calculation($last_col_bill);

		// Get the infantry impact message, if any
		$inf_fubar = infantry_impact_message($inf_total_home, $citizens);

		// Get the new messages message, if any
		$pm_fubar = new_pm_count($new_pm_count);

		include ('header.php');

		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);

		if($var_ID == $ID)
		{
			/**************************************
			Nation Display Output if nation ID requested
			 IS the user's own nation ID.
			**************************************/
			?>
			<td>
			<table class='nation_paragraph'>
				<?php echo nation_paragraph($nation, $title, $leader, $capitol, $land_type, $zone, $region, $poli_sci, $ethnicity, $creed, $currency, $tax_rate, $peace_war, $ID_use); ?>
			</table>
			<table class='main_display'>
					<?php echo $pm_fubar; ?>
				<tr>
					<th class='nation_header' colspan='2'>General Nation Information</th>
				</tr>
				<tr>
					<td class='nation_row_title' width='175'><abbr title='National Flag: Your nation&apos;s flag or emblem'>National Flag:</abbr></td>
					<td class='nation_row_info'><?php echo flag_to_image($flag); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Nation Name: The name of your nation'>Nation Name:</abbr></td>
					<td class='nation_row_info'><?php echo $nation; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Leader: The name of your nation leader'>Leader:</abbr></td>
					<td class='nation_row_info'><?php echo $title; ?> <?php echo $leader; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Capital City: The name of the city in which your capitol resides'>Capitol City:</abbr></td>
					<td class='nation_row_info'><?php echo $capitol; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Creation: The date and time when you founded <?php echo $nation; ?>'>Creation:</abbr></td>
					<td class='nation_row_info'><?php echo $formatted_creation; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Last Update: The last time you managed your nation'>Last Update:</abbr></td>
					<td class='nation_row_info'><?php echo $formatted_last_update; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='History: The brief history of your nation'>History of <?php echo $nation; ?>:</abbr></td>
					<td class='nation_row_info'><?php echo $history; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Basic Land Type: The type of region you chose to settle on'>Basic Land Type:</abbr></td>
					<td class='nation_row_info'><?php echo land_type_name($land_type); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='National Rating: The overall statistical rating of your nation'>National Rating:</abbr></td>
					<td class='nation_row_info'><?php   $nat_rating = number_format($nat_rating, 3);
								echo $nat_rating; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Land Area: The total physical area that your nation controls'>Land Area:</abbr></td>
					<td class='nation_row_info'><?php
								$land = number_format($land,2);
								echo $land; ?> square miles</td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Region Name: The term you use to describe the subdivisions of your nation'>Region Name:</abbr></td>
					<td class='nation_row_info'><?php echo $region; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Zone: The economic zone your nation chooses to participate in'>Zone:</abbr></td>
					<td class='nation_row_info'><?php echo zone_name($zone); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Political System: The governing structure of your nation'>Political System:</abbr></td>
					<td class='nation_row_info'><?php echo poli_sci_name($poli_sci); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Primary Ethnicity: The primary ethnicity within your nation'>Primary Ethnicity:</abbr></td>
					<td class='nation_row_info'><?php echo ethnicity_name($ethnicity); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Primary Language: The primary language spoken within your nation'>Primary Language:</abbr></td>
					<td class='nation_row_info'><?php echo language_name($language); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Primary Creed: The primary religion followed within your nation'>Primary Creed:</abbr></td>
					<td class='nation_row_info'><?php echo creed_name($creed); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Peace/War Setting: Whether or not you find war acceptable'>Peace/War Setting:</abbr></td>
					<td class='nation_row_info'><?php echo peace_war_image($peace_war); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Pollution: How badly your nation pollutes'>Pollution:</abbr></td>
					<td class='nation_row_info'><?php
								$pollution = number_format($pollution,2);
								echo $pollution; ?></td>
				</tr>
				<tr>
					<th class='nation_header' colspan='2'>Economic Information</th>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Economic Power: The statistical rating of your nation&apos;s economic power'>Economic Power:</abbr></td>
					<td class='nation_row_info'><?php   $econ_power = number_format($econ_power, 3);
								echo $econ_power; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Infrastructure: The unit value of your national infrastructure'>Infrastructure:</abbr></td>
					<td class='nation_row_info'><?php
								$infra = number_format($infra,2);
								echo $infra; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Technology: The unit value of your national technological abilities'>Technology:</abbr></td>
					<td class='nation_row_info'><?php
								$tech = number_format($tech,2);
								echo $tech; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Capitol: The unit value of your national ability to use assets'>Capital:</abbr></td>
					<td class='nation_row_info'><?php
								$capital = number_format($capital,2);
								echo $capital; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Tax Rate: How much you collect per working citizen in taxes'>Tax Rate:</abbr></td>
					<td class='nation_row_info'><?php echo $tax_rate; ?>%</td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Official Currency: The monitary unit used within your nation'>Official Currency:</abbr></td>
					<td class='nation_row_info'><?php echo currency_name($currency); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Collection: The estimated tax collection for this day'>Collection:</abbr></td>
					<td class='nation_row_info'><?php echo collection_output($days_diff_tax, $collection); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Bills: The estimated bill payment for this day'>Bills:</abbr></td>
					<td class='nation_row_info'><?php echo bills_output($days_diff_bill, $bills); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='National Budget Balance: The estimated +/- for this day'>National Budget Balance:</abbr></td>
					<td class='nation_row_info'><?php echo budget_balance_output($collection, $bills, $days_diff_tax, $days_diff_bill); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Treasury: The current government balance'>Treasury:</abbr></td>
					<td class='nation_row_info'><?php
								$treasury = number_format($treasury,2);
								echo "\$" . $treasury; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Civil Works: The improvements made to your nation'>Civil Works:</abbr></td>
					<td class='nation_row_info'><?php echo display_civil_works($cw_iron_mine, $cw_ranch, $cw_small_farm, $cw_dock, $cw_war_factory, $cw_pipeline, $cw_museum, $cw_regional_mint, $cw_science_inst); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Citizens: The total number of souls under your control'>Citizens:</abbr></td>
					<td class='nation_row_info'><?php   $citizens = number_format($citizens,0);
								echo $citizens; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Labor Force: The total number of working citizens'>Labor Force:</abbr></td>
					<td class='nation_row_info'><?php   $labor_force = number_format($labor_force,0);
								echo $labor_force; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Public Opinion: How much your people like you'>Public Opinion:</abbr></td>
					<td class='nation_row_info'><?php   $opinion = number_format($opinion,2);
								echo $opinion; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='<?php echo $nation; ?>&apos;s Resources: Your nation&apos;s domestic product'><?php echo $nation; ?>'s Resources:</abbr></td>
					<td class='nation_row_info'><?php echo res_to_image($resource1) . " " . res_to_image($resource2); ?></td>
				</tr>
				<tr>
					<th class='nation_header' colspan='2'>International Relations</th>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Imported Resources: The goods and products imported into your nation'>Imported Resources:</abbr></td>
					<td class='nation_row_info'><?php   $count = 0;
								while($trade_list[$count] != 99)
								{
									echo res_to_image($trade_list[$count]) . " ";
									$count++;
								} ?></td>
				</tr>
				<tr>
					<th class='nation_header' colspan='2'>Military Information</th>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Military Power: The statistical rating of your nation&apos;s military power'>Military Power:</abbr></td>
					<td class='nation_row_info'><?php   $mil_power = number_format($mil_power, 3);
								echo $mil_power; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Infantry: The total number of infantry at home and deployed'>Infantry (<em>deployed</em>):</abbr></td>
					<td class='nation_row_info'><?php echo $inf_total_my_nation . " (" . $total_inf_deployed . ")" . $inf_fubar; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Armor: The total number of armor units at home and deployed'>Armor (<em>deployed</em>):</abbr></td>
					<td class='nation_row_info'><?php echo $armor_total_my_nation . " (" . $total_armor_deployed . ")"; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Infantry Casualties: The total number of infantry and armor lost in combat'>Infantry Casualties (Armor Losses):</abbr></td>
					<td class='nation_row_info'><?php echo $inf_losses . " (" . $armor_losses . ")"; ?></td>
				</tr>
			</table>
			</td>
			</tr>
			</table>
			<?php
			include ('footer.php');
		}
		else
		{
			/**************************************
			Nation Display Output if nation ID requested
			 is NOT the user's own nation ID.
			**************************************/
			?>
			<td>
			<table class='nation_paragraph'>
				<?php echo nation_paragraph($nation, $title, $leader, $capitol, $land_type, $zone, $region, $poli_sci, $ethnicity, $creed, $currency, $tax_rate, $peace_war, $ID_use); ?>
			</table>
			<table class='main_display'>
				<tr>
					<th class='nation_header' colspan='2'>General Nation Information</th>
				</tr>
				<tr>
					<td class='nation_row_title' width='175'><abbr title='National Flag: Their nation&apos;s flag or emblem'>National Flag:</abbr></td>
					<td class='nation_row_info'><?php echo flag_to_image($flag); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Nation Name: The name of their nation'>Nation Name:</abbr></td>
					<td class='nation_row_info'><?php echo $nation; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Leader: The name of their nation leader'>Leader:</abbr></td>
					<td class='nation_row_info'><?php echo $title; ?> <?php echo $leader; ?> | <a class='link_inline' href='pm_send?to=<?php echo $leader; ?>' title='Send a Message to <?php echo $leader; ?>'>Send a Message</a></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Capital City: The name of the city in which their capitol resides'>Capitol City:</abbr></td>
					<td class='nation_row_info'><?php echo $capitol; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Creation: The date and time when they founded <?php echo $nation; ?>'>Creation:</abbr></td>
					<td class='nation_row_info'><?php echo $formatted_creation; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Last Update: The last time they managed their nation'>Last Update:</abbr></td>
					<td class='nation_row_info'><?php echo $formatted_last_update; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='History: The brief history of their nation'>History of <?php echo $nation; ?>:</abbr></td>
					<td class='nation_row_info'><?php echo $history; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Basic Land Type: The type of region they chose to settle on'>Basic Land Type:</abbr></td>
					<td class='nation_row_info'><?php echo land_type_name($land_type); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='National Rating: The overall statistical rating of their nation'>National Rating:</abbr></td>
					<td class='nation_row_info'><?php   $nat_rating = number_format($nat_rating, 3);
								echo $nat_rating; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Land Area: The total physical area that their nation controls'>Land Area:</abbr></td>
					<td class='nation_row_info'><?php
								$land = number_format($land,2);
								echo $land; ?> square miles</td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Region Name: The term they use to describe the subdivisions of their nation'>Region Name:</abbr></td>
					<td class='nation_row_info'><?php echo $region; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Zone: The economic zone their nation chooses to participate in'>Zone:</abbr></td>
					<td class='nation_row_info'><?php echo zone_name($zone); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Political System: The governing structure of their nation'>Political System:</abbr></td>
					<td class='nation_row_info'><?php echo poli_sci_name($poli_sci); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Primary Ethnicity: The primary ethnicity within their nation'>Primary Ethnicity:</abbr></td>
					<td class='nation_row_info'><?php echo ethnicity_name($ethnicity); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Primary Language: The primary language spoken within their nation'>Primary Language:</abbr></td>
					<td class='nation_row_info'><?php echo language_name($language); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Primary Creed: The primary religion followed within their nation'>Primary Creed:</abbr></td>
					<td class='nation_row_info'><?php echo creed_name($creed); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Peace/War Setting: Whether or not they find war acceptable'>Peace/War Setting:</abbr></td>
					<td class='nation_row_info'><?php echo peace_war_image($peace_war); ?></td>
				</tr>
				<tr>
					<th class='nation_header' colspan='2'>Economic Information</th>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Infrastructure: The unit value of their national infrastructure'>Infrastructure:</abbr></td>
					<td class='nation_row_info'><?php
								$infra = number_format($infra,2);
								echo $infra; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Technology: The unit value of their national technological abilities'>Technology:</abbr></td>
					<td class='nation_row_info'><?php
								$tech = number_format($tech,2);
								echo $tech; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Capitol: The unit value of their national ability to use assets'>Capital:</abbr></td>
					<td class='nation_row_info'><?php
								$capital = number_format($capital,2);
								echo $capital; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Tax Rate: How much they collect per working citizen in taxes'>Tax Rate:</abbr></td>
					<td class='nation_row_info'><?php echo $tax_rate; ?>%</td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Official Currency: The monitary unit used within their nation'>Official Currency:</abbr></td>
					<td class='nation_row_info'><?php echo currency_name($currency); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Civil Works: The improvements made to their nation'>Civil Works:</abbr></td>
					<td class='nation_row_info'><?php echo display_civil_works($cw_iron_mine, $cw_ranch, $cw_small_farm, $cw_dock, $cw_war_factory, $cw_pipeline, $cw_museum, $cw_regional_mint, $cw_science_inst); ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Citizens: The total number of souls under their control'>Citizens:</abbr></td>
					<td class='nation_row_info'><?php   $citizens = number_format($citizens,0);
								echo $citizens; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Labor Force: The total number of working citizens'>Labor Force:</abbr></td>
					<td class='nation_row_info'><?php   $labor_force = number_format($labor_force,0);
								echo $labor_force; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='<?php echo $nation; ?>&apos;s Resources: Their nation&apos;s domestic product'><?php echo $nation; ?>'s Resources:</abbr></td>
					<td class='nation_row_info'><?php echo res_to_image($resource1) . " " . res_to_image($resource2); ?></td>
				</tr>
				<tr>
					<th class='nation_header' colspan='2'>International Relations</th>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='International Options: How you can interact with their nation'>International Options:</abbr></td>
					<td class='nation_row_info'><a class='link_inline' href='trade_offer.php?ID=<?php echo $var_ID; ?>' title='Send a Trade Offer to <?php echo $leader; ?>'>Offer a Resource Trade</a> | <a class='link_inline' href='aid_offer.php?ID=<?php echo $var_ID; ?>' title='Offer a Transaction to <?php echo $leader; ?>'>Offer a Transaction</a> | <a class='link_inline' href='warfare_declare.php?ID=<?php echo $var_ID; ?>' title='Declare war on <?php echo $nation; ?>'>Declare War</a></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Imported Resources: The goods and products imported into their nation'>Imported Resources:</abbr></td>
					<td class='nation_row_info'><?php   $count = 0;
								while($trade_list[$count] != 99)
								{
									echo res_to_image($trade_list[$count]) . " ";
									$count++;
								} ?></td>
				</tr>
				<tr>
					<th class='nation_header' colspan='2'>Military Information</th>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Infantry: The total number of infantry at home'>Infantry:</abbr></td>
					<td class='nation_row_info'><?php echo $inf_total; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Armor: The total number of armor at home'>Armor:</abbr></td>
					<td class='nation_row_info'><?php echo $armor_total; ?></td>
				</tr>
				<tr>
					<td class='nation_row_title'><abbr title='Infantry Casualties: The total number of infantry and armor lost in combat'>Infantry Casualties (Armor Losses):</abbr></td>
					<td class='nation_row_info'><?php echo $inf_losses . " (" . $armor_losses . ")"; ?></td>
				</tr>
			</table>
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
	// If the cookie does not exist, they are taken to the login screen
	header("Location: expiredsession.php");
}
?>