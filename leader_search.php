<?php 
/** leader_search.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Search for Leader';
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
		//otherwise they are shown the leader search page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get'>
		<table class='search_display'>
			<tr>
				<th class='form_head' colspan='4'>Leader Search</th>
			</tr>
			<tr>
				<td class='search_box' colspan='4'>
				<table class='search_box'>
					<tr>
						<td></td>
						<td class='input_title'>Search:</td>
						<td class='input'><input type='text' maxlength='20' name='leader' /></td>
						<td></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td class='button' colspan='4'><input type='submit' name='Submit' value='Search' /></td>
			</tr>
		<?php

		// Get the search variable from URL
		$var = $_GET['leader'];
		$var = strip_tags($var);

		if(sanity_check($var, 'string', 20) != FALSE)
		{
			$var = mysql_real_escape_string($var);
			$trimmed = trim($var);
		}

		// rows to return
		$limit = 10;

		// check for an empty string and display a message.
		if ($trimmed == "")
		{
			echo "<tr><th class='search_result_header' colspan='4'>Please enter a search...</th></tr>";
		}
		// check for a search parameter
		elseif (!isset($var))
		{
			echo "<tr><th class='search_result_header' colspan='4'>We dont seem to have a search parameter!</th></tr>";
		}
		else
		{
			// Build SQL Query from users db
			$query = "SELECT * FROM users WHERE username LIKE '%$trimmed%' ORDER BY username";

			$numresults = mysql_query($query);
			$numrows = mysql_num_rows($numresults);

			// If we have no results,
			if ($numrows == 0)
			{
				echo "<tr><td class='search_result_title' colspan='4'>Sorry, your search for the leader &quot;" . $trimmed . "&quot; returned zero results.</th></tr>";
			}
			else
			{
				// next determine if s has been passed to script, if not use 0
				if (empty($s))
				{
					$s = 0;
				}

				// get results
				$query .= " limit $s,$limit";
				$result = mysql_query($query) or die("Couldn't execute query");

				// display what the person searched for
				echo "<tr><th class='search_result_header' colspan='4' align='center'>You searched for the leader <strong>" . $var . "</strong>.</th></tr>";

				// begin to show results set
				echo "<tr><th class='search_result_header' width='20'>#</th><th class='search_result_header'>Leader</th><th class='search_result_header'>Nation Link</th><th class='search_result_header' width='100'>Private Message</th></tr>";
				$count = 1 + $s ;

				// now you can display the results returned
				while ($row = mysql_fetch_array($result))
				{
					$leader = stripslashes($row['username']);
					$ID = stripslashes($row['ID']);

					// collect the nation name that corresponds with the ID
					$query2 = "SELECT * FROM nations";
					$result2 = mysql_query("SELECT * FROM nations WHERE ID = '$ID'") or die(mysql_error());
					$nation1 = mysql_fetch_array($result2) or die(mysql_error());
					$nation2 = stripslashes($nation1['nation']);

					echo "<tr><td class='search_result_title'>" . $count . "</td><td class='search_result_info'><strong>" . $leader . "</strong><br />Ruler of " . $nation2 . "</td><td class='search_result_info'><a class='link_inline' href=\"nation.php?ID=$ID\">View " . $nation2 . "</a></td><td class='search_result_info'><a class='link_inline' href=\"pm_send.php?to=$leader\">Send a message</a></td></tr>" ;
					$count++;
				}

				$currPage = (($s / $limit) + 1);

				//break before paging
				echo "<tr><th class='search_result_header' colspan='4'>";
				// next we need to do the links to other results
				if ($s >= 1)
				{
					// bypass PREV link if s is 0
					$prevs = ($s - $limit);
					print "<a class='link_inline' href='$PHP_SELF?s=$prevs&leader=$var'>Prev 10</a>";
				}

				// calculate number of pages needing links
				$pages = intval($numrows/$limit);

				// $pages now contains int of pages needed unless there is a remainder from division
				if ($numrows % $limit)
				{
					// has remainder so add one page
					$pages++;
				}

				// check to see if last page
				if (!((($s + $limit) / $limit) == $pages) && $pages != 1)
				{
					// not last page so give NEXT link
					$news = $s + $limit;

					echo "<a class='link_inline' href='$PHP_SELF?s=$news&leader=$var'>Next 10</a>";
				}

				$a = $s + ($limit) ;
				if ($a > $numrows)
				{
					$a = $numrows ;
				}

				$b = $s + 1 ;
				echo "Showing results $b to $a of $numrows";
			}
		}
		echo "</th></tr></table></form></td></tr></table>";
		include ('footer.php');
	}
}
else
{
	//if the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>