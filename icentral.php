<?php
/** icentral.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
$conn = mysql_connect("localhost", "pmac627_conquest", "iconquest101") or die(mysql_error());
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');
include ('functions/top_nations_functions.php');
include ('functions/peace_war_name.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'iConquest Today';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

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

		// Check to see if the user has a nation
		// 0 = no nation; 1 = nation exists; 2 = nation temp-banned; 3 = nation deleted; 
		if($info_nat_exist == 1)
		{
			include ('header.php');
			
			// Determine which side menu to use
			which_side_menu($ID, $mod_admin, $site_area);
			?>
			<td>
			<table class='main'>
				<tr>
					<th class='form_head' colspan='4'>Welcome to iConquest Today!</th>
				</tr>
				<tr>
					<td class='index_message_section' colspan='4'>Recent Posts by admin!</td>
				</tr>
			<?php
			$offset = 0;
			$post_rows = 3;
			$page = 1;
			$ID_admin = 18;
			$post_count_admin = 0;
			$count = 0;
			$sql = "SELECT ID_mod_admin, title, post_date, body FROM mod_admin_post WHERE page='$page' AND ID_mod_admin='$ID_admin' ORDER BY ID_post DESC LIMIT $offset, $post_rows";
			$result = mysql_query($sql, $conn) or trigger_error("SQL", E_USER_ERROR);
			while ($list = mysql_fetch_assoc($result))
			{
				$count++;

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
				$leader = stripslashes($ruler['username']);

				echo "  <tr>
							<td class='index_message_spacer' colspan='4'></td>
						</tr>
						<tr>
							<td colspan='4'>
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

				$post_rows_recount = $post_rows - 1;
				if($count > $post_rows_recount)
				{
					echo "  <tr>
								<td class='message_standard' colspan='4'>You can find all posts by admin <a class='link' href='admin_posts_all.php'>here!</a></td>
							</tr>";
				}
			}

			if($post_count_admin == 0)
			{
				echo "  <tr>
							<td class='message_standard' colspan='4'>There aren't any posts by admin at this time, sorry!</td>
						</tr>";
			}
			?>
			<tr>
				<td class='index_message_section' colspan='4'>Recent Posts by the Moderators!</td>
			</tr>
			<?php
			$offset = 0;
			$post_rows = 3;
			$page = 1;
			$count = 0;
			$post_count_mod = 0;
			$sql = "SELECT ID_mod_admin, title, post_date, body FROM mod_admin_post WHERE page='$page' ORDER BY ID_post DESC LIMIT $offset, $post_rows";
			$result = mysql_query($sql, $conn) or trigger_error("SQL", E_USER_ERROR);
			while ($list = mysql_fetch_assoc($result))
			{
				if($ID_mod_admin != 18)
				{
					$count++;

					$ID_mod_admin = stripslashes($list['ID_mod_admin']);
					$title = stripslashes($list['title']);
					$post_date = stripslashes($list['post_date']);
					$body = stripslashes($list['body']);

					// Convert the creation Unix timestamp into a date & time
					$raw_time = $post_date;
					$formatted_post_date = date('Y-m-d', $raw_time);

					// Collect the nation leader that corresponds with the ID
					$result2 = mysql_query("SELECT * FROM users WHERE ID = '$ID_mod_admin'") or die(mysql_error());
					$ruler = mysql_fetch_array($result2) or die(mysql_error());
					$leader = stripslashes($ruler['username']);

					echo "  <tr>
								<td class='index_message_spacer' colspan='4'></td>
							</tr>
							<tr>
								<td colspan='4'>
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
					$post_count_mod++;

					$post_rows_recount = $post_rows - 1;
					if($count > $post_rows_recount)
					{
						echo "  <tr>
									<td class='message_standard' colspan='4'>You can find all posts by the Mod's <a class='link' href='mod_posts_all.php'>here!</a></td>
								</tr>";
					}
				}
			}

			if($post_count_mod == 0)
			{
				echo "  <tr>
							<td class='message_standard' colspan='4'>There aren't any posts by the Mods at this time, sorry!</td>
						</tr>";
			}
			?>
			<tr>
				<td>
					<table class='list_central'>
					<tr>
						<th class='list_central_header' colspan='4'>Top Nations</th>
					</tr>
					<tr>
						<td class='list_central_row_title' width='20'>#</td>
						<td class='list_central_row_title'>Nation</td>
						<td class='list_central_row_title'>National Rating</td>
						<td class='list_central_row_title' width='140'>Peace/War</td>
					</tr>
					<?php
					$count = 0;
					top_nr_desc();
					
					?>
					</table>
				</td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
			<?php
			include ('footer.php');
		}
		elseif($info_nat_exist == 2)
		{
			echo "You already have a nation!";
			echo "<br />";
			echo "However, you are currently under a temporary ban.</a>";
		}
		elseif($info_nat_exist == 3)
		{
			echo "Your nation was deleted!";
		}
		else
		{
			include ('header.php');
			side_menu_logout();
			?>
			<td>
			<table class='main'>
			<tr>
				<th class='form_head'>Welcome to iConquest Today!</th>
			</tr>
			<tr>
				<td>Here you will find lists of the most recent updates, leaderboards, statistics and links to all sorts of materials.</td>
			</tr>
			<tr>
				<td class='message_standard'>You don't have a nation yet though!<br />
				<a class='link' href='nation_creation.php'>Make one here!</a></td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
			<?php
			include ('footer.php');
		}
	}
}
else
{
	// If the cookie does not exist, they are taken to the login screen
	header("Location: login.php");
}
?>