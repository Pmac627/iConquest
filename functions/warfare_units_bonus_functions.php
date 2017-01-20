<?php
function warfare_aggressor_units($att_inf1, $att_inf2, $att_inf3, $att_armor1, $att_armor2, $att_armor3, $att_armor4, $att_armor5)
{
	$att_inf2_value = ($att_inf2 * 2);
	$att_inf3_value = (($att_inf3 * 3) * 2);

	$att_total_armor = ($att_armor1 + $att_armor2 + $att_armor3 + $att_armor4 + $att_armor5);
	$att_total_armor_perc = ($att_total_armor / 2);
	$att_total_armor_perc = number_format($att_total_armor_perc, 0);

	if($att_armor2 >= $att_total_armor_perc)
	{
		$att_armor2_value = (($att_armor2 * 3) * 2);
	}
	else
	{
		$att_armor2_value = ($att_armor2 * 3);
	}

	$att_armor3_value = (($att_armor3 * 5) * 3);
	$att_armor4_value = ($att_armor4 * 8);
	$att_armor5_value = ($att_armor5 * 10);

	$att_inf_unit_value = ($att_inf1 + $att_inf2_value + $att_inf3_value);
	$att_armor_unit_value = ($att_armor1 + $att_armor2_value + $att_armor3_value + $att_armor4_value + $att_armor5_value);
	$att_unit_value = ($att_inf_unit_value + $att_armor_unit_value);

	return $att_unit_value;
}

function warfare_defender_units($def_inf1, $def_inf2, $def_inf3, $def_armor1, $def_armor2, $def_armor3, $def_armor4, $def_armor5)
{
	$def_inf2_value = (($def_inf2 * 2) * 2);
	$def_inf3_value = ($def_inf3 * 3);

	$def_total_armor = ($def_armor1 + $def_armor2 + $def_armor3 + $def_armor4 + $def_armor5);
	$def_total_armor_perc = ($def_total_armor / 2);
	$def_total_armor_perc = number_format($def_total_armor_perc, 0);

	if($def_armor2 >= $def_total_armor_perc)
	{
		$def_armor2_value = (($def_armor2 * 3) * 2);
	}
	else
	{
		$def_armor2_value = ($def_armor2 * 3);
	}

	$def_armor3_value = ($def_armor3 * 5);
	$def_armor4_value = (($def_armor4 * 8) * 3);
	$def_armor5_value = ($def_armor5 * 10);

	$def_inf_unit_value = ($def_inf1 + $def_inf2_value + $def_inf3_value);
	$def_armor_unit_value = ($def_armor1 + $def_armor2_value + $def_armor3_value + $def_armor4_value + $def_armor5_value);
	$def_unit_value = ($def_inf_unit_value + $def_armor_unit_value);

	return $def_unit_value;
}
?>