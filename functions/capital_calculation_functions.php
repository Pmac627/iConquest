<?php
function capital_base_cost_calculation($oldcapital)
{
	// The base cost calculation
	if($oldcapital < 100)
		{
			$price = ($oldcapital * 100000);
		}
		elseif($oldcapital < 250)
		{
			$price = ($oldcapital * 150000);
		}
		elseif($oldcapital < 500)
		{
			$price = ($oldcapital * 200000);
		}
		else
		{
			$price = ($oldcapital * 250000);
		}

	return $price;
}
?>