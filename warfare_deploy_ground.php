<?php
/** warfare_deploy_ground.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/days_since_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Deploy Ground Units';
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
		// Otherwise they are shown the warfare deploy page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		// Grab the aggressor's info from the military table
		$info = mysql_query("SELECT inf_1, inf_2, inf_3, armor_1, armor_2, armor_3, armor_4, armor_5 FROM military WHERE ID = '$ID'") or die(mysql_error());
		while($mil = mysql_fetch_array( $info ))
		{
			$inf1 = stripslashes($mil['inf_1']);
			$inf2 = stripslashes($mil['inf_2']);
			$inf3 = stripslashes($mil['inf_3']);
			$armor1 = stripslashes($mil['armor_1']);
			$armor2 = stripslashes($mil['armor_2']);
			$armor3 = stripslashes($mil['armor_3']);
			$armor4 = stripslashes($mil['armor_4']);
			$armor5 = stripslashes($mil['armor_5']);
		}

		$home_inf = ($inf1 + $inf2 + $inf3);
		$home_armor = ($armor1 + $armor2 + $armor3 + $armor4 + $armor5);

		// Grab the aggressor's info from the deployed table
		$info = mysql_query("SELECT inf_1_deploy, inf_2_deploy, inf_3_deploy, armor_1_deploy, armor_2_deploy, armor_3_deploy, armor_4_deploy, armor_5_deploy, deployed_today, last_deploy FROM deployed WHERE ID = '$ID'") or die(mysql_error());
		while($dep = mysql_fetch_array( $info ))
		{
			$inf1_deployed = stripslashes($dep['inf_1_deploy']);
			$inf2_deployed = stripslashes($dep['inf_2_deploy']);
			$inf3_deployed = stripslashes($dep['inf_3_deploy']);
			$armor1_deployed = stripslashes($dep['armor_1_deploy']);
			$armor2_deployed = stripslashes($dep['armor_2_deploy']);
			$armor3_deployed = stripslashes($dep['armor_3_deploy']);
			$armor4_deployed = stripslashes($dep['armor_4_deploy']);
			$armor5_deployed = stripslashes($dep['armor_5_deploy']);
			$deployed_today = stripslashes($dep['deployed_today']);
			$last_deploy = stripslashes($dep['last_deploy']);
		}

		$deployed_inf = ($inf1_deployed + $inf2_deployed + $inf3_deployed);
		$deployed_armor = ($armor1_deployed + $armor2_deployed + $armor3_deployed + $armor4_deployed + $armor5_deployed);

		$inf_grand_total = ($home_inf + $deployed_inf);
		$armor_grand_total = ($home_armor + $deployed_armor);

		$last_deploy_date = days_since_calculation($last_deploy);
		$last_deploy_date = number_format($last_deploy_date, 0);

		if($last_deploy_date > 0 && $deployed_today == 1)
		{
			// If the ruler hasn't deployed today, reset the $deployed_today variable
			$new_deploy_today = 0;

			// Update to undeployed
			$insert = "UPDATE deployed SET deployed_today='" . $new_deploy_today . "' WHERE ID='" . $ID . "'";
			$add_member = mysql_query($insert);

			// Then redirect them to the nation
			header("Location: warfare_deploy_ground.php");
		}

		if($deployed_today == 1 || $last_deploy_date == 0)
		{
			$deploy_fubar = "You already deployed today.";
		}
		else
		{
			$deploy_fubar = "<input type='submit' name='submit' value='Deploy Ground Units' />";
		}

		// If the military deploy form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['ID'] = strip_tags($_POST['ID']);
			$_POST['inf1'] = strip_tags($_POST['inf1']);
			$_POST['inf2'] = strip_tags($_POST['inf2']);
			$_POST['inf3'] = strip_tags($_POST['inf3']);
			$_POST['armor1'] = strip_tags($_POST['armor1']);
			$_POST['armor2'] = strip_tags($_POST['armor2']);
			$_POST['armor3'] = strip_tags($_POST['armor3']);
			$_POST['armor4'] = strip_tags($_POST['armor4']);
			$_POST['armor5'] = strip_tags($_POST['armor5']);
			$_POST['home_inf1'] = strip_tags($_POST['home_inf1']);
			$_POST['home_inf2'] = strip_tags($_POST['home_inf2']);
			$_POST['home_inf3'] = strip_tags($_POST['home_inf3']);
			$_POST['home_armor1'] = strip_tags($_POST['home_armor1']);
			$_POST['home_armor2'] = strip_tags($_POST['home_armor2']);
			$_POST['home_armor3'] = strip_tags($_POST['home_armor3']);
			$_POST['home_armor4'] = strip_tags($_POST['home_armor4']);
			$_POST['home_armor5'] = strip_tags($_POST['home_armor5']);
			$_POST['inf_grand_total'] = strip_tags($_POST['inf_grand_total']);
			$_POST['armor_grand_total'] = strip_tags($_POST['armor_grand_total']);

			if(isset($_POST['ID'], $_POST['inf1'], $_POST['inf2'], $_POST['inf3'], $_POST['armor1'], $_POST['armor2'], $_POST['armor3'], $_POST['armor4'], $_POST['armor5'], $_POST['home_inf1'], $_POST['home_inf2'], $_POST['home_inf3'], $_POST['home_armor1'], $_POST['home_armor2'], $_POST['home_armor3'], $_POST['home_armor4'], $_POST['home_armor5'], $_POST['inf_grand_total'], $_POST['armor_grand_total']))
			{
				if(sanity_check($_POST['ID'], 'numeric', 6) != FALSE && sanity_check($_POST['inf1'], 'numeric', 4) != FALSE && sanity_check($_POST['inf2'], 'numeric', 4) != FALSE && sanity_check($_POST['inf3'], 'numeric', 4) != FALSE && sanity_check($_POST['armor1'], 'numeric', 4) != FALSE && sanity_check($_POST['armor2'], 'numeric', 4) != FALSE && sanity_check($_POST['armor3'], 'numeric', 4) != FALSE && sanity_check($_POST['armor4'], 'numeric', 4) != FALSE && sanity_check($_POST['armor5'], 'numeric', 4) != FALSE && sanity_check($_POST['home_inf1'], 'numeric', 6) != FALSE && sanity_check($_POST['home_inf2'], 'numeric', 6) != FALSE && sanity_check($_POST['home_inf3'], 'numeric', 6) != FALSE && sanity_check($_POST['home_armor1'], 'numeric', 6) != FALSE && sanity_check($_POST['home_armor2'], 'numeric', 6) != FALSE && sanity_check($_POST['home_armor3'], 'numeric', 6) != FALSE && sanity_check($_POST['home_armor4'], 'numeric', 6) != FALSE && sanity_check($_POST['home_armor5'], 'numeric', 6) != FALSE && sanity_check($_POST['inf_grand_total'], 'numeric', 6) != FALSE && sanity_check($_POST['armor_grand_total'], 'numeric', 6) != FALSE)
				{
					$ID = $_POST['ID'];
					$inf1 = $_POST['inf1'];
					$inf2 = $_POST['inf2'];
					$inf3 = $_POST['inf3'];
					$armor1 = $_POST['armor1'];
					$armor2 = $_POST['armor2'];
					$armor3 = $_POST['armor3'];
					$armor4 = $_POST['armor4'];
					$armor5 = $_POST['armor5'];
					$home_inf1 = $_POST['home_inf1'];
					$home_inf2 = $_POST['home_inf2'];
					$home_inf3 = $_POST['home_inf3'];
					$home_armor1 = $_POST['home_armor1'];
					$home_armor2 = $_POST['home_armor2'];
					$home_armor3 = $_POST['home_armor3'];
					$home_armor4 = $_POST['home_armor4'];
					$home_armor5 = $_POST['home_armor5'];
					$inf_grand_total = $_POST['inf_grand_total'];
					$armor_grand_total = $_POST['armor_grand_total'];
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=75");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			$last_deploy = gmdate('U');

			$inf_attemp_deploy = ($inf1 + $inf2 + $inf3);
			$armor_attemp_deploy = ($armor1 + $armor2 + $armor3 + $armor4 + $armor5);

			$inf1 = number_format($inf1, 0);
			$inf2 = number_format($inf2, 0);
			$inf3 = number_format($inf3, 0);
			$armor1 = number_format($armor1, 0);
			$armor2 = number_format($armor2, 0);
			$armor3 = number_format($armor3, 0);
			$armor4 = number_format($armor4, 0);
			$armor5 = number_format($armor5, 0);

			if($inf_attemp_deploy > $inf_grand_total || $inf1 < 0 || $inf2 < 0 || $inf3 < 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=76");
			}

			if($armor_attemp_deploy > $armor_grand_total || $armor1 < 0 || $armor2 < 0 || $armor3 < 0 || $armor4 < 0 || $armor5 < 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=77");
			}

			$home_inf1 = ($home_inf1 + $inf1);
			$home_inf2 = ($home_inf2 + $inf2);
			$home_inf3 = ($home_inf3 + $inf3);
			$home_armor1 = ($home_armor1 + $armor1);
			$home_armor2 = ($home_armor2 + $armor2);
			$home_armor3 = ($home_armor3 + $armor3);
			$home_armor4 = ($home_armor4 + $armor4);
			$home_armor5 = ($home_armor5 + $armor5);

			$less_home_inf1 = ($home_inf1 - $inf1);
			$less_home_inf2 = ($home_inf2 - $inf2);
			$less_home_inf3 = ($home_inf3 - $inf3);
			$less_home_armor1 = ($home_armor1 - $armor1);
			$less_home_armor2 = ($home_armor2 - $armor2);
			$less_home_armor3 = ($home_armor3 - $armor3);
			$less_home_armor4 = ($home_armor4 - $armor4);
			$less_home_armor5 = ($home_armor5 - $armor5);

			if($less_home_inf1 < 0 || $less_home_inf2 < 0 || $less_home_inf3 < 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=78");
			}

			// Update the military database
			$insert2 = "UPDATE military SET inf_1='" . $less_home_inf1 . "', inf_2='" . $less_home_inf2 . "', inf_3='" . $less_home_inf3 . "', armor_1='" . $less_home_armor1 . "', armor_2='" . $less_home_armor2 . "', armor_3='" . $less_home_armor3 . "', armor_4='" . $less_home_armor4 . "', armor_5='" . $less_home_armor5 . "' WHERE ID = '" . $ID . "'";
			$add_message2 = mysql_query($insert2);

			// Update the deployed database
			$insert2 = "UPDATE deployed SET inf_1_deploy='" . $inf1 . "', inf_2_deploy='" . $inf2 . "', inf_3_deploy='" . $inf3 . "', armor_1_deploy='" . $armor1 . "', armor_2_deploy='" . $armor2 . "', armor_3_deploy='" . $armor3 . "', armor_4_deploy='" . $armor4 . "', armor_5_deploy='" . $armor5 . "', inf_1_today='" . $inf1 . "', inf_2_today='" . $inf2 . "', inf_3_today='" . $inf3 . "', armor_1_today='" . $armor1 . "', armor_2_today='" . $armor2 . "', armor_3_today='" . $armor3 . "', armor_4_today='" . $armor4 . "', armor_5_today='" . $armor5 . "', deployed_today='1', last_deploy='" . $last_deploy . "' WHERE ID = '" . $ID . "'";
			$add_message2 = mysql_query($insert2);

			// Then redirect them to the nation
			header("Location: military_command.php");
		}
		include ('header.php');

		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='2'>Deploying Ground Units</th>
			</tr>
			<tr>
				<td class='list_central_instructions' colspan='2'>This is for deploying ground units. You can only deploy units once a day. 
								Plan accordingly!</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Lv 1 Infantry (<?php echo $inf1; ?> max)</td>
				<td class='list_central_row_info'><input type='text' name='inf1' value='<?php echo $inf1_deployed; ?>' maxlength='4' /></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Lv 2 Infantry (<?php echo $inf2; ?> max)</td>
				<td class='list_central_row_info'><input type='text' name='inf2' value='<?php echo $inf2_deployed; ?>' maxlength='4' /></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Lv 3 Infantry (<?php echo $inf3; ?> max)</td>
				<td class='list_central_row_info'><input type='text' name='inf3' value='<?php echo $inf3_deployed; ?>' maxlength='4' /></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Lv 1 Armor (<?php echo $armor1; ?> max)</td>
				<td class='list_central_row_info'><input type='text' name='armor1' value='<?php echo $armor1_deployed; ?>' maxlength='4' /></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Lv 2 Armor (<?php echo $armor2; ?> max)</td>
				<td class='list_central_row_info'><input type='text' name='armor2' value='<?php echo $armor2_deployed; ?>' maxlength='4' /></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Lv 3 Armor (<?php echo $armor3; ?> max)</td>
				<td class='list_central_row_info'><input type='text' name='armor3' value='<?php echo $armor3_deployed; ?>' maxlength='4' /></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Lv 4 Armor (<?php echo $armor4; ?> max)</td>
				<td class='list_central_row_info'><input type='text' name='armor4' value='<?php echo $armor4_deployed; ?>' maxlength='4' /></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Lv 5 Armor (<?php echo $armor5; ?> max)</td>
				<td class='list_central_row_info'><input type='text' name='armor5' value='<?php echo $armor5_deployed; ?>' maxlength='4' /></td>
			</tr>
			<tr>
				<td class='button' colspan='2'><?php echo $deploy_fubar; ?>
					<?php echo "<input type='hidden' name='ID' value='" . $ID . "' />
								<input type='hidden' name='home_inf1' value='" . $inf1 . "' />
								<input type='hidden' name='home_inf2' value='" . $inf2 . "' />
								<input type='hidden' name='home_inf3' value='" . $inf3 . "' />
								<input type='hidden' name='home_armor1' value='" . $armor1 . "' />
								<input type='hidden' name='home_armor2' value='" . $armor2 . "' />
								<input type='hidden' name='home_armor3' value='" . $armor3 . "' />
								<input type='hidden' name='home_armor4' value='" . $armor4 . "' />
								<input type='hidden' name='home_armor5' value='" . $armor5 . "' />
								<input type='hidden' name='inf_grand_total' value='" . $inf_grand_total . "' />
								<input type='hidden' name='armor_grand_total' value='" . $armor_grand_total . "' />" ?></td>
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
	// If the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>