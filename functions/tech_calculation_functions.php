<?php
function tech_base_cost_calculation($oldtech)
{
	// The base cost calculation
	if($oldtech < 1)
		{
			$price = 5000;
		}
		elseif($oldtech < 2)
		{
			$price = ($oldtech * 51) + 5000;
		}
		elseif($oldtech < 3)
		{
			$price = ($oldtech * 52) + 5000;
		}
		elseif($oldtech < 4)
		{
			$price = ($oldtech * 53) + 5000;
		}
		elseif($oldtech < 5)
		{
			$price = ($oldtech * 54) + 5000;
		}
		elseif($oldtech < 10)
		{
			$price = ($oldtech * 55) + 5000;
		}
		elseif($oldtech < 20)
		{
			$price = ($oldtech * 56) + 5000;
		}
		elseif($oldtech < 30)
		{
			$price = ($oldtech * 57) + 5000;
		}
		elseif($oldtech < 40)
		{
			$price = ($oldtech * 58) + 5000;
		}
		elseif($oldtech < 50)
		{
			$price = ($oldtech * 59) + 5000;
		}
		elseif($oldtech < 75)
		{
			$price = ($oldtech * 60) + 5000;
		}
		elseif($oldtech < 100)
		{
			$price = ($oldtech * 61) + 5000;
		}
		elseif($oldtech < 150)
		{
			$price = ($oldtech * 62) + 5000;
		}
		elseif($oldtech < 200)
		{
			$price = ($oldtech * 63) + 5000;
		}
		elseif($oldtech < 250)
		{
			$price = ($oldtech * 64) + 5000;
		}
		elseif($oldtech < 300)
		{
			$price = ($oldtech * 65) + 5000;
		}
		elseif($oldtech < 350)
		{
			$price = ($oldtech * 70) + 5000;
		}
		elseif($oldtech < 400)
		{
			$price = ($oldtech * 75) + 5000;
		}
		elseif($oldtech < 450)
		{
			$price = ($oldtech * 80) + 5000;
		}
		elseif($oldtech < 500)
		{
			$price = ($oldtech * 85) + 5000;
		}
		elseif($oldtech < 750)
		{
			$price = ($oldtech * 90) + 5000;
		}
		elseif($oldtech < 1000)
		{
			$price = ($oldtech * 95) + 5000;
		}
		elseif($oldtech < 2000)
		{
			$price = ($oldtech * 100) + 5000;
		}
		elseif($oldtech < 3000)
		{
			$price = ($oldtech * 110) + 5000;
		}
		elseif($oldtech < 4000)
		{
			$price = ($oldtech * 120) + 5000;
		}
		else
		{
			$price = ($oldtech * 130) + 5000;
		}

	return $price;
}
?>