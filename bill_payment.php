<?php
/** bill_payment.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/bills_functions.php');
include ('functions/days_since_functions.php');
include ('functions/resource_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Bill Payment';
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
		//otherwise they are shown the bills payment page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		//collect the nation information for display from the nation_variables table
		$nationstats = mysql_query("SELECT resource_1, resource_2, treasury, land, infra, tech, capital, last_bill FROM nation_variables WHERE ID = '$ID'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			// Collect the raw data from the nation_variables table in the db
			$resource1 = stripslashes($row['resource_1']);
			$resource2 = stripslashes($row['resource_2']);
			$treasury = stripslashes($row['treasury']);
			$land = stripslashes($row['land']);
			$infra = stripslashes($row['infra']);
			$tech = stripslashes($row['tech']);
			$capital = stripslashes($row['capital']);
			$last_col = stripslashes($row['last_bill']);
		}

		// Compile a master array full of ALL trades this nation has
		// The included function 'trade_list' already uses stripslashes() on the resource list
		$trade_list = trade_list($ID);
		$trade_list[1] = $resource1;
		$trade_list[2] = $resource2;
		sort($trade_list);
		$trade_list = array_unique($trade_list);

		// Bills Calculation
		while($trade_list[$count] != 99)
		{
			if($trade_list[$count] == 3)
			{
				$resource = $trade_list[$count];
			}
		$count++;
		}

		$bills = bills_calculation($infra, $tech, $capital, $land);
		$bills = resource_bills($bills, $resource);

		// determine how many days they are back bills
		$days_diff = days_since_calculation($last_col);
		$days_diff = number_format($days_diff, 0);

		// calculate exact collection times the number of days uncollected
		$bill_payment = ($bills * $days_diff);

		//if bill payment form is submitted
		if (isset($_POST['submit']))
		{
			// subtract new treasury from old
			$newtreasury = ($treasury - $bill_payment);

			// update Last Bill Payment timestamp
			$date_update = gmdate('U');

			// update the treasury total to the new one!
			$insert = "UPDATE nation_variables SET treasury='" . $newtreasury . "', last_bill='" . $date_update . "' WHERE ID='" . $ID . "'";
			$add_member = mysql_query($insert);

			//then redirect them to the nation
			header("Location: nation.php?ID=$ID");
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='2'>Bill Payment</th>
			</tr>
			<tr>
				<td class='list_central_row_title'>Treasury:</td>
				<td class='list_central_row_info'>$<?php
							$treasury = number_format($treasury,2);
							echo $treasury; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Daily Bill Payment:</td>
				<td class='list_central_row_info'>$<?php
							$bills = number_format($bills,2);
							echo $bills; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Accumulated Bill Payment:</td>
				<td class='list_central_row_info'>$<?php
							$bill_payment = number_format($bill_payment,2);
							echo $bill_payment; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Days Unpaid:</td>
				<td class='list_central_row_info'><?php echo $days_diff; ?></td>
			</tr>
			<?php 
			if($days_diff > 0)
			{
				echo "  <tr>
							<td class='button' colspan='2'><input type='submit' name='submit' value='Pay Bills!' /></td>
						</tr>";
			}
			else
			{
				echo "  <tr>
							<td class='list_central_nav' colspan='2'>You have already paid bills today.  Try again tomorrow!</td>
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