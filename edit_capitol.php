<?php
/** edit_capitol.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Change Capitol Name';
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
		//otherwise they are shown the capitol name edit page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		//collect the nation information for display from the nations table
		$nationstats = mysql_query("SELECT capitol FROM nations WHERE ID = '$ID'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			$old_capitol = stripslashes($row['capitol']);
		}

		//if the capitol name edit form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['newcapitol'] = strip_tags($_POST['newcapitol']);

			if(isset($_POST['newcapitol']))
			{
				if(sanity_check($_POST['newcapitol'], 'string', 20) != FALSE)
				{
					$newcapitol = mysql_real_escape_string($_POST['newcapitol']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=26");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			// update the capitol city name to the new one!
			$insert = "UPDATE nations SET capitol='" . $newcapitol . "' WHERE ID='" . $ID . "'";
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
				<th class='list_central_header' colspan='2'>Update Capitol City Name</th>
			</tr>
			<tr>
				<td class='list_central_row_title'>Original Capitol Name:</td>
				<td class='list_central_row_info'> <?php echo $old_capitol; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>New Capitol Name:</td>
				<td class='list_central_row_info'><input type='text' name='newcapitol' maxlength='20' /></td>
			</tr>
			<tr>
				<td class='button' colspan='2'><input type='submit' name='submit' value='Update Capitol!' /></td>
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
	header("'Location: expiredsession.php");
}
?>