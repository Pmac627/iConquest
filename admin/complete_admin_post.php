<?php
/** complete_admin_post.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();

// If the admin enters the change
if(isset($_POST['post']))
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

	// This makes sure they did not leave any fields blank
	if (!$_POST['title'] || !$_POST['body'] )
	{
		echo "You left a field empty!";
	}
	elseif($_POST['page'] == 99)
	{
		echo "You didn't choose a page to post this too!";
	}
	else
	{
		$ID_mod_admin = 18;
		$page = $_POST['page'];
		$title = $_POST['title'];
		$body = $_POST['body'];
		$post_date = gmdate('U');

		if($page == 0)
		{
			$postsuccess = "postsuccess_index";
		}
		elseif($page == 1)
		{
			$postsuccess = "postsuccess_icentral";
		}
		elseif($page == 2)
		{
			$postsuccess = "postsuccess_version";
		}
		else
		{
			$postsuccess = "postsuccess_basic";
		}

		// Create the private message in the database
		$insert = "INSERT INTO mod_admin_post (ID_mod_admin, page, title, body, post_date)
		VALUES ('$ID_mod_admin', '$page', '$title', '".mysql_real_escape_string($body)."', '$post_date')";
		$add_message = mysql_query($insert);

		// If the cookie does not exist, they are taken to the login screen
		header("Location: ../admin/admin_terminal.php?admin_fubar=$postsuccess");
	}
}
?>