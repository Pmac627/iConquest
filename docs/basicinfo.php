<?php
/** basicinfo.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();
$conn = mysql_connect("localhost", "pmac627_conquest", "iconquest101") or die(mysql_error());
include ('../functions/side_menu_functions.php');
include ('../functions/days_since_functions.php');
include ('../functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'sub';
$mod_admin = 3;
$route = '../';
$page_title_name = 'Basic Game Information';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$check = mysql_query("SELECT ID, mod_admin FROM users WHERE username = '$username'")or die(mysql_error());
	if($info = mysql_fetch_array( $check ))
	{
		$ID = $info['ID'];
		$mod_admin = $info['mod_admin'];
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
}
else
{
	include ('../header.php');
	side_menu_logout_sub();
}
?>
<td>
<table class='main'>
	<tr>
		<th class='form_head'>Quick Reference Guide</th>
	</tr>
	<?php
	$page = 3;
	$ID_admin = 18;
	$sql = "SELECT title, post_date, body FROM mod_admin_post WHERE page='$page' AND ID_mod_admin='$ID_admin' ORDER BY title ASC";
	$result = mysql_query($sql, $conn) or trigger_error("SQL", E_USER_ERROR);
	while ($list = mysql_fetch_assoc($result))
	{
		$title = stripslashes($list['title']);
		$post_date = stripslashes($list['post_date']);
		$body = stripslashes($list['body']);

		$recent_post = days_since_calculation($post_date);
		$new_fubar = " ";

		if($recent_post <= 7)
		{
			$new_fubar = "<font color='red'>!</font>";
		}

		echo "  <tr>
					<td class='index_message_spacer'></td>
				</tr>
				<tr>
					<td>
						<table class='index_message_box' cellpadding='0' cellspacing='0'>
							<tr>
								<td>
									<table class='index_message_box_mini_no_author' cellpadding='0' cellspacing='0'>
										<tr>
											<td class='index_box_center'>" . " " . $new_fubar . " " . $title . " " . $new_fubar . "</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class='index_message_shell'>
									<p class='index_message_body'>" . $body . "</p>
								</td>
							</tr>
						</table>
					</td>
				</tr>";
	}
	?>
</table>
</td>
</tr>
</table>
<?php
include ('../footer.php');
?>