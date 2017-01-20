<?php
function citizens_calculation($infra, $land, $tech, $capital)
{
	// Citizens Calculation
	$citizens = (($infra*'10') + ($land*'2') + ($tech*'1') + ($capital*'0.5') + 100);

	return $citizens;
}
?>