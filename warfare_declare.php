<?php
/** warfare_declare.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Declare War';
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
	if($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		// Otherwise they are shown the warfare declare page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		// Grab the recipient out of the URL
		$URL_defend = $_GET['ID'];
		$URL_defend = strip_tags($URL_defend);

		if(isset($URL_defend))
		{
			if(sanity_check($URL_defend, 'numeric', 11) != FALSE)
			{
				$ID_defend = mysql_real_escape_string($URL_defend);
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=70");
			}
		}
		else
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=70");
		}

		// If trade offer form is submitted
		if(isset($_POST['submit']))
		{
			$_POST['ID_defend'] = strip_tags($_POST['ID_defend']);
			$_POST['ID_me'] = strip_tags($_POST['ID_me']);

			if(isset($_POST['ID_defend'], $_POST['ID_me']))
			{
				if(sanity_check($_POST['ID_defend'], 'numeric', 6) != FALSE && sanity_check($_POST['ID_me'], 'numeric', 6) != FALSE)
				{
					$ID_defend = mysql_real_escape_string($_POST['ID_defend']);
					$ID_attack = mysql_real_escape_string($_POST['ID_me']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=71");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			$war_date = gmdate('U');

			$check = mysql_query("SELECT ID FROM users WHERE ID = '$ID_defend'")
			or die(mysql_error());
			$check2 = mysql_num_rows($check);

			// If the ID doesn't  exists it gives an error
			if($check2 == 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=37");
			}

			// Check to see if they are sending it to themselves
			if($ID_attack == $ID_defend)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=72");
			}

			// Counts how many wars I have declared/have with them
			$result8 = mysql_query("SELECT ID_war FROM warfare WHERE ID_attack = '$ID_attack' AND ID_defend = '$ID_defend' AND war_stat = '0' OR war_stat = '1' OR war_stat = '2'") or die(mysql_error());
			while($res = mysql_fetch_array($result8))
			{
				$count_duplicates++;
			}
			// Counts how many wars they have declared/have with me
			$result8 = mysql_query("SELECT ID_war FROM warfare WHERE ID_defend = '$ID_attack' AND ID_attack = '$ID_defend' AND war_stat = '0' OR war_stat = '1' OR war_stat = '2'") or die(mysql_error());
			while($res = mysql_fetch_array($result8))
			{
				$count_duplicates2++;
			}

			// Gives an error message if they have one deal already
			if($count_duplicates > 1 || $count_duplicates2 > 1)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=73");
			}

			// Counts how many wars I have declared/have
			$result8 = mysql_query("SELECT ID_war FROM warfare WHERE ID_attack = '$ID_attack' AND war_stat = '0' OR war_stat = '2' OR war_stat = '4'") or die(mysql_error());
			while($res = mysql_fetch_array($result8))
			{
				$count_attack++;
			}

			if($count_attack >= 3)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=74");
			}

			// Format the private message
			$ID_recip = $ID_defend;
			$ID_send = $ID_attack;

			// Collect the username that corresponds with the sender
			$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_send'") or die(mysql_error());
			$IDcheck3 = mysql_fetch_array($result2) or die(mysql_error());
			$sender = stripslashes($IDcheck3['username']);

			$subject = "War!";
			$subject = mysql_real_escape_string($subject);

			$body = "War has been declared against you by " . $sender . ".\n\n Check out your Military Command page for more details.\n\n The iC Moderation Team";

			$body = mysql_real_escape_string($body);
			$time_sent = gmdate('U');

			// Create the private message in the database
			$insert1 = "INSERT INTO private_message (ID_recip, ID_send, subject, time_sent, body) VALUES ('" . $ID_recip . "', '" . $ID_send . "', '" . $subject . "', '" . $time_sent . "', '" . $body . "')";
			$add_message1 = mysql_query($insert1);

			// Create the war in the database
			$insert2 = "INSERT INTO warfare (ID_attack, ID_defend, war_date) VALUES ('" . $ID_attack . "', '" . $ID_defend . "', '" . $war_date . "')";
			$add_message2 = mysql_query($insert2);

			$att_used_date = gmdate('U');

			// Make the units used marker today
			$insert = "UPDATE deployed SET att_used_date='" . $att_used_date . "' WHERE ID='" . $ID . "'";
			$add_member = mysql_query($insert);

			// Then redirect them to the nation
			header("Location: military_command.php");
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<th class='list_central_header'>Declaring War</th>
			</tr>
			<tr>
				<td class='list_central_instructions'>This is for declaring war.  If you are 100% certain you want to
					declare on this nation, click the button below. There is no undo.
					Good luck, soldier!</td>
			</tr>
			<tr>
				<td class='button'><input type='submit' name='submit' value='Declare War' />
					<?php echo "
								<input type='hidden' name='ID_defend' value='" . $ID_defend . "' />
								<input type='hidden' name='ID_me' value='" . $ID . "' />" ?></td>
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
	// If the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>