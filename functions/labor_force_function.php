<?php
function labor_force_calculation($citizens, $inf_total)
{
	$labor_force = ($citizens * 0.5);

	// Determine the range of acceptable troop levels (15% - 75% of citizens)
	$pop_var_low = ($citizens * 0.15);
	$pop_var_high = ($citizens * 0.75);

	// Labor Force's Infantry Impact Calculation
	if($inf_total < $pop_var_low || $inf_total > $pop_var_high)
	{
		// Check to see if the Infantry level is too low
		$labor_force = ($labor_force / 2);
	}

	return $labor_force;
}
?>