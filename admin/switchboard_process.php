<?php
/** switchboard_process.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();

// If the admin enters the change
if (isset($_POST['change']))
{
	$site_online = $_POST['site_online'];
	$version = $_POST['version'];
	$multiple_nations = $_POST['multiple_nations'];
	$new_nations = $_POST['new_nations'];

	// Collect the nation info that corresponds with the ID
	$user_stats = mysql_query("SELECT mod_admin FROM users WHERE ID = '$ID'") or die(mysql_error());
	while($user = mysql_fetch_array( $user_stats ))
	{
		// Collect the raw data from the users table in the db 
		$mod_admin = stripslashes($user['mod_admin']);
	}
	if($mod_admin != 2)
	{
		header("Location: ../login.php");
	}

	$site_online = mysql_real_escape_string($site_online);
	$version = mysql_real_escape_string($version);
	$multiple_nations = mysql_real_escape_string($multiple_nations);
	$new_nations = mysql_real_escape_string($new_nations);

	// Update the nation settings to the new ones!
	$insert = "UPDATE switchboard SET site_online='$site_online', version='$version', multiple_nations='$multiple_nations', new_nations='$new_nations' WHERE ID_switch='1'";
	$add_member = mysql_query($insert);

	// If the cookie does not exist, they are taken to the login screen
	header("Location: ../admin/admin_terminal.php?admin_fubar=switchboard");
}
else
{
	header("Location: ../admin/switchboard.php");
}
?>