<?php
/** trade_cancel.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/res_to_image.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Cancel Resource Trade';
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

		$URL_ID_trade = $_GET['ID_trade'];
		$URL_ID_trade = strip_tags($URL_ID_trade);

		if(isset($URL_ID_trade))
		{
			if(sanity_check($URL_ID_trade, 'numeric', 7) != FALSE)
			{
				$ID_trade = $URL_ID_trade;
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=62");
			}
		}
		else
		{
			// Redirect them to the error page
			header("Location: error_page.php?error=62");
		}

		// grab the offer guy's ID
		$result2 = mysql_query("SELECT ID_offerer FROM resource_trade WHERE ID_trade = '$ID_trade'") or die(mysql_error());
		while($offer_guy = mysql_fetch_array($result2))
		{
			$ID_offerer = stripslashes($offer_guy['ID_offerer']);
		}

		//determine their resources
		$result3 = mysql_query("SELECT resource_1, resource_2 FROM nation_variables WHERE ID = '$ID_offerer'") or die(mysql_error());
		while($their = mysql_fetch_array($result3))
		{
			$resource1 = stripslashes($their['resource_1']);
			$resource2 = stripslashes($their['resource_2']);
		}

		//if trade offer form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['URL_ID_trade'] = strip_tags($_POST['URL_ID_trade']);

			if(isset($_POST['URL_ID_trade']))
			{
				if(sanity_check($_POST['URL_ID_trade'], 'numeric', 7) != FALSE)
				{
					$ID_trade = mysql_real_escape_string($_POST['URL_ID_trade']);
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

			// set the trade status to canceled
			$trade_stat = 2;

			// update to canceled trade
			$insert = "UPDATE resource_trade SET trade_stat='" . $trade_stat . "' WHERE ID_trade='" . $ID_trade . "'";
			$add_member = mysql_query($insert);

			//then redirect them to the nation
			header("Location: resource_trade.php");
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
			<th class='list_central_header' colspan='2'>Canceling a Trade</th>
		</tr>
		<tr>
			<td class='list_central_row_title'>I'll Lose:</td>
			<td class='list_central_row_info'><?php echo res_to_image($resource1) . res_to_image($resource2); ?></td>
		</tr>
		<tr>
			<td class='button' colspan='2'><input type='submit' name='submit' value='Cancel Trade' />
				<?php echo "<input type='hidden' name='URL_ID_trade' value='" . $ID_trade . "' />" ?></td>
		</tr>
	</table>
	</form>
	</td>
	</tr>
	</table>
	<?php
	include ('footer.php');
}
else
{
	//if the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>