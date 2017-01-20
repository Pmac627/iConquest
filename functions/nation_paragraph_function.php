<?php
function nation_paragraph($nation, $title, $leader, $capitol, $land_type, $zone, $region, $poli_sci, $ethnicity, $creed, $currency, $tax_rate, $peace_war, $ID_use)
{
// The main paragraph (top of the nation page)
	echo "  <tr>
				<td colspan='2' class='index_message_spacer_no_th'></td>
			</tr>
			<tr>
				<td colspan='2'>
					<table class='index_message_box' cellpadding='0' cellspacing='0'>
						<tr>
							<td>
								<table class='index_message_box_mini_no_author' cellpadding='0' cellspacing='0'>
									<tr>
										<td class='index_box_center'>The Nation of " . $nation . "</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class='index_message_shell'>
								<p class='index_message_body'>
									Welcome!
									You are viewing the nation of " . $nation . " which is lead under the fantastic leadership of " . $title . " " . $leader .".  
									Within " . $nation . " lies the capitol city of " . $capitol . " which is located in a " . land_type_name($land_type) . " in the " . zone_name($zone) . " zone.  
									" . $nation . " currently consists of " . $region . " which are controlled under a " . poli_sci_name($poli_sci) . " system.  
									The people of " . $nation . " are mostly " . ethnicity_name($ethnicity) . " and " . creed_name($creed) . ".  
									The average citizen has a decent sum of " . currency_name($currency) . " thanks in part to the tax rate of " . $tax_rate . "%.  
									" . $nation . " is a " . peace_war_name($peace_war) . "-loving nation and is the number  " . $ID_use . " newest nation.
								</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>";
}
?>