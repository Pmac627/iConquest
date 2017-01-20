<?php
/** switchboard.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();
include ('../functions/side_menu_functions.php');
include ('../functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'sub';
$route = '../';
$page_title_name = 'Switchboard';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$pass = $_COOKIE['Key_i_Conquest'];
	$check = mysql_query("SELECT * FROM users WHERE username = '$username'")or die(mysql_error());
	while($info = mysql_fetch_array( $check ))
	{
		// If the cookie has the wrong password, they are taken to the login page
		if ($pass != $info['password'])
		{
			header("Location: ../login.php");
		}
		else
		{
			// Otherwise they are shown the members area

			// Collect the nation ID that corresponds with the username
			$query = "SELECT * FROM users";
			$result = mysql_query("SELECT ID, mod_admin FROM users WHERE username = '$username'") or die(mysql_error());
			$IDcheck = mysql_fetch_array($result) or die(mysql_error());
			$ID = $IDcheck['ID'];
			$mod_admin = $IDcheck['mod_admin'];
			if($mod_admin != 2)
			{
				header("Location: ../login.php");
			}

			// Collect the switchboard information
			$switch_stats = mysql_query("SELECT site_online, version, multiple_nations, new_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
			while($switch = mysql_fetch_array( $switch_stats ))
			{
				$site_online = stripslashes($switch['site_online']);
				$version = stripslashes($switch['version']);
				$multiple_nations = stripslashes($switch['multiple_nations']);
				$new_nations = stripslashes($switch['new_nations']);
			}

			site_online($site_online, $mod_admin);
			$ic_version_marker = $version;

			include ('../header.php');

			// Determine which side menu to use
			which_side_menu($ID, $mod_admin, $site_area);

			?>
				<form action='../admin/switchboard_process.php' method='post'>
				<table border='1' width='600'>
				<tr>
					<th colspan='2'>Admin Terminal</th>
				</tr>
				<tr>
					<td colspan='2'><em>Switchboard</em></td>
				</tr>
				<tr>
					<td colspan='2'><strong><em><font color='red'><center>BE VERY CAREFUL WHEN CHANGING THE SWITCHBOARD!</center></font></em></strong></td>
				</tr>
					<?php
					// Switch to select the current site_online status
					switch ($site_online)
					{
						case "0":
						$_1a="selected";
						break;
						case "1":
						$_2a="selected";
						break;
						case "2":
						$_3a="selected";
						break;
					}
					echo "
						<tr>
							<td width='150'>Site Online: </td>
							<td>
								<select name='site_online'>
								<option value='0' $_1a>On line</option>
								<option value='1' $_2a>Off line</option>
								<option value='2' $_3a>Admin Only</option>
							</td>
						</tr>"; ?>
				<tr>
					<td>Version Number: </td>
					<td><input type='text' name='version' maxlength='12' value='<?php echo $version ?>' /></td>
				</tr>
					<?php
					// Switch to select the current site_online status
					switch ($multiple_nations)
					{
						case "0":
						$_1b="selected";
						break;
						case "1":
						$_2b="selected";
						break;
						case "2":
						$_3b="selected";
						break;
					}
					echo "
						<tr>
							<td width='150'>Multiple Nations: </td>
							<td>
								<select name='multiple_nations'>
								<option value='0' $_1b>Allow</option>
								<option value='1' $_2b>Disallow</option>
								<option value='2' $_3b>Admin Only</option>
							</td>
						</tr>";

					// Switch to select the current site_online status
					switch ($new_nations)
					{
						case "0":
						$_1c="selected";
						break;
						case "1":
						$_2c="selected";
						break;
					}
					echo "
						<tr>
							<td width='150'>New Nations: </td>
							<td>
								<select name='new_nations'>
								<option value='0' $_1c>Allow</option>
								<option value='1' $_2c>Disallow</option>
							</td>
						</tr>";
					?>
				<tr>
					<td colspan='2' align='center'><input type='submit' name='change' value='Change' /></td>
				</tr>
				</form>
				</table>
			<?php
			include ('../footer.php');
		}
	}
}
else
{
	// If the cookie does not exist, they are taken to the login screen
	header("Location: ../login.php");
}
?>