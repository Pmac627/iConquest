<?php
/** pm_outbox.php **/

// Database connection function
require('functions/database_connection_function.php');
database_connection();
$conn = mysql_connect("localhost", "pmac627_conquest", "iconquest101") or die(mysql_error());
include('functions/side_menu_functions.php');
include('functions/days_since_functions.php');
include('functions/form_input_check_functions.php');
include('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Private Message Outbox';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

// Checks cookies to make sure they are logged in
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

	// If the cookie has the wrong password, they are taken to the expired session login page
	if ($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		// Otherwise they are shown the private message read page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		$ID_send = $ID;

		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID_send, $mod_admin, $site_area);
		?>
		<td>
		<table class='list_central'>
			<tr>
				<td class='list_central_nav' colspan='4'><a class='link_inline' href='pm_central.php'>Inbox</a> | <a class='link_inline' href='pm_outbox.php'>Outbox</a></td>
			</tr>
			<tr>
				<th class='list_central_header' colspan='4'>Private Message Central</th>
			</tr>
			<tr>
				<th class='list_central_sub_header' colspan='4'>Outbox</th>
			</tr>
			<tr>
				<td class='list_central_row_title' width='20'>#</td>
				<td class='list_central_row_title'>Recipient</td>
				<td class='list_central_row_title'>Subject</td>
				<td class='list_central_row_title' width='140'>Date/Time</td>
			</tr>
			<?php

			// Find out how many rows are in the table 
			$message_list = mysql_query("SELECT ID_message FROM private_message WHERE ID_send = '$ID_send' AND read_pm < 2") or die(mysql_error());
			while($pm_list = mysql_fetch_array( $message_list ))
			{
				$numrows++;
			}

			// Number of rows to show per page
			$rowsperpage = 10;
			// Find out total pages
			$totalpages = ceil($numrows  / $rowsperpage);

			$URL_current_page = $_GET['currentpage'];
			$URL_current_page = strip_tags($URL_current_page);

			// Get the current page or set a default
			if(isset($URL_current_page))
			{
				if(sanity_check($URL_current_page, 'numeric', 3) != FALSE)
				{
					$currentpage = $URL_current_page;
				}
			}
			else
			{
			   // Default page num
			   $currentpage = 1;
			}

			// If current page is greater than total pages...
			if($currentpage > $totalpages)
			{
			   // Set current page to last page
			   $currentpage = $totalpages;
			}
			// If current page is less than first page...
			if($currentpage < 1)
			{
			   // Set current page to first page
			   $currentpage = 1;
			}

			// The offset of the list, based on current page 
			$offset = ($currentpage - 1) * $rowsperpage;

			$sql = "SELECT ID_recip, ID_message, time_sent, read_date, subject, read_pm, read_date FROM private_message WHERE read_pm < 2 AND ID_send=$ID_send ORDER BY time_sent DESC LIMIT $offset, $rowsperpage";
			$result = mysql_query($sql, $conn) or trigger_error("SQL", E_USER_ERROR);

			$count = 1;
			// While there are rows to be fetched...
			while($list = mysql_fetch_assoc($result))
			{
				// Echo data
				$ID_recip = stripslashes($list['ID_recip']);
				$ID_message = stripslashes($list['ID_message']);
				$time_sent = stripslashes($list['time_sent']);
				$read_date = stripslashes($list['read_date']);
				$subject = stripslashes($list['subject']);
				$read_pm = stripslashes($list['read_pm']);
				
				// Convert the creation Unix timestamp into a date & time
				$raw_time = $time_sent;
				$formatted_time_sent = date('Y-m-d', $raw_time);

				// Collect the nation leader that corresponds with the ID
				$result2 = mysql_query("SELECT username FROM users WHERE ID = '$ID_recip'") or die(mysql_error());
				$ruler = mysql_fetch_array($result2) or die(mysql_error());
				$leader = stripslashes($ruler['username']);
				
				$new_flag = 0;
				if($read_pm == 0)
				{
					$new_flag = "<em><font color='red'>(unread)</font></em>";
					echo "  <tr>
									<td class='list_central_row_info'>" . $count . "</td>
									<td class='list_central_row_info'><strong><a class='link_inline' href='nation.php?ID=" . $ID_recip . "'>" . $leader . "</a></strong></td>
									<td class='list_central_row_info'> " . $new_flag . " <a class='link_inline' href='pm_read_sent.php?ID_message=" . $ID_message . "'> " . $subject . "</a></td>
									<td class='list_central_row_info'>" . $formatted_time_sent . "</td>
								</tr>" ;
					$count++;
				}
				elseif($read_pm == 1)
				{
					$delete_timer = days_since_calculation($read_date);
					// If the message has been read, check to see how old it is.
					if($delete_timer <= 7)
					{
						echo "  <tr>
									<td class='list_central_row_info'>" . $count . "</td>
									<td class='list_central_row_info'><strong><a class='link_inline' href='nation.php?ID=" . $ID_recip . "'>" . $leader . "</a></strong></td>
									<td class='list_central_row_info'><a class='link_inline' href='pm_read_sent.php?ID_message=" . $ID_message . "'> " . $subject . "</a></td>
									<td class='list_central_row_info'>" . $formatted_time_sent . "</td>
								</tr>" ;
						$count++;
					}
					else
					{
						// If the message is 7 days or older since it was sent, mark it deleted
						$read_pm = 2;

						// Update to deleted
						$insert = "UPDATE private_message SET read_pm='" . $read_pm . "' WHERE ID_message='" . $ID_message . "'";
						$add_member = mysql_query($insert);
					}
				}
				else
				{
					$new_flag = "";
				}
			}

			if($numrows != 0)
			{
				echo "  <tr>
							<td class='list_central_row_title' colspan='4'>";
				/******  build the pagination links ******/
				// if not on page 1, don't show back links
				if ($currentpage > 1)
				{
				   // show << link to go back to page 1
				   echo " <a class='link_inline' href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
				   // get previous page num
				   $prevpage = $currentpage - 1;
				   // show < link to go back to 1 page
				   echo " <a class='link_inline' href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";
				}
				
				// range of num links to show
				$range = 3;

				// loop to show links to range of pages around current page
				for ($x = ($currentpage - $range); $x < (($currentpage + $range)  + 1); $x++)
				{
					// if it's a valid page number...
					if (($x > 0) && ($x <= $totalpages))
					{
						// if we're on current page...
						if ($x == $currentpage)
						{
							// 'highlight' it but don't make a link
							echo " [$x] ";
							// if not current page...
						}
						else
						{
							// make it a link
							echo " <a class='link_inline' href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
						}
					}
				}
				
				// if not on last page, show forward and last page links        
				if ($currentpage != $totalpages)
				{
					// get next page
					$nextpage = $currentpage + 1;
					// echo forward link for next page 
					echo " <a class='link_inline' href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
					// echo forward link for lastpage
					echo " <a class='link_inline' href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>>></a> ";
				}
				/****** end build pagination links ******/
				echo "  	</td>
						</tr>";
			}
			?>
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
	// If the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>