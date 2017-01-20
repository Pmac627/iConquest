<?php
function land_base_cost_calculation($oldland)
{
	// The base cost calculation
	if($oldland < 5)
		{
			$price = ($oldland * 2) + 1000;
		}
		elseif($oldland < 10)
		{
			$price = ($oldland * 4) + 1000;
		}
		elseif($oldland < 15)
		{
			$price = ($oldland * 6) + 1000;
		}
		elseif($oldland < 20)
		{
			$price = ($oldland * 8) + 1000;
		}
		elseif($oldland < 25)
		{
			$price = ($oldland * 10) + 1000;
		}
		elseif($oldland < 50)
		{
			$price = ($oldland * 12) + 1000;
		}
		elseif($oldland < 100)
		{
			$price = ($oldland * 14) + 1000;
		}
		elseif($oldland < 200)
		{
			$price = ($oldland * 16) + 1000;
		}
		elseif($oldland < 300)
		{
			$price = ($oldland * 18) + 1000;
		}
		elseif($oldland < 400)
		{
			$price = ($oldland * 20) + 1000;
		}
		elseif($oldland < 500)
		{
			$price = ($oldland * 22) + 1000;
		}
		elseif($oldland < 7500)
		{
			$price = ($oldland * 24) + 1000;
		}
		elseif($oldland < 1000)
		{
			$price = ($oldland * 26) + 1000;
		}
		elseif($oldland < 2000)
		{
			$price = ($oldland * 28) + 1000;
		}
		elseif($oldland < 3000)
		{
			$price = ($oldland * 30) + 1000;
		}
		elseif($oldland < 4000)
		{
			$price = ($oldland * 32) + 1000;
		}
		elseif($oldland < 5000)
		{
			$price = ($oldland * 34) + 1000;
		}
		elseif($oldland < 6000)
		{
			$price = ($oldland * 36) + 1000;
		}
		elseif($oldland < 7000)
		{
			$price = ($oldland * 38) + 1000;
		}
		elseif($oldland < 8000)
		{
			$price = ($oldland * 40) + 1000;
		}
		elseif($oldland < 9000)
		{
			$price = ($oldland * 42) + 1000;
		}
		elseif($oldland < 10000)
		{
			$price = ($oldland * 44) + 1000;
		}
		elseif($oldland < 12500)
		{
			$price = ($oldland * 46) + 1000;
		}
		elseif($oldland < 15000)
		{
			$price = ($oldland * 48) + 1000;
		}
		else
		{
			$price = ($oldland * 50) + 1000;
		}

	return $price;
}
?>