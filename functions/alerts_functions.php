<?php
function new_pm_count($count)
{
	if($count == 1)
	{
		$pm_fubar = "<tr><td class='alert_fubar' colspan='2'>You have " . $count . " new private message in your inbox.</td></tr>" ;
	}
	elseif($count > 1)
	{
		$pm_fubar = "<tr><td class='alert_fubar' colspan='2'>You have " . $count . " new private messages in your inbox.</td></tr>" ;
	}
	else
	{
		$pm_fubar = "" ;
	}
	return $pm_fubar;
}

function infantry_impact_message($inf_total_home, $citizens)
{
	$inf_total = ($inf_total_home);

	//determine 15% of a nation's population
	$pop_var_low = ($citizens * 0.15);

	//determine 75% of a nation's population
	$pop_var_high = ($citizens * 0.75);

	$inf_fubar = "";

	if($inf_total < $pop_var_low)
		{
			// Check to see if the Infantry level is too low
			$inf_fubar = "<br /><em>Your Infantry Level is too low!<br />Half of your Labor Force has stopped working!<br />Your Public Opinion has been reduced by 50% as well!</em>";
		}

	if($inf_total > $pop_var_high)
		{
			// Check to see if the Infantry level is too high
			$inf_fubar = "<br /><em>Your Infantry Level is too high!<br />Half of your Labor Force has stopped working!</em>";
		}
	return $inf_fubar;
}
?>