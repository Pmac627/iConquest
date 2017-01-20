<?php
/** foreign_aid.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/days_since_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Foreign Aid Overview';
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

	//if the cookie has the wrong password, they are taken to the expired session login page
	if ($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		//otherwise they are shown the foreign aid management page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		$ID_recip = $ID;

		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='4'>International Aid Contracts</th>
			</tr>
			<tr>
				<td class='list_central_row_title'>Partner</td>
				<td class='list_central_row_title' width='250'>Details</td>
				<td class='list_central_row_title' width='80'>Date/Time</td>
				<td class='list_central_row_title' width='60'>Status</td>
			</tr>
				<?php
					// Display aid offered by others to this nation

					//count the number of aid partners a user has
					$aid_list = mysql_query("SELECT ID_send, ID_aid, aid_money, aid_tech, aid_cap, aid_inf1, aid_inf2, aid_inf3, aid_date, aid_stat FROM foreign_aid WHERE ID_recip = '$ID_recip'") or die(mysql_error());
					while($trans_list = mysql_fetch_array( $aid_list ))
					{
						$ID_send = stripslashes($trans_list['ID_send']);
						$ID_aid = stripslashes($trans_list['ID_aid']);
						$money_aid = stripslashes($trans_list['aid_money']);
						$tech_aid = stripslashes($trans_list['aid_tech']);
						$capital_aid = stripslashes($trans_list['aid_cap']);
						$inf1_aid = stripslashes($trans_list['aid_inf1']);
						$inf2_aid = stripslashes($trans_list['aid_inf2']);
						$inf3_aid = stripslashes($trans_list['aid_inf3']);
						$aid_date = stripslashes($trans_list['aid_date']);
						$aid_stat = stripslashes($trans_list['aid_stat']);

						// Convert the creation Unix timestamp into a date & time
						$raw_time = $aid_date;
						$formatted_aid_date = date('Y-m-d', $raw_time);

						// collect the nation leader that corresponds with ID_offerer
						$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_send'") or die(mysql_error());
						while($ruler = mysql_fetch_array($result2))
						{
							$leader = stripslashes($ruler['username']);
						}

						$a = $aid_stat;
						$aid_check = days_since_calculation($aid_date);
						$aid_check = number_format($aid_check,0);
						if($aid_check > 7)
						{
							$aid_stat = 2;
							// update to deleted
							$insert = "UPDATE foreign_aid SET aid_stat='" . $aid_stat . "' WHERE ID_aid='" . $ID_aid . "'";
							$add_member = mysql_query($insert);
						}

						if($a == 0)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_send . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>Monetary: $" . $money_aid . "<br />
										Technology: " . $tech_aid . "<br />
										Capital: " . $capital_aid . "<br />
										Infantry 1: " . $inf1_aid . "<br />
										Infantry 2: " . $inf2_aid . "<br />
										Infantry 3: " . $inf3_aid . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_aid_date . "</td>
									<td class='list_central_row_info'><a class='link_inline' href='aid_accept.php?ID_aid=" . $ID_aid . "' title='Accept Aid'>Offered</a></td>
								</tr>" ;
						}
						elseif($a == 1)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_send . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>Monetary: $" . $money_aid . "<br />
										Technology: " . $tech_aid . "<br />
										Capital: " . $capital_aid . "<br />
										Infantry 1: " . $inf1_aid . "<br />
										Infantry 2: " . $inf2_aid . "<br />
										Infantry 3: " . $inf3_aid . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_aid_date . "</td>
									<td class='list_central_row_info'>Active</td>
								</tr>" ;
						}
						else
						{
						}
					}
					// Display aid offered by this nation to others

					//count the number of aid partners a user has
					$aid_list = mysql_query("SELECT ID_recip, ID_aid, aid_money, aid_tech, aid_cap, aid_inf1, aid_inf2, aid_inf3, aid_date, aid_stat FROM foreign_aid WHERE ID_send = '$ID_recip'") or die(mysql_error());
					while($trans_list = mysql_fetch_array( $aid_list ))
					{
						$ID_recip = stripslashes($trans_list['ID_recip']);
						$ID_aid = stripslashes($trans_list['ID_aid']);
						$money_aid = stripslashes($trans_list['aid_money']);
						$tech_aid = stripslashes($trans_list['aid_tech']);
						$capital_aid = stripslashes($trans_list['aid_cap']);
						$inf1_aid = stripslashes($trans_list['aid_inf1']);
						$inf2_aid = stripslashes($trans_list['aid_inf2']);
						$inf3_aid = stripslashes($trans_list['aid_inf3']);
						$aid_date = stripslashes($trans_list['aid_date']);
						$aid_stat = stripslashes($trans_list['aid_stat']);

						// Convert the creation Unix timestamp into a date & time
						$raw_time = $aid_date;
						$formatted_aid_date = date('Y-m-d', $raw_time);

						// collect the nation leader that corresponds with ID_offerer
						$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_recip'") or die(mysql_error());
						while($ruler = mysql_fetch_array($result2))
						{
							$leader = stripslashes($ruler['username']);
						}

						// create the status report; $status_fubar
						$a = $aid_stat;
						$aid_check = days_since_calculation($aid_date);
						$aid_check = number_format($aid_check, 0);
						if($aid_check > 7)
						{
							$aid_stat = 2;
							// update to deleted
							$insert = "UPDATE foreign_aid SET aid_stat='" . $aid_stat . "' WHERE ID_aid='" . $ID_aid . "'";
							$add_member = mysql_query($insert);
						}

						if($a == 0)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_recip . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>Monetary: $" . $money_aid . "<br />
										Technology: " . $tech_aid . "<br />
										Capital: " . $capital_aid . "<br />
										Infantry 1: " . $inf1_aid . "<br />
										Infantry 2: " . $inf2_aid . "<br />
										Infantry 3: " . $inf3_aid . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_aid_date . "</td>
									<td class='list_central_row_info'>Offered</td>
								</tr>" ;
						}
						elseif($a == 1)
						{
						// Output the display
						echo "  <tr>
									<td class='list_central_row_info'><a class='link_inline' href='nation.php?ID=" . $ID_recip . "'>" . $leader . "</a></td>
									<td class='list_central_row_info'>Monetary: $" . $money_aid . "<br />
										Technology: " . $tech_aid . "<br />
										Capital: " . $capital_aid . "<br />
										Infantry 1: " . $inf1_aid . "<br />
										Infantry 2: " . $inf2_aid . "<br />
										Infantry 3: " . $inf3_aid . "<br /></td>
									<td class='list_central_row_info'>" . $formatted_aid_date . "</td>
									<td class='list_central_row_info'>Active</td>
								</tr>" ;
						}
						else
						{
						}
					}
				?>
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