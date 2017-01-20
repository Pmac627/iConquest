<?php
/** pm_send.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Sending a Private Message';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

?>
<script language="JavaScript">
<!-- 
function restrict(body)
{
	var maxlength = 1000; // specify the maximum length  if (body.value.length > maxlength)   
	body.value = body.value.substring(0,maxlength);  
}
-->
</script>
<?php

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
		//otherwise they are shown the private message send page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		// grab the recipient out of the URL if page was opened from the reply button in pm_read.php
		$URL_recipient = $_GET['to'];
		$URL_recipient = strip_tags($URL_recipient);

		if(isset($URL_recipient))
		{
			if(sanity_check($URL_recipient, 'string', 20) != FALSE)
			{
				$recipient = mysql_real_escape_string($URL_recipient);
			}
		}

		//if infra purchase form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['recipient'] = strip_tags($_POST['recipient']);
			$_POST['subject'] = strip_tags($_POST['subject']);
			$_POST['body'] = strip_tags($_POST['body']);
			$_POST['ID'] = strip_tags($_POST['ID']);

			//This makes sure they did not leave any fields blank
			if(isset($_POST['recipient'], $_POST['subject'], $_POST['body'], $_POST['ID']))
			{
				if(sanity_check($_POST['recipient'], 'string', 20) != FALSE && sanity_check($_POST['subject'], 'string', 25) != FALSE && sanity_check($_POST['body'], 'string', 1000) != FALSE && sanity_check($_POST['ID'], 'numeric', 6) != FALSE)
				{
					$input_recipient = mysql_real_escape_string($_POST['recipient']);
					$input_subject = mysql_real_escape_string($_POST['subject']);
					$input_body = mysql_real_escape_string($_POST['body']);
					$input_ID = mysql_real_escape_string($_POST['ID']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=36");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			$check = mysql_query("SELECT username FROM users WHERE username = '$input_recipient'")
			or die(mysql_error());
			$check2 = mysql_num_rows($check);

			//if the name doesn't  exists it gives an error
			if ($check2 == 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=47");
			}

			// collect the nation ID that corresponds with the recipient
			$result = mysql_query("SELECT ID FROM users WHERE username = '$input_recipient'") or die(mysql_error());
			$IDcheck2 = mysql_fetch_array($result) or die(mysql_error());
			$ID_recip = $IDcheck2['ID'];

			$ID_send = $input_ID;
			$time_sent = gmdate('U');

			//check to see if they are sending it to themselves
			if ($ID_send == $ID_recip)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=48");
			}

			// create the private message in the database
			$insert = "INSERT INTO private_message (ID_recip, ID_send, subject, time_sent, body) VALUES ('" . $ID_recip . "', '" . $ID_send . "', '" . $input_subject . "', '" . $time_sent . "', '" . $input_body . "')";
			$add_message = mysql_query($insert);

			//then redirect them to the nation
			echo "<META HTTP-EQUIV='Refresh' Content='0; URL=nation.php?ID=" . $ID . "'>";
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<td class='list_central_nav' colspan='4'><a class='link_inline' href='pm_central.php'>Inbox</a> | <a class='link_inline' href='pm_outbox.php'>Outbox</a></td>
			</tr>
			<tr>
				<th class='list_central_header' colspan='4'>Private Message Central</th>
			</tr>
			<tr>
				<th class='list_central_sub_header' colspan='4'>Writing a Private Message</th>
			</tr>
			<tr>
				<td class='pm_title' width='70'>Recipient:</td>
				<td class='pm_info'><input type='text' name='recipient' value='<?php echo $recipient; ?>' maxlength='20' /></td>
			</tr>
			<tr>
				<td class='pm_title'>Subject:</td>
				<td class='pm_info'><input type='text' name='subject' maxlength='25' /></td>
			</tr>
			<tr>
				<td class='search_box' colspan='4'>
				<table class='search_box'>
					<tr>
						<td></td>
						<td colspan='4'><textarea name='body' rows='12' cols='60' onkeyup="restrict(this.form.body)">There is a 1000 word limit...</textarea></td>
						<td></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td class='button' colspan='4'><input type='submit' name='submit' value='Send PM' />
				<input type='hidden' name='ID' value='<?php echo $ID; ?>' /></td>
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