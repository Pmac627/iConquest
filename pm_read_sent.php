<?php
/** pm_read_sent.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Reading Sent Private Message';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

//checks cookies to make sure they are logged in
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

	//if the cookie has the wrong password, they are taken to the expired session login page
	if ($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		//otherwise they are shown the private message read page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		$sender = $username;
		$URL_ID_message = $_GET['ID_message'];
		$URL_ID_message = strip_tags($URL_ID_message);

		// grab the message ID out of the URL from the message central page's link to this message
		if(isset($URL_ID_message))
		{
			if(sanity_check($URL_ID_message, 'numeric', 11) != FALSE)
			{
				$ID_message = mysql_real_escape_string($URL_ID_message);
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=44");
			}
		}
		else
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=44");
		}

		//collect the message information for display from the private_message table
		$message_stats = mysql_query("SELECT ID_send, ID_recip, subject, body, time_sent FROM private_message WHERE ID_message = '$ID_message'") or die(mysql_error("error at message_stats call"));
		while($pm = mysql_fetch_array( $message_stats ))
		{
			$ID_recip = stripslashes($pm['ID_recip']);
			$ID_send = stripslashes($pm['ID_send']);
			$subject = stripslashes($pm['subject']);
			$body = stripslashes($pm['body']);
			$time = stripslashes($pm['time_sent']);
		}

		if($ID != $ID_send)
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=46");
		}

		// Convert the creation Unix timestamp into a date & time
		$raw_time = $time;
		$formatted_time = date('Y-m-d', $raw_time);

		//collect the recipient's information for display from the users table
		$PM_recip = mysql_query("SELECT username FROM users WHERE ID = '$ID_recip'") or die(mysql_error("error at pm_recip call"));
		while($recip = mysql_fetch_array( $PM_recip ))
		{
			// put $ID_recip to a recipient name
			$recipient = stripslashes($recip['username']);
		}

		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get'>
		<table class='list_central'>
			<tr>
				<td class='list_central_nav' colspan='4'><a class='link_inline' href='pm_central.php'>Inbox</a> | <a class='link_inline' href='pm_outbox.php'>Outbox</a></td>
			</tr>
			<tr>
				<th class='list_central_header' colspan='4'>Private Message Central</th>
			</tr>
			<tr>
				<th class='list_central_sub_header' colspan='4'>Reading "<?php echo $subject; ?>"</th>
			</tr>
			<tr>
				<td class='pm_title' width='50'>To:</td>
				<td class='pm_info' width='200'><?php echo $recipient; ?></td>
				<td class='pm_title' width='50'>From:</td>
				<td class='pm_info'><?php echo $sender; ?></td>
			</tr>
			<tr>
				<td class='pm_title'>Subject:</td>
				<td class='pm_info'><?php echo $subject; ?></td>
				<td class='pm_title'>Sent at: </td>
				<td class='pm_info'><?php echo $formatted_time; ?></td>
			</tr>
			<tr>
				<td class='search_box' colspan='4'>
				<table class='search_box'>
					<tr>
						<td></td>
						<td class='pm_body' colspan='2'><textarea readonly='readonly' name='body' rows='12' cols='60'><?php echo $body; ?></textarea></td>
						<td></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td class='button' colspan='4'><input type='button' onClick="parent.location='pm_outbox.php'" name='back' value='Back to PM Central | Outbox' /></td>
			</tr>
		</table>
		</form>
		</td>
		</tr>
		</table>
		<?php
		include ('footer.php');
	}
}
else
{
	//if the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>