<?php
/** complete_ban_edit.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();

// If the admin enters the change
if (isset($_POST['change']))
{
	$ID = $_POST['ID'];

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

	// Here we add slashes if needed
	if (!get_magic_quotes_gpc())
	{
		$_POST['ban_value'] = addslashes($_POST['ban_value']);
	}

	// Collect the updates from the form
	$nat_exist = $_POST['ban_value'];

	// Update the nation settings to the new ones!
	$insert = "UPDATE users SET nat_exist='$nat_exist' 
				WHERE ID='$ID'";
	$add_member = mysql_query($insert);

	if($nat_exist == 1)
	{
		$bansuccess = "bansuccess_exists";
	}
	elseif($nat_exist == 2)
	{
		$bansuccess = "bansuccess_banned";
	}
	else
	{
		$bansuccess = "bansuccess_deleted";
	}
	// If the cookie does not exist, they are taken to the login screen
	header("Location: ../admin/admin_terminal.php?admin_fubar=$bansuccess");
}
else
{
	header("Location: ../admin/nation_ban_manager.php");
}
?>