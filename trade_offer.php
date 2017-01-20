<?php
/** trade_offer.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/res_to_image.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Offer Resource Trade';
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
		//otherwise they are shown the trade offer page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		$ID_offer = $ID;

		// grab the recipient out of the URL
		$URL_recipient = $_GET['ID'];
		$URL_recipient = strip_tags($URL_recipient);

		if(isset($URL_recipient))
		{
			if(sanity_check($URL_recipient, 'numeric', 7) != FALSE)
			{
				$ID_recip = $URL_recipient;
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=63");
			}
		}
		else
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=63");
		}

		//determine my resources
		$res_2 = mysql_query("SELECT resource_1, resource_2 FROM nation_variables WHERE ID = '$ID_offer'") or die(mysql_error());
		while($mine = mysql_fetch_array($res_2))
		{
			$my_resource1 = stripslashes($mine['resource_1']);
			$my_resource2 = stripslashes($mine['resource_2']);
		}

		//determine their resources
		$res_1 = mysql_query("SELECT resource_1, resource_2 FROM nation_variables WHERE ID = '$ID_recip'") or die(mysql_error());
		while($their = mysql_fetch_array($res_1))
		{
			$their_resource1 = stripslashes($their['resource_1']);
			$their_resource2 = stripslashes($their['resource_2']);
		}

		//if trade offer form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['URL_recip'] = strip_tags($_POST['URL_recip']);

			if(isset($_POST['URL_recip']))
			{
				if(sanity_check($_POST['URL_recip'], 'numeric', 6) != FALSE)
				{
					$ID_recip = mysql_real_escape_string($_POST['URL_recip']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=59");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			$check = mysql_query("SELECT ID FROM users WHERE ID = '$ID_recip'")
			or die(mysql_error());
			$check2 = mysql_num_rows($check);

			//if the ID doesn't  exists it gives an error
			if ($check2 == 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=37");
			}

			//check to see if they are sending it to themselves
			if ($ID_offer == $ID_recip)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=64");
			}

			// counts how many offers I have sent/have
			$result8 = mysql_query("SELECT ID_trade FROM resource_trade WHERE ID_offerer = '$ID_offer' AND ID_recip = '$ID_recip'") or die(mysql_error());
			while($res = mysql_fetch_array($result8))
			{
				$count_duplicates++;
			}

			// gives an error message if they have one deal already
			if($count_duplicates > 1)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=65");
			}

			// counts how many offers I have sent/have
			$result8 = mysql_query("SELECT ID_trade FROM resource_trade WHERE ID_offerer = '$ID_offer'") or die(mysql_error());
			while($res = mysql_fetch_array($result8))
			{
				$count_me++;
			}

			// counts how many offers they have sent/have
			$result8 = mysql_query("SELECT ID_trade FROM resource_trade WHERE ID_recip = '$ID_recip'") or die(mysql_error());
			while($res = mysql_fetch_array($result8))
			{
				$count_them++;
			}

			if($count_me >= 5)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=66");
			}
			elseif($count_them >= 5)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=67");
			}
			else
			{
				// format the private message
				$ID_send = $ID_offer;

				// collect the username that corresponds with the sender
				$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_send'") or die(mysql_error());
				$IDcheck3 = mysql_fetch_array($result2) or die(mysql_error());
				$sender = $IDcheck3['username'];

				$subject = "Trade Offer Arrived!";
				$subject = mysql_real_escape_string($subject);
				$body = "You have recieved a trade offer from " . $sender . ".\n\nYou may view the offer made in your trade contracts area.\n\nThe iC Moderation Team";
				$body = mysql_real_escape_string($body);
				$time_sent = gmdate('U');

				// create the private message in the database
				$insert = "INSERT INTO private_message (ID_recip, ID_send, subject, time_sent, body) VALUES ('" . $ID_recip . "', '" . $ID_send . "', '" . $subject . "', '" . $time_sent . "', '" . $body . "')";
				$add_message = mysql_query($insert);

				// prepare the resource trade input
				$ID_offerer = $ID_send;
				$trade_date = gmdate('U');	

				// create the resource trade in the database
				$insert2 = "INSERT INTO resource_trade (ID_offerer, ID_recip, trade_date) VALUES ('" . $ID_offerer . "', '" . $ID_recip . "', '" . $trade_date . "')";
				$add_message2 = mysql_query($insert2);

				//then redirect them to the nation
				header("Location: resource_trade.php");
			}
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID_offer, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='2'>Offering a Trade</th>
			</tr>
			<tr>
				<td class='list_central_row_title'>I'll Offer:</td>
				<td class='list_central_row_info'><?php echo res_to_image($my_resource1) . res_to_image($my_resource2); ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>I'll Recieve:</td>
				<td class='list_central_row_info'><?php echo res_to_image($their_resource1) . res_to_image($their_resource2); ?></td>
			</tr>
			<tr>
				<td class='button' colspan='2'><input type='submit' name='submit' value='Offer Trade' />
					<?php echo "<input type='hidden' name='URL_recip' value='" . $URL_recipient . "' />" ?></td>
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