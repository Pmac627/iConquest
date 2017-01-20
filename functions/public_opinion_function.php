<?php
function public_opinion_calculation($pollution, $citizens, $inf_total)
{
	$opinion = (20 - $pollution);

	// Determine the low-end of the range of acceptable troop levels (15% of citizens)
	$pop_var_low = ($citizens * 0.15);

	// Public Opinion's Infantry Impact Calculation
	if($inf_total < $pop_var_low)
	{
		// Check to see if the Infantry level is too low
		$opinion = ($opinion / 2);
	}

	return $opinion;
}
?>