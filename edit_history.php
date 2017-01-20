<?php
/** edit_history.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Change Nation History';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

?>
<script language="JavaScript">
<!--
function restrict(newhistory)
{
	// specify the maximum length  if (newhistory.value.length > maxlength)
	var maxlength = 300;
	newhistory.value = newhistory.value.substring(0,maxlength);
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
	if($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		//otherwise they are shown the history edit page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		//collect the nation information for display from the nation_variables table
		$nationstats = mysql_query("SELECT history FROM nation_variables WHERE ID = '$ID'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			$old_history = stripslashes($row['history']);
		}

		//if the history edit form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['newhistory'] = strip_tags($_POST['newhistory']);

			if(isset($_POST['newhistory']))
			{
				if(sanity_check($_POST['newhistory'], 'string', 300) != FALSE)
				{
					$newhistory = mysql_real_escape_string($_POST['newhistory']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=19");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			// update the history to the new one!
			$insert = "UPDATE nation_variables SET history='" . $newhistory . "' WHERE ID='" . $ID . "'";
			$add_member = mysql_query($insert);

			//then redirect them to the nation
			echo "<META HTTP-EQUIV='Refresh' Content='0; URL=nation.php?ID=" . $ID . "'>";
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<table class='list_central'>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
			<tr>
				<th class='list_central_header'>Update Nation History</th>
			</tr>
			<tr>
				<td class='list_central_row_title'>Original Nation History:</td>
			</tr>
			<tr>
				<td class='list_central_row_info'><?php echo $old_history; ?></td>
			</tr>
			<tr>
				<td class='list_central_row_title'>New Nation History:</td>
			</tr>
			<tr>
				<td class='list_central_nav'><font color='red'>**300 character max**</font></td>
			</tr>
			<tr>
				<td class='search_box'>
				<table class='search_box'>
					<tr>
						<td><textarea rows='10' cols='60' name='newhistory' onkeyup="restrict(this.form.newhistory)">New History Here...</textarea></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td class='button'><input type='submit' name='submit' value='Update History!' /></td>
			</tr>
		</form>
		</table>
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