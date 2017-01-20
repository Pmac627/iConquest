<?php
/** admin_posts_all.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
$conn = mysql_connect("localhost", "logtesting", "Adminpmac101") or die(mysql_error());
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Admin Post Archive';
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

	// If the cookie has the wrong password, they are taken to the login page
	if ($pass != $info_pass)
	{
		header("Location: login.php");
	}
	else
	{
		// Otherwise they are shown the members area

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
		<table class='main'>
			<tr>
				<th class='form_head'>Admin's Post Archive</th>
			</tr>
		<?php
		$page = 1;
		$ID_admin = 18;
		$post_count_admin = 0;
		$sql = "SELECT ID_mod_admin, title, post_date, body FROM mod_admin_post WHERE page='$page' AND ID_mod_admin='$ID_admin' ORDER BY ID_post DESC";
		$result = mysql_query($sql, $conn) or trigger_error("SQL", E_USER_ERROR);
		while ($list = mysql_fetch_assoc($result))
		{
			$post_count_admin++;
			$ID_mod_admin = stripslashes($list['ID_mod_admin']);
			$title = stripslashes($list['title']);
			$post_date = stripslashes($list['post_date']);
			$body = stripslashes($list['body']);
			
			// Convert the creation Unix timestamp into a date & time
			$raw_time = $post_date;
			$formatted_post_date = date('Y-m-d', $raw_time);
			
			// Collect the nation leader that corresponds with the ID
			$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_mod_admin'") or die(mysql_error());
			$ruler = mysql_fetch_array($result2) or die(mysql_error());
			$leader = $ruler['username'];

			echo "  <tr>
						<td class='index_message_spacer'></td>
					</tr>
					<tr>
						<td colspan='2'>
							<table class='index_message_box' cellpadding='0' cellspacing='0'>
								<tr>
									<td>
										<table class='index_message_box_mini' cellpadding='0' cellspacing='0'>
											<tr>
												<td class='index_box_center'>" . $title . "</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td class='index_message_shell'>
										<p class='index_message_author'>Posted in iCentral by " . $leader . " - " . $formatted_post_date . "</p>
										<hr class='index_message_hr' />
										<p class='index_message_body'>" . $body . "</p>
									</td>
								</tr>
							</table>
						</td>
					</tr>";
		}
		
		if($post_count_admin == 0)
		{
			echo "  <tr>
						<td class='message_standard'>There aren't any posts by admin at this time, sorry!</td>
					</tr>";
		}
		?>
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
	// If the cookie does not exist, they are taken to the login screen
	header("Location: login.php");
}
?>