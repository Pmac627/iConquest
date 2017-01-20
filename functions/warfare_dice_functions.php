<?php
function warfare_dice_ground($ground_raw)
{
	$dice = rand(1,5);
	$part = "1.0";
	$ground_multiplier = $part . $dice;
	$ground_rolled = ($ground_raw * $ground_multiplier);
	
	return $ground_rolled;
}
?>