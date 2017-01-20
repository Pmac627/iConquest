<?php
/** forgot_password.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Password Reset';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	header("Location: icentral.php");
}
else
{
	// Otherwise they are shown the forgot password page

	// Collect the switchboard information
	$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
	while($switch = mysql_fetch_array( $switch_stats ))
	{
		$site_online = stripslashes($switch['site_online']);
	}

	site_online_forgot($site_online);

	if(isset($_POST['email']))
	{
		$_POST['email'] = strip_tags($_POST['email']);

		// Convert to simple variables
		if(sanity_check($_POST['email'], 'string', 60) != FALSE && email_check($_POST['email']) != FALSE)
		{
			$email = $_POST['email'];
		}

		$email = mysql_real_escape_string($email);
		$status = "OK";
		$msg = "";

		// Error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR);
		if (!stristr($email,"@") OR !stristr($email,"."))
		{
			$msg = "Your email address is not correct<BR>";
			$status = "NOTOK";
		}

		echo "<br><br>";
		if($status=="OK")
		{
			$query = "SELECT email, username FROM users WHERE email = '$email'";
			$st = mysql_query($query);
			$recs = mysql_num_rows($st);
			$row = mysql_fetch_object($st);
			// Email is stored to a variable
			$em = $row->email;
			if ($recs == 0)
			{
				echo "Your address is not in our database. You can signup and login to use our site.<br /><a href='register.php'>Register</a>";
				exit;
			}
			function makeRandomPassword()
			{
				$salt = "abcdefghjkmnpqrstuvwxyz0123456789";
				srand((double)microtime()*1000000);
				$i = 0;
				while ($i <= 7)
				{
					$num = rand() % 33;
					$tmp = substr($salt, $num, 1);
					$pass = $pass . $tmp;
					$i++;
				}
				return $pass;
			}

			$random_password = makeRandomPassword();
			$db_password = md5($random_password);

			$sql = mysql_query("UPDATE users SET password='" . $db_password . "' WHERE email='" . $email . "'"); 
			$subject = "Your password at www.internationalconquest.net";
			$message = "Hi, we have reset your password.
New Password: $random_password
Once logged in, you can change your password.
Thanks!
admin

This is an automated response, please DO NOT REPLY!";

			mail($email, $subject, $message, "From: iConquest Admin <admin@internationalconquest.net>");
			echo "Your password has been sent! Please check your email! This could take up to 10 minutes to process.<br />"; 
			echo "<br /><br /><a href='login.php'>Click to login</a>";
		}
		else
		{
			echo "<center><font face='Verdana' size='2' color='red'>$msg<br /><br /><input type='button' value='Retry' onClick='history.go(-1)' /></center></font>";
		}
	}
	else
	{
		include ('header.php');

		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['REQUEST_URI']; ?>' method='post'>
		<table class='main'>
			<tr>
				<th class='form_head' colspan='4'>I Forgot My Password!</th>
			</tr>
			<tr>
				<td></td>
				<td class='input_title'>Email:</td>
				<td class='input'><input type='text' title='Please enter your email address' name='email' size='40' /></td>
				<td></td>
			</tr>
			<tr>
				<td class='button' colspan='4'><input type='submit' value='Submit' /></td>
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
?>