<?php
/** capital_purchase.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/capital_calculation_functions.php');
include ('functions/resource_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Purchase Capital';
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
		//otherwise they are shown the capital purchase page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		//collect the nation information for display from the nation_variables table
		$nationstats = mysql_query("SELECT resource_1, resource_2, capital, treasury FROM nation_variables WHERE ID = '$ID'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			// Collect the raw data from the nation_variables table in the db
			$resource1 = stripslashes($row['resource_1']);
			$resource2 = stripslashes($row['resource_2']);
			$oldcapital = stripslashes($row['capital']);
			$treasury = stripslashes($row['treasury']);
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
			if($trade_list[$count] == 11)
			{
				$resource = $trade_list[$count];
			}
		$count++;
		}

		// Capital base cost calculation
		$price = capital_base_cost_calculation($oldcapital);
		$price = resource_cost_capital($price, $resource);

		//if capital purchase form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['capitalchange'] = strip_tags($_POST['capitalchange']);

			if(isset($_POST['capitalchange']))
			{
				if(sanity_check($_POST['capitalchange'], 'numeric', 3) != FALSE)
				{
					$capitalchange = mysql_real_escape_string($_POST['capitalchange']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=11");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			// check to see if too much capital has been purchased or sold
			if($capitalchange > 1)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=12");
			}
			elseif($capitalchange <= 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=13");
			}
			else
			{
				// calculate exact price of requested transaction amount
				$capitalcost = ($price * $capitalchange);

				// add or subtract new capital from old
				$newcapital = ($oldcapital + $capitalchange);

				// subtract new treasury from old
				$newtreasury = ($treasury - $capitalcost);

				// check to see if the requested purchase will bankrupt nation
				if($newtreasury < 0)
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=14");
				}

				// check to see if the value entered is either 0.5 or 1 only
				if($capitalchange == 0.5 || $capitalchange == 1)
				{	
					// update the capital and treasury total to the new one!
					$insert = "UPDATE nation_variables SET capital='" . $newcapital . "', treasury='" . $newtreasury . "' WHERE ID='" . $ID . "'";
					$add_member = mysql_query($insert);

					//then redirect them to the nation
					header("Location: capital_purchase.php");
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=15");
				}
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
				<th class='list_central_header' colspan='2'>Capital Purchase</th>
			</tr>
			<tr>
				<td class='list_central_instructions' colspan='2'>	This is the capital purchase page for your nation.  All capital 
																	purchases must be made in <em>increments of 0.5</em>. There is 
																	currently a limit of <strong>1</strong> capital maximum 
																	transaction limit.  Further, you <em>cannot sell capital</em> at this 
																	time.</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Current National Capital Value:</td>
				<td class='list_central_row_info'> <?php echo
								$oldcapital = number_format($oldcapital,2);
								$oldcapital; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Current Cost of 1 Capital:</td>
				<td class='list_central_row_info'> $<?php echo
								$price = number_format($price,2);
								$price; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Current Treasury Total:</td>
				<td class='list_central_row_info'> $<?php echo
								$treasury = number_format($treasury,2);
								$treasury; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Purchase/Destroy Capital</td>
				<td class='list_central_row_info'><input type='text' name='capitalchange' maxlength='3' /></td>
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