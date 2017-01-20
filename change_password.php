<?php
/** change_password.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Change Password';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$pass = $_COOKIE['Key_i_Conquest'];

	// Collect the nation ID that corresponds with the username
	$check = mysql_query("SELECT ID, password, mod_admin FROM users WHERE username = '$username'")or die(mysql_error());
	while($info = mysql_fetch_array( $check ))
	{
		$ID = $info['ID'];
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

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		// If the change password form is submitted
		if(isset($_POST['submit']))
		{
			$_POST['oldpass'] = strip_tags($_POST['oldpass']);
			$_POST['newpass'] = strip_tags($_POST['newpass']);
			$_POST['newpass2'] = strip_tags($_POST['newpass2']);

			if(isset($_POST['oldpass'], $_POST['newpass'], $_POST['newpass2']))
			{
				if(sanity_check($_POST['oldpass'], 'string', 32) != FALSE && sanity_check($_POST['newpass'], 'string', 32) != FALSE && sanity_check($_POST['newpass2'], 'string', 32) != FALSE)
				{
					$input_oldpass = mysql_real_escape_string($_POST['oldpass']);
					$input_newpass = mysql_real_escape_string($_POST['newpass']);
					$input_newpass2 = mysql_real_escape_string($_POST['newpass2']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=1");
				}

				// Here we encrypt the old password for the match check
				$input_oldpass = md5($input_oldpass);

				// Here we encrypt the new passwords
				$input_newpass = md5($input_newpass);
				$input_newpass2 = md5($input_newpass2);

				// This makes sure old password entered matches old password in db
				if ($input_oldpass != $info_pass)
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=17");
				}
				// This makes sure both new passwords entered match
				if ($input_newpass != $input_newpass2)
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=18");
				}

				// Update the password to the new one!
				$insert = "UPDATE users SET password='" . $input_newpass . "' WHERE ID='" . $ID . "'";
				$add_member = mysql_query($insert);

				// Then redirect them to the nation
				header("Location: nation.php?ID=$ID");
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=16");
			}
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='main'>
			<tr>
				<th class='form_head' colspan='4'>iC Change Password Page</th></tr>
			<tr>
				<td></td>
				<td class='input_title_register'>Old Password:</td>
				<td class='input'><input type='password' name='oldpass' maxlength='20' /></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='input_title_register'>New Password:</td>
				<td class='input'><input type='password' name='newpass' maxlength='20' /></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='input_title_register'>Confirm New Password:</td>
				<td class='input'><input type='password' name='newpass2' maxlength='20' /></td>
				<td></td>
			</tr>
			<tr>
				<td class='button' colspan='4'><input type='submit' name='submit' value='Change Password' /></td>
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
	// If the cookie does not exist, they are taken to the expired session login screen
	header("Location: expiredsession.php");
}
?>