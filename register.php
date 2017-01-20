<?php
/** register.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$mod_admin = 3;
$page_title_name = 'Create Account';
$meta_restrict = '<meta name="robots" content="index, nofollow" />';

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$pass = $_COOKIE['Key_i_Conquest'];
	$check = mysql_query("SELECT ID, password, mod_admin, IP, IP_block FROM users WHERE username = '$username'")or die(mysql_error());
	while($info = mysql_fetch_array( $check ))
	{
		$ID = $info['ID'];
		$info_pass = $info['password'];
		$mod_admin = $info['mod_admin'];
		$IP = $info['IP'];
		$IP_block = $info['IP_block'];
	}

	if($ID == '' || $ID == NULL)
	{
		header("Location: expiredsession.php");
	}
	elseif($ID >= 1)
	{
		// Redirect them to the error page
		header("Location: error_page.php?error=41");
	}
	else
	{
	}
}
else
{
	// Otherwise they are shown the registration page

	// Collect the switchboard information
	$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
	while($switch = mysql_fetch_array( $switch_stats ))
	{
		$site_online = stripslashes($switch['site_online']);
		$ic_version_marker = stripslashes($switch['version']);
		$multiple_nations = stripslashes($switch['multiple_nations']);
	}

	site_online($site_online, $mod_admin);
	multi_check($IP_block, $IP_total, $multiple_nations, $mod_admin);

	// This code runs if the form has been submitted
	if(isset($_POST['submit']))
	{
		$_POST['username'] = strip_tags($_POST['username']);
		$_POST['pass'] = strip_tags($_POST['pass']);
		$_POST['pass2'] = strip_tags($_POST['pass2']);
		$_POST['email'] = strip_tags($_POST['email']);
		$_POST['ToS'] = strip_tags($_POST['ToS']);

		// This makes sure they did not leave any fields blank
		if(isset($_POST['username'], $_POST['pass'], $_POST['pass2'], $_POST['email'], $_POST['ToS']))
		{
			if(sanity_check($_POST['username'], 'string', 20) != FALSE && sanity_check($_POST['pass'], 'string', 20) != FALSE && sanity_check($_POST['pass2'], 'string', 20) != FALSE && sanity_check($_POST['email'], 'string', 60) != FALSE && email_check($_POST['email']) != FALSE && sanity_check($_POST['ToS'], 'numeric', 1) != FALSE && number_check($_POST['ToS'], 1) != FALSE)
			{
				$input_username = mysql_real_escape_string($_POST['username']);
				$input_pass = mysql_real_escape_string($_POST['pass']);
				$input_pass2 = mysql_real_escape_string($_POST['pass2']);
				$input_email = mysql_real_escape_string($_POST['email']);
				$input_ToS = mysql_real_escape_string($_POST['ToS']);
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=16");
			}

			$check = mysql_query("SELECT username FROM users WHERE username = '$input_username'")
			or die(mysql_error());
			$check2 = mysql_num_rows($check);

			// If the name exists it gives an error
			if ($check2 != 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=53");
			}

			// This makes sure both passwords entered match
			if ($input_pass != $input_pass2)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=54");
			}

			$input_pass = md5($input_pass);

			// Gets the IP address
			$IP = getenv("REMOTE_ADDR");

			// Now we insert it into the database
			$insert = "INSERT INTO users (username, password, email, ToS, IP) VALUES ('" . $input_username . "', '" . $input_pass . "', '" . $input_email . "', '" . $input_ToS . "', '" . $IP . "')";
			$add_member = mysql_query($insert);

			echo "<strong>Registered</strong><br />";
			echo "Thank you, you have registered - you may now <a href='login.php'>login</a>.";
		}
		else
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=36");
		}
	}
	else
	{
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='main'>
			<tr>
				<th class='form_head' colspan='4'>iC Registration Page</th>
			</tr>
			<tr>
				<td class='message_alert' colspan='4'>*Your username is also your leader name.  This cannot be changed and must be unique.*</td>
			</tr>
			<tr>
				<td></td>
				<td class='input_title_register'>Username:</td>
				<td class='input'><input type='text' name='username' maxlength='20' /></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='input_title_register'>Password:</td>
				<td class='input'><input type='password' name='pass' maxlength='20' /></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='input_title_register'>Confirm Password:</td>
				<td class='input'><input type='password' name='pass2' maxlength='20' /></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='input_title_register'>Email Address:</td>
				<td class='input'><input type='text' name='email' maxlength='60' /></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td class='input_title_register'>You Read the Terms of Service and Agree?:</td>
				<td class='input'><input type='checkbox' name='ToS' value='1' /></td>
				<td></td>
			</tr>
			<tr>
				<td class='link' colspan='4'><a class='link' href='docs/ToS.php'>Terms of Service</a></td>
			</tr>
			<tr>
				<td class='button' colspan='4'><input type='submit' name='submit' value='Register' /></td>
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
?>