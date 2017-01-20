<?php
function days_since_calculation($last_day)
{
	$right_now = gmdate('U');

	$last_day_formatted = date('z', $last_day);
	$right_now_formatted = date('z', $right_now);

	if($right_now_formatted > $last_day_formatted)
	{
		$days_since = ($right_now_formatted - $last_day_formatted);
	}
	else
	{
		$days_since = ($last_day_formatted - $right_now_formatted);
	}

	return $days_since;
}
?>