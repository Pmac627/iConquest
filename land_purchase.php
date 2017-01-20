<?php
/** land_purchase.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/land_calculation_functions.php');
include ('functions/resource_functions.php');
include ('functions/civil_works_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Purchase Land';
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
		// Otherwise they are shown the land purchase page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		//collect the nation information for display from the nation_variables table
		$nationstats = mysql_query("SELECT land, treasury, resource_1, resource_2 FROM nation_variables WHERE ID = '$ID'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			// Collect the raw data from the nation_variables table in the db
			$oldland = stripslashes($row['land']);
			$treasury = stripslashes($row['treasury']);
			$resource1 = stripslashes($row['resource_1']);
			$resource2 = stripslashes($row['resource_2']);
		}

		//collect the nation information for display from the civil_works table
		$civil = mysql_query("SELECT ranch FROM civil_works WHERE ID = '$ID'") or die(mysql_error());
		while($cw = mysql_fetch_array( $civil ))
		{
			// Collect the raw data from the civil_works table in the db 
			$cw_ranch = stripslashes($cw['ranch']);
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
			if($trade_list[$count] == 2)
			{
				$resource = $trade_list[$count];
			}
		$count++;
		}

		$oldland = resource_land_area($oldland, $resource, $cw_ranch);

		// Land base cost calculation
		$price = land_base_cost_calculation($oldland);

		$price20 = ($price * 20);

		//if land purchase form is submitted
		if(isset($_POST['submit']))
		{
			$_POST['landchange'] = strip_tags($_POST['landchange']);

			if(isset($_POST['landchange']))
			{
				if(sanity_check($_POST['landchange'], 'numeric', 5) != FALSE)
				{
					$landchange = mysql_real_escape_string($_POST['landchange']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=32");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			// check to see if too much land has been purchased or sold
			if($landchange > 20)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=33");
			}
			elseif($landchange <= 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=13");
			}
			else
			{
				// calculate exact price of requested transaction amount
				$landcost = ($price * $landchange);

				// add or subtract new land from old
				$newland = ($oldland + $landchange);

				// subtract new treasury from old
				$newtreasury = ($treasury - $landcost);

				// check to see if the requested purchase will bankrupt nation
				if ($newtreasury < 0)
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=34");
				}

				// update the land and treasury total to the new one!
				$insert = "UPDATE nation_variables SET land='" . $newland . "', treasury='" . $newtreasury . "' WHERE ID='" . $ID . "'";
				$add_member = mysql_query($insert);

				//then redirect them to the nation
				header("Location: land_purchase.php");
			}
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='2'>Land Purchase</th>
			</tr>
			<tr>
				<td class='list_central_instructions' colspan='2'>	This is the land purchase page for your nation.
																	There is currently a limit of <strong>20</strong> land maximum
																	transaction limit.  Further, you <em>cannot sell land</em> at this
																	time.</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Current National Land Value:</td>
				<td class='list_central_row_info'> <?php
							$oldland = number_format($oldland,2);
							echo $oldland; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Current Cost of 1 Land:</td>
				<td class='list_central_row_info'> $<?php
							$price = number_format($price,2);
							echo $price; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Estimated Cost of 20 Land:</td>
				<td class='list_central_row_info'> $<?php
							$price20 = number_format($price20,2);
							echo $price20; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Current Treasury Total:</td>
				<td class='list_central_row_info'> $<?php
							$treasury = number_format($treasury,2);
							echo $treasury; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Purchase/Destroy Land</td>
				<td class='list_central_row_info'><input type='text' name='landchange' maxlength='5' /></td>
			</tr>
			<tr>
				<td class='button' colspan='2'><input type='submit' name='submit' value='Purchase!' /></td>
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