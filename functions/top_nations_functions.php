<?php
function top_nr_desc()
{
	$top_nations_nr_desc1 = mysql_query("SELECT ID, nat_rate, eco_power, mil_power FROM nation_variables ORDER BY nat_rate DESC LIMIT 0, 10") or die(mysql_error());
	while($top_nr_desc1 = mysql_fetch_array( $top_nations_nr_desc1 ))
	{
		$count++;

		$ID = stripslashes($top_nr_desc1['ID']);
		$nat_rate = stripslashes($top_nr_desc1['nat_rate']);
		$eco_power = stripslashes($top_nr_desc1['eco_power']);
		$mil_power = stripslashes($top_nr_desc1['mil_power']);

		$top_nations_nr_desc2 = mysql_query("SELECT username, nat_exist FROM users WHERE ID = '" . $ID . "'") or die(mysql_error());
		while($top_nr_desc2 = mysql_fetch_array( $top_nations_nr_desc2 ))
		{
			$ruler = stripslashes($top_nr_desc2['username']);
			$nat_exists = stripslashes($top_nr_desc2['nat_exist']);
		}

		if($nat_exists == 1)
		{
			$top_nations_nr_desc3 = mysql_query("SELECT nation, title, peace_war FROM nations WHERE ID = '" . $ID . "'") or die(mysql_error());
			while($top_nr_desc3 = mysql_fetch_array( $top_nations_nr_desc3 ))
			{
				$nation = stripslashes($top_nr_desc3['nation']);
				$title = stripslashes($top_nr_desc3['title']);
				$peace_war = stripslashes($top_nr_desc3['peace_war']);
			}

			echo "  <tr>
						<td class='list_central_row_info'>" . $count . "</td>
						<td class='list_central_row_info'><strong><a class='link_inline' href='pm_send.php?to=" . $ID . "'>" . $title . " " . $ruler . "</a><br /> of <a class='link_inline' href='nation.php?ID=" . $ID . "'>" . $nation . "</a></strong></td>
						<td class='list_central_row_info'>" . $nat_rate . "<br />(MP: " . $mil_power . " / EP: " . $eco_power . ")</td>
						<td class='list_central_row_info'>" . peace_war_image($peace_war) . "</td>
					</tr>";
		}
		else
		{
		}
	}
}

function top_nr_asc()
{
	$top_nations_nr_asc1 = mysql_query("SELECT ID, nat_rate, eco_power, mil_power FROM nation_variables ORDER BY nat_rate ASC LIMIT 0, 10") or die(mysql_error());
	while($top_nr_asc1 = mysql_fetch_array( $top_nations_nr_asc1 ))
	{
		$count++;

		$ID = stripslashes($top_nr_asc1['ID']);
		$nat_rate = stripslashes($top_nr_asc1['nat_rate']);
		$eco_power = stripslashes($top_nr_asc1['eco_power']);
		$mil_power = stripslashes($top_nr_asc1['mil_power']);

		$top_nations_nr_asc2 = mysql_query("SELECT username, nat_exist FROM users WHERE ID = '" . $ID . "'") or die(mysql_error());
		while($top_nr_asc2 = mysql_fetch_array( $top_nations_nr_asc2 ))
		{
			$ruler = stripslashes($top_nr_asc2['username']);
			$nat_exists = stripslashes($top_nr_asc2['nat_exist']);
		}

		if($nat_exists == 1)
		{
			$top_nations_nr_asc3 = mysql_query("SELECT nation, title, peace_war FROM nations WHERE ID = '" . $ID . "'") or die(mysql_error());
			while($top_nr_asc3 = mysql_fetch_array( $top_nations_nr_asc3 ))
			{
				$nation = stripslashes($top_nr_asc3['nation']);
				$title = stripslashes($top_nr_asc3['title']);
				$peace_war = stripslashes($top_nr_asc3['peace_war']);
			}

			echo "  <tr>
						<td class='list_central_row_info'>" . $count . "</td>
						<td class='list_central_row_info'><strong><a class='link_inline' href='pm_send.php?to=" . $ID . "'>" . $title . " " . $ruler . "</a><br /> of <a class='link_inline' href='nation.php?ID=" . $ID . "'>" . $nation . "</a></strong></td>
						<td class='list_central_row_info'>" . $nat_rate . "<br />(MP: " . $mil_power . " / EP: " . $eco_power . ")</td>
						<td class='list_central_row_info'>" . peace_war_image($peace_war) . "</td>
					</tr>";
		}
		else
		{
		}
	}
}
?>