<?php
/** complete_nation_edit.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();

// If the admin enters the change
if(isset($_POST['change']))
{
	// This makes sure they did not leave any fields blank
	if(!$_POST['nation'] || !$_POST['region'] || !$_POST['capitol'] || !$_POST['title'] || !$_POST['treasury'] || !$_POST['land'] || !$_POST['infra'] || !$_POST['tech'] || !$_POST['capital'] || !$_POST['last_col_bill'] || !$_POST['last_col_tax'] || !$_POST['history'])
	{
		echo "You left a field empty!  This is dangerous for the user's nation.";
		echo $_POST['ID'];
	}
	else
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

		// This makes sure the last_col_bill and last_col_tax are both 10 characters
		$l_c_b_length = strlen($_POST['last_col_bill']);
		$l_c_t_length = strlen($_POST['last_col_tax']);

		if($l_c_b_length != 10)
		{
			echo "The Last Bill date format error: Don't have 10 characters!";
		}

		if($l_c_t_length != 10)
		{
			echo "The Last Tax date format error: Don't have 10 characters!";
		}

		// This makes sure that resource1 and resource2 are NOT the same
		if($_POST['resource1'] == $_POST['resource2'])
		{
			echo "You cannot have both of the resources be the same thing!";
		}

		$_POST['nation'] = mysql_real_escape_string($_POST['nation']);
		$_POST['region'] = mysql_real_escape_string($_POST['region']);
		$_POST['capitol'] = mysql_real_escape_string($_POST['capitol']);
		$_POST['title'] = mysql_real_escape_string($_POST['title']);
		$_POST['tax_rate'] = mysql_real_escape_string($_POST['tax_rate']);
		$_POST['land_type'] = mysql_real_escape_string($_POST['land_type']);
		$_POST['zone'] = mysql_real_escape_string($_POST['zone']);
		$_POST['poli_sci'] = mysql_real_escape_string($_POST['poli_sci']);
		$_POST['currency'] = mysql_real_escape_string($_POST['currency']);
		$_POST['ethnicity'] = mysql_real_escape_string($_POST['ethnicity']);
		$_POST['language'] = mysql_real_escape_string($_POST['language']);
		$_POST['creed'] = mysql_real_escape_string($_POST['creed']);
		$_POST['peace_war'] = mysql_real_escape_string($_POST['peace_war']);

		$_POST['resource1'] = mysql_real_escape_string($_POST['resource1']);
		$_POST['resource2'] = mysql_real_escape_string($_POST['resource2']);
		$_POST['treasury'] = mysql_real_escape_string($_POST['treasury']);
		$_POST['land'] = mysql_real_escape_string($_POST['land']);
		$_POST['infra'] = mysql_real_escape_string($_POST['infra']);
		$_POST['tech'] = mysql_real_escape_string($_POST['tech']);
		$_POST['capital'] = mysql_real_escape_string($_POST['capital']);
		$_POST['last_col_bill'] = mysql_real_escape_string($_POST['last_col_bill']);
		$_POST['last_col_tax'] = mysql_real_escape_string($_POST['last_col_tax']);
		$_POST['history'] = mysql_real_escape_string($_POST['history']);

		$_POST['inf_1'] = mysql_real_escape_string($_POST['inf_1']);
		$_POST['inf_2'] = mysql_real_escape_string($_POST['inf_2']);
		$_POST['inf_3'] = mysql_real_escape_string($_POST['inf_3']);
		$_POST['inf_losses'] = mysql_real_escape_string($_POST['inf_losses']);

		// Collect the updates from the form
		$nation = $_POST['nation'];
		$region = $_POST['region'];
		$capitol = $_POST['capitol'];
		$title = $_POST['title'];
		$tax_rate = $_POST['tax_rate'];
		$land_type = $_POST['land_type'];
		$zone = $_POST['zone'];
		$poli_sci = $_POST['poli_sci'];
		$currency = $_POST['currency'];
		$ethnicity = $_POST['ethnicity'];
		$language = $_POST['language'];
		$creed = $_POST['creed'];
		$peace_war = $_POST['peace_war'];

		$resource1 = $_POST['resource1'];
		$resource2 = $_POST['resource2'];
		$treasury = $_POST['treasury'];
		$land = $_POST['land'];
		$infra = $_POST['infra'];
		$tech = $_POST['tech'];
		$capital = $_POST['capital'];
		$last_col_bill = $_POST['last_col_bill'];
		$last_col_tax = $_POST['last_col_tax'];
		$history = $_POST['history'];

		$inf_1 = $_POST['inf_1'];
		$inf_2 = $_POST['inf_2'];
		$inf_3 = $_POST['inf_3'];
		$inf_losses = $_POST['inf_losses'];

		// Update the nation settings to the new ones!
		$insert1 = "UPDATE nations SET nation='$nation', region='$region', capitol='$capitol', title='$title', currency='$currency', language='$language', ethnicity='$ethnicity', creed='$creed', poli_sci='$poli_sci', zone='$zone', land_type='$land_type', tax_rate='$tax_rate', peace_war='$peace_war' 
					WHERE ID='$ID'";
		$add_member1 = mysql_query($insert1);

		// Update the nation_variables settings to the new ones!
		$insert2 = "UPDATE nation_variables SET treasury='$treasury', land='$land', infra='$infra', tech='$tech', capital='$capital', last_bill='$last_col_bill', last_tax='$last_col_tax', history='$history', resource_1='$resource1', resource_2='$resource2' 
					WHERE ID='$ID'";
		$add_member2 = mysql_query($insert2);

		// Update the military settings to the new ones!
		$insert3 = "UPDATE military SET inf_1='$inf_1', inf_2='$inf_2', inf_3='$inf_3', inf_loss='$inf_losses' 
					WHERE ID='$ID'";
		$add_member3 = mysql_query($insert3);

		// If the cookie does not exist, they are taken to the login screen
		echo "<META HTTP-EQUIV='Refresh' Content='0; URL=../admin/admin_terminal.php?admin_fubar=editsuccess'>";
	}
}
else
{
	header("Location: ../admin/nation_alter.php");
}
?>