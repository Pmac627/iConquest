<?php
/** index.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
$conn = mysql_connect("localhost", "pmac627_conquest", "iconquest101") or die(mysql_error());
include ('functions/side_menu_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$mod_admin = 3;
$page_title_name = 'Welcome';
$meta_restrict = '<meta name="robots" content="index, nofollow" />';

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$pass = $_COOKIE['Key_i_Conquest'];
	$check = mysql_query("SELECT ID, password, nat_exist, mod_admin FROM users WHERE username = '$username'")or die(mysql_error());
	while($info = mysql_fetch_array( $check ))
	{
		$ID = $info['ID'];
		$info_pass = $info['password'];
		$info_nat_exist = $info['nat_exist'];
		$mod_admin = $info['mod_admin'];
	}
}

// Collect the switchboard information
$switch_stats = mysql_query("SELECT site_online, version FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
while($switch = mysql_fetch_array( $switch_stats ))
{
	$site_online = stripslashes($switch['site_online']);
	$ic_version_marker = stripslashes($switch['version']);
}

site_online($site_online, $mod_admin);

// If the cookie has the wrong password, they are taken to the login page
if ($pass != $info_pass)
{
	header("Location: login.php");
}
else
{
	// Otherwise they are shown the members area
	include ('header.php');

	// Determine which side menu to use
	which_side_menu($ID, $mod_admin, $site_area);
	?>
	<td>
	<table class='index'>
		<tr>
			<td class='index_message_section'>A message from the Creator!</td>
		</tr>
		<?php
		$offset = 0;
		$post_rows = 1;
		$page = 0;
		$ID_admin = 1;
		$post_count_admin = 0;
		$count = 0;
		$sql = "SELECT ID_mod_admin, title, post_date, body FROM mod_admin_post WHERE page='$page' AND ID_mod_admin='$ID_admin' ORDER BY ID_post DESC LIMIT $offset, $post_rows";
		$result = mysql_query($sql, $conn) or trigger_error("SQL", E_USER_ERROR);
		while ($list = mysql_fetch_assoc($result))
		{
			$count++;

			$ID_mod_admin = $list['ID_mod_admin'];
			$title = stripslashes($list['title']);
			$post_date = $list['post_date'];
			$body = stripslashes($list['body']);

			// Convert the creation Unix timestamp into a date & time
			$raw_time = $post_date;
			$formatted_post_date = date('Y-m-d', $raw_time);

			// Collect the nation leader that corresponds with the ID
			$query2 = "SELECT * FROM users";
			$result2 = mysql_query("SELECT * FROM users WHERE ID = '$ID_mod_admin'") or die(mysql_error());
			$ruler = mysql_fetch_array($result2) or die(mysql_error());
			$leader = $ruler['username'];

			echo "  <tr>
						<td class='index_message_spacer'></td>
					</tr>
					<tr>
						<td>
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
										<p class='index_message_author'>Posted by " . $leader . " - " . $formatted_post_date . "</p>
										<hr class='index_message_hr' />
										<p class='index_message_body'>" . $body . "</p>
									</td>
								</tr>
							</table>
						</td>
					</tr>";
			$post_count_admin++;
		}

		if($post_count_admin == 0)
		{
			echo "  <tr>
						<td class='message_standard'>There aren't any messages from the creator at this time, sorry!</td>
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
?>