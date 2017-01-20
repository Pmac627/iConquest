<?php
/** admin_terminal.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();
include ('../functions/side_menu_functions.php');
include ('../functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'sub';
$route = '../';
$page_title_name = 'Administrative Control Panel';
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
			if($_GET['admin_fubar'] == "editsuccess")
			{
				$admin_fubar = "Nation edit was a success!";
			}
			elseif($_GET['admin_fubar'] == "postsuccess_index")
			{
				$admin_fubar = "Admin Post to the INDEX was a success!";
			}
			elseif($_GET['admin_fubar'] == "postsuccess_icentral")
			{
				$admin_fubar = "Admin Post to the iCENTRAL was a success!";
			}
			elseif($_GET['admin_fubar'] == "postsuccess_version")
			{
				$admin_fubar = "Admin Post to the VERSION INFO was a success!";
			}
			elseif($_GET['admin_fubar'] == "postsuccess_basic")
			{
				$admin_fubar = "Admin Post to the BASIC INFO was a success!";
			}
			
			elseif($_GET['admin_fubar'] == "bansuccess_exists")
			{
				$admin_fubar = "Nation ban setting updated to EXISTS!";
			}
			elseif($_GET['admin_fubar'] == "bansuccess_banned")
			{
				$admin_fubar = "Nation ban setting updated to BANNED!";
			}
			elseif($_GET['admin_fubar'] == "bansuccess_deleted")
			{
				$admin_fubar = "Nation ban setting updated to DELETED!";
			}
			elseif($_GET['admin_fubar'] == "switchboard")
			{
				$admin_fubar = "Switchboard has been edited!";
			}
			else
			{
				$admin_fubar = "";
			}

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
			// Check to see if the user has a nation
			// 0 = no nation; 1 = nation exists; 2 = nation temp-banned; 3 = nation deleted; 
			if($IDcheck['mod_admin'] == 2)
			{
				// Determine which side menu to use
				which_side_menu($ID, $mod_admin, $site_area);

				?>
				<td>
				<table border='1' width='600'>
				<tr>
					<?php echo $admin_fubar ?>
					<th colspan='2'>Admin Terminal</th>
				</tr>
				<tr>
					<td colspan='2'><strong><em><font color='red'><center>BE VERY CAREFUL WHEN PERFORMING ADMINISTRATIVE ACTION!<br />CHANGES CAN DESTROY A NATION!</center></font></em></strong></td>
				</tr>
				<tr>
					<td><em>SWITCHBOARD</em></td>
					<td><a href='switchboard.php'>Alter the Switchboard</a></td>
				</tr>
				<tr>
					<td><em>Edit a Nation</em></td>
					<td><a href='nation_alter.php'>Alter a Nation</a></td>
				</tr>
				<tr>
					<td><em>Edit a Ban</em></td>
					<td><a href='nation_ban_manager.php'>Ban Edit</a></td>
				</tr>
				<tr>
					<td><em>Edit a Page</em></td>
					<td><a href='admin_post.php'>Admin Post</a></td>
				</tr>
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