<?php
function military_power($inf_1, $inf_2, $inf_3, $armor_1, $armor_2, $armor_3, $armor_4, $armor_5, $creation_date)
{
	$inf_1_value = ($inf_1 * 10);
	$inf_2_value = (($inf_2 * 2) * 10);
	$inf_3_value = (($inf_3 * 3) * 10);
	$armor_1_value = ($armor_1 * 15);
	$armor_2_value = (($armor_2 * 2) * 15);
	$armor_3_value = (($armor_3 * 3) * 15);
	$armor_4_value = (($armor_4 * 4) * 15);
	$armor_5_value = (($armor_5 * 5) * 15);

	$creation_date_redux = days_since_calculation($creation_date);
	$creation_value_pre = number_format($creation_date_redux, 0, '.', '');
	$creation_value = ($creation_value_pre * 2);

	$mil_total = ($inf_1_value + $inf_2_value + $inf_3_value + $armor_1_value + $armor_2_value + $armor_3_value + $armor_4_value + $armor_5_value + $creation_value);

	return $mil_total;
}
?>