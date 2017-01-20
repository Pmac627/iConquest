<?php
/** edit_region.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Change Region Name';
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
		//otherwise they are shown the region edit page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		//collect the nation information for display from the nations table
		$nationstats = mysql_query("SELECT region FROM nations WHERE ID = '$ID'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			$old_region = stripslashes($row['region']);
		}

		//if the region name edit form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['newregion'] = strip_tags($_POST['newregion']);

			if(isset($_POST['newregion']))
			{
				if(sanity_check($_POST['newregion'], 'string', 20) != FALSE)
				{
					$newregion = mysql_real_escape_string($_POST['newregion']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=24");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			// update the region city name to the new one!
			$insert = "UPDATE nations SET region='" . $newregion . "' WHERE ID='" . $ID . "'";
			$add_member = mysql_query($insert);

			//then redirect them to the nation
			header("Location: nation.php?ID=$ID");
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='2'>Update Region Name</th>
			</tr>
			<tr>
				<td class='list_central_row_title'>Original Region Name:</td>
				<td class='list_central_row_info'><?php echo $old_region; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>New Region Name:</td>
				<td class='list_central_row_info'><input type='text' name='newregion' maxlength='20' /></td>
			</tr>
			<tr>
				<td class='button' colspan='2'><input type='submit' name='submit' value='Submit' /></td>
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