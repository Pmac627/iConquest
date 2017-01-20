<?php
/** warfare_peace.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Offer Peace';
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
	if($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		//otherwise they are shown the warfare peace page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		// grab the recipient out of the URL
		$URL_ID_them = $_GET['ID'];
		$URL_ID_war = $_GET['war'];
		$URL_ID_them = strip_tags($URL_ID_them);
		$URL_ID_war = strip_tags($URL_ID_war);

		if(isset($URL_ID_them, $URL_ID_war))
		{
			if(sanity_check($URL_ID_them, 'numeric', 11) != FALSE && sanity_check($URL_ID_war, 'numeric', 7) != FALSE)
			{
				$URL_them = mysql_real_escape_string($URL_ID_them);
				$URL_war = mysql_real_escape_string($URL_ID_war);
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=88");
			}
		}
		else
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=88");
		}

		//if trade offer form is submitted
		if(isset($_POST['submit']))
		{
			$_POST['URL_them'] = strip_tags($_POST['URL_them']);
			$_POST['ID_me'] = strip_tags($_POST['ID_me']);
			$_POST['URL_war'] = strip_tags($_POST['URL_war']);

			if(isset($_POST['URL_them'], $_POST['ID_me'], $_POST['URL_war']))
			{
				if(sanity_check($_POST['URL_them'], 'numeric', 6) != FALSE && sanity_check($_POST['ID_me'], 'numeric', 6) != FALSE && sanity_check($_POST['URL_war'], 'numeric', 7) != FALSE)
				{
					$ID_them = mysql_real_escape_string($_POST['URL_them']);
					$ID_me = mysql_real_escape_string($_POST['ID_me']);
					$ID_war = mysql_real_escape_string($_POST['URL_war']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=89");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			$check = mysql_query("SELECT ID FROM users WHERE ID = '$ID_them'")
			or die(mysql_error());
			$check2 = mysql_num_rows($check);

			//if the ID doesn't  exists it gives an error
			if($check2 == 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=37");
			}

			//check to see if they are sending it to themselves
			if($ID_me == $ID_them)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=90");
			}

			$result2 = mysql_query("SELECT war_stat, ID_attack, ID_defend FROM warfare WHERE ID_war = '$ID_war'") or die(mysql_error());
			while($res = mysql_fetch_array($result2))
			{
				$war_stat = stripslashes($res['war_stat']);
				$ID_attack = stripslashes($res['ID_attack']);
				$ID_defend = stripslashes($res['ID_defend']);
			}

			if($war_stat != 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=91");
			}

			if($ID_attack == $ID_me)
			{
				$war_stat = 1;
			}
			if($ID_defend == $ID_me)
			{
				$war_stat = 2;
			}

			// create the resource trade in the database
			$insert2 = "UPDATE warfare SET war_stat='" . $war_stat . "' WHERE ID_war = '" . $ID_war . "'";
			$add_message2 = mysql_query($insert2);

			//then redirect them to the nation
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
				<th class='list_central_header'>Offering Peace</th>
			</tr>
			<tr>
				<td class='list_central_instructions'>Would you like to end this war?  I think it is about time.</td>
			</tr>
			<tr>
				<td class='button'><input type='submit' name='submit' value='Offer Peace' />
					<?php echo "
								<input type='hidden' name='URL_them' value='" . $URL_them . "' />
								<input type='hidden' name='ID_me' value='" . $ID . "' />
								<input type='hidden' name='URL_war' value='" . $URL_war . "' />" ?></td>
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