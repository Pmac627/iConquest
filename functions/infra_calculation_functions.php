<?php
function infra_base_cost_calculation($oldinfra)
{
	// The base cost calculation
	if($oldinfra < 50)
		{
			$price = 500;
		}
		elseif($oldinfra < 100)
		{
			$price = ($oldinfra * 2) + 500;
		}
		elseif($oldinfra < 200)
		{
			$price = ($oldinfra * 3) + 500;
		}
		elseif($oldinfra < 300)
		{
			$price = ($oldinfra * 4) + 500;
		}
		elseif($oldinfra < 500)
		{
			$price = ($oldinfra * 5) + 500;
		}
		elseif($oldinfra < 700)
		{
			$price = ($oldinfra * 6) + 500;
		}
		elseif($oldinfra < 900)
		{
			$price = ($oldinfra * 7) + 500;
		}
		elseif($oldinfra < 1100)
		{
			$price = ($oldinfra * 8) + 500;
		}
		elseif($oldinfra < 1400)
		{
			$price = ($oldinfra * 9) + 500;
		}
		elseif($oldinfra < 1800)
		{
			$price = ($oldinfra * 10) + 500;
		}
		elseif($oldinfra < 2000)
		{
			$price = ($oldinfra * 11) + 500;
		}
		elseif($oldinfra < 2500)
		{
			$price = ($oldinfra * 12) + 500;
		}
		elseif($oldinfra < 3000)
		{
			$price = ($oldinfra * 13) + 500;
		}
		elseif($oldinfra < 3500)
		{
			$price = ($oldinfra * 14) + 500;
		}
		elseif($oldinfra < 4000)
		{
			$price = ($oldinfra * 15) + 500;
		}
		elseif($oldinfra < 5000)
		{
			$price = ($oldinfra * 20) + 500;
		}
		elseif($oldinfra < 6000)
		{
			$price = ($oldinfra * 25) + 500;
		}
		elseif($oldinfra < 7000)
		{
			$price = ($oldinfra * 30) + 500;
		}
		elseif($oldinfra < 8000)
		{
			$price = ($oldinfra * 35) + 500;
		}
		elseif($oldinfra < 9000)
		{
			$price = ($oldinfra * 40) + 500;
		}
		elseif($oldinfra < 10000)
		{
			$price = ($oldinfra * 45) + 500;
		}
		elseif($oldinfra < 12000)
		{
			$price = ($oldinfra * 50) + 500;
		}
		elseif($oldinfra < 14000)
		{
			$price = ($oldinfra * 75) + 500;
		}
		elseif($oldinfra < 17000)
		{
			$price = ($oldinfra * 100) + 500;
		}
		elseif($oldinfra < 20000)
		{
			$price = ($oldinfra * 125) + 500;
		}
		else
		{
			$price = ($oldinfra * 150) + 500;
		}

	return $price;
}
?>