<?php
/** resource_trade.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/days_since_functions.php');
include ('functions/resource_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Resource Trade Contracts';
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
		//otherwise they are shown the resource trade management page
		$ID_recip = $ID;

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		// collect my resources
		$my_r = mysql_query("SELECT resource_1, resource_2 FROM nation_variables WHERE ID = '$ID_recip'") or die(mysql_error());
		while($my_resources = mysql_fetch_array($my_r))
		{
			$my_resource1 = stripslashes($my_resources['resource_1']);
			$my_resource2 = stripslashes($my_resources['resource_2']);
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='5'>International Resource Trade Contracts</th>
			</tr>
			<tr>
				<td class='list_central_row_title' colspan='2'>My Resources</td>
				<td class='list_central_row_info' colspan='3'><?php echo res_to_image($my_resource1) . res_to_image($my_resource2) ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Partner</td>
				<td class='list_central_row_title'>Resource 1</td>
				<td class='list_central_row_title'>Resource 2</td>
				<td class='list_central_row_title' width='140'>Date/Time</td>
				<td class='list_central_row_title'>Status</td>
			</tr>
			<?php
				// Display trades offered by others to this nation

				//count the number of trade partners a user has
				$trade_list = mysql_query("SELECT ID_offerer, ID_trade, trade_date, trade_stat FROM resource_trade WHERE ID_recip = '$ID_recip'") or die(mysql_error());
				while($partner_list = mysql_fetch_array( $trade_list ))
				{
					$ID_offerer = stripslashes($partner_list['ID_offerer']);
					$ID_trade = stripslashes($partner_list['ID_trade']);
					$trade_date = stripslashes($partner_list['trade_date']);
					$trade_stat = stripslashes($partner_list['trade_stat']);

					// collect the nation leader that corresponds with ID_offerer
					$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_offerer'") or die(mysql_error());
					while($ruler = mysql_fetch_array($result2))
					{
						$leader = stripslashes($ruler['username']);
					}

					// collect the resources that correspond with  ID_offerer
					$result3 = mysql_query("SELECT resource_1, resource_2 FROM nation_variables WHERE ID = '$ID_offerer'") or die(mysql_error());
					while($res = mysql_fetch_array($result3))
					{
						$resource_1 = stripslashes($res['resource_1']);
						$resource_2 = stripslashes($res['resource_2']);
					}

					// Convert the creation Unix timestamp into a date & time
					$raw_time = $trade_date;
					$formatted_trade_date = date('Y-m-d', $raw_time);

					// create the status report; $status_fubar
					$a = $trade_stat;
					$trade_check = days_since_calculation($trade_date);
					if($a == 0)
					{
						if($trade_check > 7)
						{
							$trade_stat = 2;
							// update to deleted
							$insert = "UPDATE resource_trade SET trade_stat='" . $trade_stat . "' WHERE ID_trade='" . $ID_trade . "'";
							$add_member = mysql_query($insert);
						}
						else
						{
							// Output the display
							echo "  <tr>
										<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_offerer . "'>" . $leader . "</a></td>
										<td class='list_central_row_info'>" . res_to_image($resource_1) . "</td>
										<td class='list_central_row_info'>" . res_to_image($resource_2) . "</td>
										<td class='list_central_row_info'>" . $formatted_trade_date . "</td>
										<td class='list_central_row_info'><a class='link_inline' href='trade_accept.php?ID_trade=" . $ID_trade . "' title='Accept Trade'>Offered</a></td>
									</tr>" ;
						}
					}
					elseif($a == 1)
					{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_offerer . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>" . res_to_image($resource_1) . "</td>
									<td class='list_central_row_info'>" . res_to_image($resource_2) . "</td>
									<td class='list_central_row_info'>" . $formatted_trade_date . "</td>
									<td class='list_central_row_info'><a class='link_inline' href='trade_cancel.php?ID_trade=" . $ID_trade . "' title='Cancel Trade'>Accepted</a></td>
								</tr>" ;
					}
					else
					{
					}
				}
				// Display trades offered by this nation to others 
				
				//count the number of trade partners a user has
				$trade_list = mysql_query("SELECT ID_recip, ID_trade, trade_date, trade_stat FROM resource_trade WHERE ID_offerer = '$ID_recip'") or die(mysql_error());
				while($partner_list = mysql_fetch_array( $trade_list ))
				{
					$ID_recip = stripslashes($partner_list['ID_recip']);
					$ID_trade = stripslashes($partner_list['ID_trade']);
					$trade_date = stripslashes($partner_list['trade_date']);
					$trade_stat = stripslashes($partner_list['trade_stat']);

					// collect the nation leader that corresponds with ID_offerer
					$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_recip'") or die(mysql_error());
					while($ruler = mysql_fetch_array($result2))
					{
						$leader = stripslashes($ruler['username']);
					}

					// collect the resources that correspond with  ID_offerer
					$result3 = mysql_query("SELECT resource_1, resource_2 FROM nation_variables WHERE ID = '$ID_recip'") or die(mysql_error());
					while($res = mysql_fetch_array($result3))
					{
						$resource_1 = stripslashes($res['resource_1']);
						$resource_2 = stripslashes($res['resource_2']);
					}

					// Convert the creation Unix timestamp into a date & time
					$raw_time = $trade_date;
					$formatted_trade_date = date('Y-m-d', $raw_time);

					$a = $trade_stat;
					$trade_check = days_since_calculation($trade_date);
					if($a == 0)
					{
						if($trade_check > 7)
						{
							$trade_stat = 2;
							// update to deleted
							$insert = "UPDATE resource_trade SET trade_stat='" . $trade_stat . "' WHERE ID_trade='" . $ID_trade . "'";
							$add_member = mysql_query($insert);
						}
						else
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_recip . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>" . res_to_image($resource_1) . "</td>
									<td class='list_central_row_info'>" . res_to_image($resource_2) . "</td>
									<td class='list_central_row_info'>" . $formatted_trade_date . "</td>
									<td class='list_central_row_info'>Offered</td>
								</tr>" ;
						}
					}
					elseif($a == 1)
					{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_recip . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>" . res_to_image($resource_1) . "</td>
									<td class='list_central_row_info'>" . res_to_image($resource_2) . "</td>
									<td class='list_central_row_info'>" . $formatted_trade_date . "</td>
									<td class='list_central_row_info'><a class='link_inline' href='trade_cancel.php?ID_trade=" . $ID_trade . "' title='Cancel Trade'>Accepted</a></td>
								</tr>" ;
					}
					else
					{
					}
				}
			?>
			<tr>
				<td class='list_central_row_title' colspan='2'>Bonus Resources:</td>
				<td class='list_central_row_info' colspan='3'>N/A</td>
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
	//if the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>