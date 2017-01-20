<?php
/** nation_ban_manager.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();
include ('../functions/side_menu_functions.php');
include ('../functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'sub';
$route = '../';
$page_title_name = 'Ban/Unban A Nation';
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
			$switch_stats = mysql_query("SELECT site_online, version FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
			while($switch = mysql_fetch_array( $switch_stats ))
			{
				$site_online = stripslashes($switch['site_online']);
				$ic_version_marker = stripslashes($switch['version']);
			}

			site_online($site_online, $mod_admin);

			include ('../header.php');

			// Determine which side menu to use
			which_side_menu($ID, $mod_admin, $site_area);

			// If the nation settings form is submitted
			if (isset($_POST['find']))
			{
				$ID = $_POST['ID'];

				// Collect the nation leader that corresponds with ID
				$result = mysql_query("SELECT * FROM users WHERE ID = '$ID'") or die(mysql_error());
				while($verify = mysql_fetch_array($result))
				{
					$ID_verify = $verify['ID'];
					$ban_value = $verify['nat_exist'];
				}

				if($ID == $ID_verify)
				{
					// Grab all of the nation data we can to be edited
					// Collect the nation information for display from the nations table
					$nationstats = mysql_query("SELECT * FROM nations WHERE ID = '$ID'") or die(mysql_error());
					while($row = mysql_fetch_array( $nationstats ))
					{
						// Collect the raw data from the nations table in the db 
						$nation = $row['nation'];
					}

					// Insert change form
					?>
					<td>
					<form action='../admin/complete_ban_edit.php' method='post'>
					<table border='1' width='600'>
					<tr>
						<th colspan='2'>Admin Terminal</th>
					</tr>
					<tr>
						<td colspan='2'><em>Managing Ban of <?php echo $nation ?></em></td>
					</tr>
					<tr>
						<td colspan='2'><strong><em><font color='red'><center>BE VERY CAREFUL WHEN BANNING A NATION!</center></font></em></strong></td>
					</tr>
					<tr>
						<td width='150'>Nation ID: </td>
						<td><input type='text' readonly='readonly' name='ID' value='<?php echo $ID ?>' /></td>
					</tr>
					<tr>
						<?php
						// Switch to retrieve previous peace_war selection from db and making it the selected item in the form.
						switch ($ban_value)
						{
							case "1":
							$_1="selected";
							break;
							case "2":
							$_2="selected";
							break;
							case "3":
							$_3="selected";
							break;
						}
						echo "
							<td>Ban Setting: </td>
							<td>
								<select name='ban_value'>
								<option value='1' $_1>Nation Exists</option>
								<option value='2' $_2>Nation Banned</option>
								<option value='3' $_3>Nation Deleted</option>
							</td>
							</tr>"; ?>
					<tr>
						<td colspan='2' align='center'><input type='submit' name='change' value='Change' /></td>
					</tr>
					</form>
					</table>
				<?php
				}
				else
				{
					echo "Nation does not exist.";
				}
			}

			// Check to see what type of user they are
			// 0 = player; 1 = mod; 2 = admin
			if($IDcheck['mod_admin'] == 2)
			{
				?>
				<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
				<table border='1' width='600'>
				<tr>
					<th colspan='2'>Admin Terminal</th>
				</tr>
				<tr>
					<td colspan='2'><em>Manage a Nation's Ban</em></td>
				</tr>
				<tr>
					<td>Enter a Nation ID: </td>
					<td><input type='text' name='ID' maxlength='11' value='<?php echo $ID_verify ?>' /></td>
				</tr>
				<tr>
					<td colspan='2' align='center'><input type='submit' name='find' value='Find' /></td>
				</tr>
				</form>
				</table>
				</td>
				</tr>
				</table>
				<?php
				include ('../footer.php');
			}
		}
	}
}
else
{
	// If the cookie does not exist, they are taken to the login screen
	header("Location: ../login.php");
}
?>