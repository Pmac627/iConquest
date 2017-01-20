<?php
function bills_calculation($infra, $tech, $capital, $land)
{
	// Bills Calculation
	$B1 = ($infra * 10) + ($tech * 2) + ($capital) + ($land *7.5);
	$bills = $B1 * 10;

	return $bills;
}

function bills_output($days_diff_bill, $bills)
{
	// format the output for bills on the nation page
	if($days_diff_bill == 0)
		{
			$bills = number_format($bills,2);
			echo "\$" . $bills . " (Already paid)";
		}
	elseif($days_diff_bill == 1)
		{
			$bills = number_format($bills,2);
			echo "\$" . $bills . " (It has been 1 day since the last bills payment)";
		}
	else
		{
			$bills = ($bills * $days_diff_bill);
			$bills = number_format($bills,2);
			echo "\$" . $bills . " (It has been " . $days_diff_bill . " days since the last bills payment)";
		}
}
?>