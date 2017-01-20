<?php
/** error_page.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/switchboard_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/error_message_function.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Error';
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
	if ($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		// Otherwise they are shown the trade offer page

		$error_ID = $_GET['error'];

		if(isset($error_ID))
		{
			if(sanity_check($error_ID, 'numeric', 3) != FALSE)
			{
				$error_fubar = mysql_real_escape_string($error_ID);
			}
			else
			{
				die("The only error is that your error code doesn't have an error message!");
			}
		}
		else
		{
			die("Are you trying to beat the error system?");
		}

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		include ('header.php');

		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<table align='center' border='0'>
			<tr valign='top'>
				<td>
					<tr>
						<td>
							<table class='main_display'>
								<tr>
									<td>
										<?php echo error_fubar($error_fubar); ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</td>
			</tr>
		</table>
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