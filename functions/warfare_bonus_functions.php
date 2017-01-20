<?php
function warfare_opinion_bonus($att_opinion, $def_opinion)
{
	$opinion_diff = $att_opinion - $def_opinion;
	$opinion_bonus = 1;

	if($opinion_diff > 0)
	{
		$opinion_bonus = 1.01;
	}
	return $opinion_bonus;
}

function warfare_zone_bonus($att_zone, $def_zone)
{
	$zonediff = $att_zone - $def_zone;
	$zone_diff = abs($zonediff);
	$zone_bonus = 1;

	if($zone_diff <= 1)
	{
		$zone_bonus = 1.01;
	}
	return $zone_bonus;
}

function warfare_creed_bonus($att_creed, $def_creed)
{
	$creed_bonus = 1;

	if($att_creed == $def_creed)
	{
		$creed_bonus = 0.995;
	}
	return $creed_bonus;
}

function warfare_ethnicity_bonus($att_ethnicity, $def_ethnicity)
{
	$ethnicity_bonus = 1;

	if($att_ethnicity == $def_ethnicity)
	{
		$ethnicity_bonus = 0.995;
	}
	return $ethnicity_bonus;
}

function warfare_surprise_bonus($open_shot)
{
	$surprise_bonus = 1;

	if($open_shot == 0)
	{
		$surprise_bonus = 1.005;
	}
	return $surprise_bonus;
}
?>