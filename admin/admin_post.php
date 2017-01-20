<?php
/** admin_post.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();
include ('../functions/side_menu_functions.php');
include ('../functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'sub';
$route = '../';
$page_title_name = 'Make A Post';
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
			header("Location: login.php");
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

			?>
			<script language="JavaScript">
			<!--
			function restrict(body)
			{
				// Specify the maximum length  if (body.value.length > maxlength)
				var maxlength = 60000;

				body.value = body.value.substring(0,maxlength);
			}
			-->
			</script>
			<?php
			include ('../header.php');

			// Determine which side menu to use
			which_side_menu($ID, $mod_admin, $site_area);

			?>
			<td>
			<form action='../admin/complete_admin_post.php' method='post'>
				<table border='1' width='600'>
				<tr>
					<th colspan='2'>Admin Terminal</th>
				</tr>
				<tr>
					<td colspan='2'><em>Post on an iC Page</em></td>
				</tr>
				<tr>
					<td>What page to post on?</td>
					<td>
						<select name='page'>
							<option value='99'>...</option>
							<option value='0'>Index Page</option>
							<option value='1'>iCentral Page</option>
							<option value='2'>Version Info Page</option>
							<option value='3'>Basic Info Page</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Title: </td>
					<td>50 character limit!<br /><input type='text' size='55' name='title' maxlength='50' /></td>
				</tr>
				<tr>
					<td colspan='2'>Body: </td>
				</tr>
				<tr>
					<td colspan='2'>60,000 character limit!<br />Use HTML formatting!<br /><textarea rows='20' cols='70' name='body' onkeyup="restrict(this.form.body)"></textarea></td>
				</tr>
				<tr>
					<input type='hidden' name='ID' value='<?php echo $ID; ?>' />
					<td colspan='2' align='center'><input type='submit' name='post' value='Post!' /></td>
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
else
{
	// If the cookie does not exist, they are taken to the login screen
	header("Location: ../login.php");
}
?>