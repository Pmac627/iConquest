<?php
/** aid_offer.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Foreign Aid Offer';
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
		//otherwise they are shown the aid offer page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		// grab the recipient out of the URL
		$URL_recipient = $_GET['ID'];
		$URL_recipient = strip_tags($URL_recipient);
		$ID_recip = mysql_real_escape_string($URL_recipient);

		$ID_send = $ID;
		####################
		//HARDCODE THE AID LIMITS
		$MONEY_LIMIT = 500000;
		$TECH_LIMIT = 10;
		$CAPITAL_LIMIT = 0.5;
		$INF_LIMIT = 250;
		####################

		//collect the nation information for display from the nation_variables table
		$nation_var = mysql_query("SELECT treasury, tech, capital FROM nation_variables WHERE ID = '$ID_send'") or die(mysql_error());
		while($row = mysql_fetch_array($nation_var))
		{
			// Collect the raw data from the nation_variables table in the db 
			$treasury = stripslashes($row['treasury']);
			$tech = stripslashes($row['tech']);
			$capital = stripslashes($row['capital']);
		}

		//collect the nation information for display from the military table
		$military = mysql_query("SELECT inf_1, inf_2, inf_3 FROM military WHERE ID = '$ID_send'") or die(mysql_error());
		while($mil = mysql_fetch_array( $military ))
		{
			// Collect the raw data from the military table in the db 
			$inf_1 = stripslashes($mil['inf_1']);
			$inf_2 = stripslashes($mil['inf_2']);
			$inf_3 = stripslashes($mil['inf_3']);
		}

		//if trade offer form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['URL_recip'] = strip_tags($_POST['URL_recip']);
			$_POST['money'] = strip_tags($_POST['money']);
			$_POST['tech'] = strip_tags($_POST['tech']);
			$_POST['cap'] = strip_tags($_POST['cap']);
			$_POST['inf_1'] = strip_tags($_POST['inf_1']);
			$_POST['inf_2'] = strip_tags($_POST['inf_2']);
			$_POST['inf_3'] = strip_tags($_POST['inf_3']);

			if(isset($_POST['URL_recip'], $_POST['money'], $_POST['tech'], $_POST['cap'], $_POST['inf_1'], $_POST['inf_2'], $_POST['inf_3']))
			{
				if(sanity_check($_POST['URL_recip'], 'numeric', 6) != FALSE && sanity_check($_POST['money'], 'numeric', 9) != FALSE && sanity_check($_POST['tech'], 'numeric', 5) != FALSE && sanity_check($_POST['cap'], 'numeric', 3) != FALSE && sanity_check($_POST['inf1'], 'numeric', 5) != FALSE && sanity_check($_POST['inf2'], 'numeric', 5) != FALSE && sanity_check($_POST['inf3'], 'numeric', 5) != FALSE)
				{
					$ID_recip = mysql_real_escape_string($_POST['URL_recip']);
					$aid_money = mysql_real_escape_string($_POST['money']);
					$aid_tech = mysql_real_escape_string($_POST['tech']);
					$aid_cap = mysql_real_escape_string($_POST['cap']);
					$aid_inf1 = mysql_real_escape_string($_POST['inf1']);
					$aid_inf2 = mysql_real_escape_string($_POST['inf2']);
					$aid_inf3 = mysql_real_escape_string($_POST['inf3']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=2");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			$inf_send = ($aid_inf1 + $aid_inf2 + $aid_inf3);

			$check = mysql_query("SELECT ID FROM users WHERE ID = '$ID_recip'")
			or die(mysql_error());
			$check2 = mysql_num_rows($check);

			//if the ID doesn't  exists it gives an error
			if ($check2 == 0)
			{
				die("Sorry, that nation does not exist.");
			}

			//if the money aid is too much, deny them
			if ($aid_money > $MONEY_LIMIT)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=3");
			}

			//if the tech aid is too much, deny them
			if ($aid_tech > $TECH_LIMIT)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=4");
			}

			//if the capital aid is too much, deny them
			if ($aid_cap > $CAPITAL_LIMIT)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=5");
			}

			//if the infantry aid is too much, deny them
			if ($inf_send > $INF_LIMIT)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=6");
			}

			// collect the username that corresponds with the recipient
			$result = mysql_query("SELECT username FROM users WHERE ID = '$ID_recip'") or die(mysql_error());
			$IDcheck2 = mysql_fetch_array($result) or die(mysql_error());
			$recipient = $IDcheck2['username'];

			//check to see if they are sending it to themselves
			if ($ID_send == $ID_recip)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=7");
			}

			// counts how many offers I have sent/have
			$result8 = mysql_query("SELECT ID_aid FROM foreign_aid WHERE ID_send = '$ID_send' AND ID_recip = '$ID_recip'") or die(mysql_error());
			while($res = mysql_fetch_array($result8))
			{
				$count_duplicates++;
			}

			// gives an error message if they have one deal already
			if($count_duplicates > 1)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=8");
			}

			// counts how many offers I have sent/have
			$result8 = mysql_query("SELECT ID_aid FROM foreign_aid WHERE ID_send = '$ID_send'") or die(mysql_error());
			while($res = mysql_fetch_array($result8))
			{
				$count_me++;
			}

			// counts how many offers they have sent/have
			$result8 = mysql_query("SELECT ID_aid FROM foreign_aid WHERE ID_recip = '$ID_recip'") or die(mysql_error());
			while($res = mysql_fetch_array($result8))
			{
				$count_them++;
			}

			if($count_me >= 5)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=9");
			}
			elseif($count_them >= 5)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=10");
			}
			else
			{

				// collect the username that corresponds with the sender
				$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_send'") or die(mysql_error());
				$IDcheck3 = mysql_fetch_array($result2) or die(mysql_error());
				$sender = $IDcheck3['username'];

				$subject = "Aid Offer Arrived!";
				$subject = mysql_real_escape_string($subject);
				$body = "You have recieved an aid offer from " . $sender . ".\n\nYou may view the offer made in your Foreign Transactions area.\n\nThe iC Moderation Team";
				$body = mysql_real_escape_string($body);
				$time_sent = gmdate('U');

				// create the private message in the database
				$insert = "INSERT INTO private_message (ID_recip, ID_send, subject, time_sent, body) VALUES ('" . $ID_recip . "', '" . $ID_send . "', '" . $subject . "', '" . $time_sent . "', '" . $body . "')";
				$add_message = mysql_query($insert);

				// prepare the aid offer input
				$aid_date = gmdate('U');

				// create the resource trade in the database
				$insert2 = "INSERT INTO foreign_aid (ID_send, ID_recip, aid_date, aid_money, aid_tech, aid_cap, aid_inf1, aid_inf2, aid_inf3) VALUES ('" . $ID_send . "', '" . $ID_recip . "', '" . $aid_date . "', '" . $aid_money . "', '" . $aid_tech . "', '" . $aid_cap . "', '" . $aid_inf1 . "', '" . $aid_inf2 . "', '" . $aid_inf3 . "')";
				$add_message2 = mysql_query($insert2);

				//then redirect them to the nation
				header("Location: foreign_aid.php");
			}
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='3'>Offering an Aid Contract</th>
			</tr>
			<tr>
				<td class='list_central_instructions' colspan='3'>	This is the International Aid Offer page.  There are currently a few 
									limits on what you can aid to other nations.  You can only send up to
									<strong>$500,000.00</strong> in monetary aid.  You can only send up to 
									<strong>10.00</strong> units of technology.  You can only send up to 
									<strong>0.5</strong> units of capital.  Finally, you can only send up 
									to <strong>250</strong> infantry units of any combination.</td>
			</tr>
			<tr>
				<td class='list_central_row_title' width='100'>Monetary:</td>
				<td class='list_central_row_info'><input type='text' name='money' maxlength='9' /></td>
				<td class='list_central_row_info' width='100'>($<?php $treasury = number_format($treasury,2);
								echo $treasury; ?>)</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Technology:</td>
				<td class='list_central_row_info'><input type='text' name='tech' maxlength='5' /></td>
				<td class='list_central_row_info'>(<?php $tech = number_format($tech,2);
								echo $tech; ?>)</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Capital:</td>
				<td class='list_central_row_info'><input type='text' name='cap' maxlength='3' /></td>
				<td class='list_central_row_info'>(<?php $capital = number_format($capital,2);
								echo $capital; ?>)</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Infantry 1:</td>
				<td class='list_central_row_info'><input type='text' name='inf1' maxlength='5' /></td>
				<td class='list_central_row_info'>(<?php echo $inf_1; ?>)</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Infantry 2:</td>
				<td class='list_central_row_info'><input type='text' name='inf2' maxlength='5' /></td>
				<td class='list_central_row_info'>(<?php echo $inf_2; ?>)</td>
			</tr>
			<tr>
				<td class='list_central_row_title'>Infantry 3:</td>
				<td class='list_central_row_info'><input type='text' name='inf3' maxlength='5' /></td>
				<td class='list_central_row_info'>(<?php echo $inf_3; ?>)</td>
			</tr>
			<tr>
				<td class='button' colspan='3'><input type='submit' name='submit' value='Offer Aid' />
					<?php echo "<input type='hidden' name='URL_recip' value='" . $ID_recip . "' />" ?></td>
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