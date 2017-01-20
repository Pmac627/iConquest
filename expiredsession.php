<?php
/** expired_session.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$mod_admin = 3;
$page_title_name = 'Session Expired';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

// Grab the last URL they came from
$ref = getenv('HTTP_REFERER');

// Checks if there is a login cookie
if(isset($_COOKIE['ID_i_Conquest']))
// If there is, it logs you in and directes you to the nation page
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

	if($pass != $info_pass)
	{
	}
	else
	{
		header("Location: nation.php?ID=$ID");
	}
}

// Collect the switchboard information
$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
while($switch = mysql_fetch_array( $switch_stats ))
{
	$site_online = stripslashes($switch['site_online']);
	$ic_version_marker = stripslashes($switch['version']);
}

site_online($site_online, $mod_admin);

// If the login form is submitted
if (isset($_POST['submit']))
{
	$_POST['username'] = strip_tags($_POST['username']);
	$_POST['pass'] = strip_tags($_POST['pass']);

	// Makes sure they filled it in
	if(isset($_POST['username'], $_POST['pass']))
	{
		// Sanity Checks!
		if(sanity_check($_POST['username'], 'string', 20) != FALSE && sanity_check($_POST['pass'], 'string', 20) != FALSE)
		{
			$input_username = mysql_real_escape_string($_POST['username']);
			$input_pass = mysql_real_escape_string($_POST['pass']);
		}
		else
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=16");
		}

		$check = mysql_query("SELECT username, password FROM users WHERE username = '" . $input_username . "'")or die(mysql_error());

		// Gives error if user dosen't exist
		$check2 = mysql_num_rows($check);
		if ($check2 == 0)
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=27");
		}
		
		while($info = mysql_fetch_array( $check ))
		{
			$info_password = $info['password'];
			$input_pass = md5($input_pass);

			// Gives error if the password is wrong
			if ($input_pass != $info_password)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=28");
			}
			else
			{
				// If login is ok then we add a cookie
				$input_username = stripslashes($input_username);
				$hour = time() + 3600;
				setcookie(ID_i_Conquest, $input_username, $hour);
				setcookie(Key_i_Conquest, $input_pass, $hour);

				header("Location: $ref");
			}
		}
	}
	else
	{
		// Redirect them to the error page
		header("Location: error_page.php?error=1");
	}
}
else
{
	// If they are not logged in
	include ('header.php');
	
	// Determine which side menu to use
	which_side_menu($ID, $mod_admin, $site_area);	
	?>
	<td>
	<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
	<table class='main'>
		<tr>
			<td class='message_standard' colspan='4'>Your session in iConquest has expired.  Please log back in to continue playing. Sorry!</td>
		</tr>
		<tr>
			<th class='form_head' colspan='4'>iC Login Page</th>
		</tr>
		<tr>
			<td></td>
			<td class='input_title'>Username:</td>
			<td class='input'><input type='text' name='username' maxlength='20' /></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td class='input_title'>Password:</td>
			<td class='input'><input type='password' name='pass' maxlength='20' /></td>
			<td></td>
		</tr>
		<tr>
			<td class='button' colspan='4'><input type='submit' name='submit' value='Login' /></td>
		</tr>
	</table>
	</form>
	</td>
	</tr>
	</table>
	<?php
	include ('footer.php');
}
?>