<?php
function budget_balance_output($collection, $bills, $days_diff_tax, $days_diff_bill)
{
	$raw_collection = ($collection * $days_diff_tax);
	$raw_bills = ($bills * $days_diff_bill);
	$raw_balance = ($raw_collection - $raw_bills);
	$raw_balance_formatted = number_format($raw_balance, 2);

	if($raw_balance > 0)
	{
		$budget_balance_fubar = "<font color='green'>$" . $raw_balance_formatted . "</font>";
	}
	elseif($raw_balance < 0)
	{
		$budget_balance_fubar = "<font color='red'>$" . $raw_balance_formatted . "</font>";
	}
	else
	{
		$budget_balance_fubar = "$" . $raw_balance_formatted . "";
	}

	return $budget_balance_fubar;
}
?>