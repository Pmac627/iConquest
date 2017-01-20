<?php
/** aid_accept.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/days_since_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Foreign Aid Accept';
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
		//otherwise they are shown the trade offer page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		$URL_ID_aid = $_GET['ID_aid'];
		$URL_ID_aid = strip_tags($URL_ID_aid);
		$ID_aid = mysql_real_escape_string($URL_ID_aid);

		// grab the offer guy's ID
		$result2 = mysql_query("SELECT ID_send, aid_date, aid_money, aid_tech, aid_cap, aid_inf1, aid_inf2, aid_inf3 FROM foreign_aid WHERE ID_aid = '$ID_aid'") or die(mysql_error());
		while($send_guy = mysql_fetch_array($result2))
		{
			$ID_send = stripslashes($send_guy['ID_send']);
			$aid_date = stripslashes($send_guy['aid_date']);
			$aid_money = stripslashes($send_guy['aid_money']);
			$aid_tech = stripslashes($send_guy['aid_tech']);
			$aid_cap = stripslashes($send_guy['aid_cap']);
			$aid_inf1 = stripslashes($send_guy['aid_inf1']);
			$aid_inf2 = stripslashes($send_guy['aid_inf2']);
			$aid_inf3 = stripslashes($send_guy['aid_inf3']);
		}

		// Convert the creation Unix timestamp into a date & time
		$raw_time = $aid_date;
		$formatted_aid_date = date('Y-m-d', $raw_time);

		// grab the offer guy's ID
		$result3 = mysql_query("SELECT username FROM users WHERE ID = '$ID_send'") or die(mysql_error());
		while($sender = mysql_fetch_array($result3))
		{
			$sender_name = stripslashes($sender['username']);
		}

		//if trade offer form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['URL_ID_aid'] = strip_tags($_POST['URL_ID_aid']);
			$_POST['page_user'] = strip_tags($_POST['page_user']);
			$_POST['money'] = strip_tags($_POST['money']);
			$_POST['tech'] = strip_tags($_POST['tech']);
			$_POST['cap'] = strip_tags($_POST['cap']);
			$_POST['inf1'] = strip_tags($_POST['inf1']);
			$_POST['inf2'] = strip_tags($_POST['inf2']);
			$_POST['inf3'] = strip_tags($_POST['inf3']);
			$_POST['aid_date'] = strip_tags($_POST['aid_date']);
			$_POST['sender_name'] = strip_tags($_POST['sender_name']);

			if(isset($_POST['URL_ID_aid'], $_POST['page_user'], $_POST['money'], $_POST['tech'], $_POST['cap'], $_POST['inf1'], $_POST['inf2'], $_POST['inf3'], $_POST['aid_date'], $_POST['sender_name']))
			{
				if(sanity_check($_POST['URL_ID_aid'], 'numeric', 7) != FALSE && sanity_check($_POST['page_user'], 'numeric', 6) != FALSE && sanity_check($_POST['money'], 'numeric', 9) != FALSE && sanity_check($_POST['tech'], 'numeric', 5) != FALSE && sanity_check($_POST['cap'], 'numeric', 3) != FALSE && sanity_check($_POST['inf1'], 'numeric', 5) != FALSE && sanity_check($_POST['inf2'], 'numeric', 5) != FALSE && sanity_check($_POST['inf3'], 'numeric', 5) != FALSE && sanity_check($_POST['aid_date'], 'numeric', 10) != FALSE && sanity_check($_POST['sender_name'], 'string', 20) != FALSE)
				{
					$ID_aid = mysql_real_escape_string($_POST['URL_ID_aid']);
					$ID_user = mysql_real_escape_string($_POST['page_user']);
					$aid_money = mysql_real_escape_string($_POST['money']);
					$aid_tech = mysql_real_escape_string($_POST['tech']);
					$aid_cap = mysql_real_escape_string($_POST['cap']);
					$aid_inf1 = mysql_real_escape_string($_POST['inf1']);
					$aid_inf2 = mysql_real_escape_string($_POST['inf2']);
					$aid_inf3 = mysql_real_escape_string($_POST['inf3']);
					$aid_date = mysql_real_escape_string($_POST['aid_date']);
					$sender_name = mysql_real_escape_string($_POST['sender_name']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=2");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			// verify that the person using this action to accept the trade IS the one it was offered to ONLY
			// grab the offer guy's ID
			$result2 = mysql_query("SELECT ID_recip, ID_send FROM foreign_aid WHERE ID_aid = '$ID_aid'") or die(mysql_error());
			while($send_guy = mysql_fetch_array($result2))
			{
				$ID_recip = stripslashes($send_guy['ID_recip']);
				$ID_send = stripslashes($send_guy['ID_send']);
			}

			if($ID_user == $ID_recip)
			{
				// grab the offer guy's info
				$result4 = mysql_query("SELECT treasury, tech, capital FROM nation_variables WHERE ID = '$ID_send'") or die(mysql_error());
				while($verify1 = mysql_fetch_array($result4))
				{
					$treasury_orig = stripslashes($verify1['treasury']);
					$tech_orig = stripslashes($verify1['tech']);
					$cap_orig = stripslashes($verify1['capital']);
				}

				//collect the nation information for display from the military table
				$military = mysql_query("SELECT inf_1, inf_2, inf_3 FROM military WHERE ID = '$ID_send'") or die(mysql_error());
				while($mil = mysql_fetch_array( $military ))
				{
					// Collect the raw data from the military table in the db 
					$inf1_orig = stripslashes($mil['inf_1']);
					$inf2_orig = stripslashes($mil['inf_2']);
					$inf3_orig = stripslashes($mil['inf_3']);
				}

				if($treasury_orig >= $aid_money && $tech_orig >= $aid_tech && $cap_orig >= $aid_cap && $inf1_orig >= $aid_inf1 && $inf2_orig >= $aid_inf2 && $inf3_orig >= $aid_inf3)
				{
					// set the trade status to accepted
					$aid_stat = 1;

					// update to active trade
					$insert = "UPDATE foreign_aid SET aid_stat='" . $aid_stat . "' WHERE ID_aid='" . $ID_aid . "'";
					$add_member = mysql_query($insert);

					$new_treasury = mysql_real_escape_string(($treasury_orig - $aid_money));
					$new_tech = mysql_real_escape_string(($tech_orig - $aid_tech));
					$new_cap = mysql_real_escape_string(($cap_orig - $aid_cap));
					$new_inf1 = mysql_real_escape_string(($inf1_orig - $aid_inf1));
					$new_inf2 = mysql_real_escape_string(($inf2_orig - $aid_inf2));
					$new_inf3 = mysql_real_escape_string(($inf3_orig - $aid_inf3));

					// remove the items to the recipient nation
					$insert2 = "UPDATE nation_variables SET treasury='" . $new_treasury . "', tech='" . $new_tech . "', capital='" . $new_cap . "' WHERE ID='" . $ID_send . "'";
					$add_member2 = mysql_query($insert2);

					// remove the items to the recipient nation
					$insert3 = "UPDATE military SET inf_1='" . $new_inf1 . "', inf_2='" . $new_inf2 . "', inf_3='" . $new_inf3 . "' WHERE ID='" . $ID_send . "'";
					$add_member3 = mysql_query($insert3);

					// grab the recip guy's info
					$result5 = mysql_query("SELECT treasury, tech, capital FROM nation_variables WHERE ID = '$ID_recip'") or die(mysql_error());
					while($verify2 = mysql_fetch_array($result5))
					{
						$treasury_orig = stripslashes($verify2['treasury']);
						$tech_orig = stripslashes($verify2['tech']);
						$cap_orig = stripslashes($verify2['capital']);
					}

					//collect the nation information for display from the military table
					$military2 = mysql_query("SELECT inf_1, inf_2, inf_3 FROM military WHERE ID = '$ID_recip'") or die(mysql_error());
					while($mil2 = mysql_fetch_array( $military2 ))
					{
						// Collect the raw data from the military table in the db 
						$inf1_orig = stripslashes($mil2['inf_1']);
						$inf2_orig = stripslashes($mil2['inf_2']);
						$inf3_orig = stripslashes($mil2['inf_3']);
					}

					$new_treasury = mysql_real_escape_string(($treasury_orig + $aid_money));
					$new_tech = mysql_real_escape_string(($tech_orig + $aid_tech));
					$new_cap = mysql_real_escape_string(($cap_orig + $aid_cap));
					$new_inf1 = mysql_real_escape_string(($inf1_orig + $aid_inf1));
					$new_inf2 = mysql_real_escape_string(($inf2_orig + $aid_inf2));
					$new_inf3 = mysql_real_escape_string(($inf3_orig + $aid_inf3));

					// add the items to the recipient nation
					$insert4 = "UPDATE nation_variables SET treasury='" . $new_treasury . "', tech='" . $new_tech . "', capital='" . $new_cap . "' WHERE ID='" . $ID_recip . "'";
					$add_member4 = mysql_query($insert4);

					// add the items to the recipient nation
					$insert5 = "UPDATE military SET inf_1='" . $new_inf1 . "', inf_2='" . $new_inf2 . "', inf_3='" . $new_inf3 . "' WHERE ID='" . $ID_recip . "'";
					$add_member5 = mysql_query($insert5);

					//then redirect them to the nation
					header("Location: foreign_aid.php");
				}
				else
				{
					echo "You cannot accept the aid at this time.  The sender does not have sufficient resources to fulfill his/her aid!";
				}
			}
			elseif($ID_user == $ID_send)
			{
				echo "Trying to accept a trade <strong>YOU</strong> sent is cheating and has been logged!<br />Expect a PM shortly detailing the possiblity of a temp ban.";
			}
			else
			{
				echo "You can't accept a trade for someone else!";
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
			<th class='list_central_header' colspan='2'>Accepting an Aid Transaction</th>
		</tr>
		<tr>
			<td class='list_central_row_title'>From <?php echo $sender_name; ?><br />
				on <?php echo $formatted_aid_date; ?></td>
			<td class='list_central_row_info'><?php echo "  Monetary: $" . $aid_money . "<br />
															Technology: " . $aid_tech . "<br />
															Capital: " . $aid_cap . "<br />
															Infantry 1: " . $aid_inf1 . "<br />
															Infantry 2: " . $aid_inf2 . "<br />
															Infantry 3: " . $aid_inf3 . "<br />"; ?></td>
		</tr>
		<tr>
			<td class='button' colspan='2'><input type='submit' name='submit' value='Accept Aid' />
				<?php echo "<input type='hidden' name='URL_ID_aid' value='" . $ID_aid . "' />" ?>
				<?php echo "<input type='hidden' name='page_user' value='" . $ID . "' />" ?>
				<?php echo "<input type='hidden' name='money' value='" . $aid_money . "' />" ?>
				<?php echo "<input type='hidden' name='tech' value='" . $aid_tech . "' />" ?>
				<?php echo "<input type='hidden' name='cap' value='" . $aid_cap . "' />" ?>
				<?php echo "<input type='hidden' name='inf1' value='" . $aid_inf1 . "' />" ?>
				<?php echo "<input type='hidden' name='inf2' value='" . $aid_inf2 . "' />" ?>
				<?php echo "<input type='hidden' name='inf3' value='" . $aid_inf3 . "' />" ?>
				<?php echo "<input type='hidden' name='aid_date' value='" . $aid_date . "' />" ?>
				<?php echo "<input type='hidden' name='sender_name' value='" . $sender_name . "' />" ?></td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	</table>
	<?php
	include ('footer.php');
}
else
{
	//if the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>