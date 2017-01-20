<?php
function economic_power($infra, $tech, $capital, $land, $cw_total, $opinion, $labor_force, $res_total)
{
	$infra_value = ($infra * 10);
	$tech_value = ($tech * 5);
	$capital_value = ($capital / 2);
	$land_value = ($land * 5);
	$cw_value = ($cw_total * 100);
	$opinion_value = ($opinion * 5);
	$lf_value = ($labor_force * 3);
	$res_value = ($res_total * 50);

	$econ_total = ($infra_value + $tech_value + $capital_value + $land_value + $cw_value + $opinion_value + $lf_value + $res_value);

	return $econ_total;
}
?>