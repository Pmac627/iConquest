<?php
function warfare_aggressor_stats($att_unit_value, $opinion_bonus, $zone_bonus, $creed_bonus, $ethnicity_bonus, $surprise_bonus, $att_infra, $att_land, $att_tech, $att_capital, $aggres_me)
{
	$infra_bonus = $att_infra * 0.1;
	$land_bonus = $att_land * 0.1;
	$tech_bonus = $att_tech * 0.3;
	$capital_bonus = $att_capital * 0.5;
	
	$combined_bonus = ($att_unit_value + $infra_bonus + $land_bonus + $tech_bonus + $capital_bonus);
	$applied_bonus = (($opinion_bonus + $zone_bonus + $creed_bonus + $ethnicity_bonus + $surprise_bonus + $aggres_me) - 5);
	
	$aggressor_raw = ($combined_bonus * $applied_bonus);
	return $aggressor_raw;
}

function warfare_defender_stats($def_unit_value, $def_infra, $def_land, $def_tech, $def_capital, $aggres_them)
{
	$infra_bonus = $def_infra * 0.2;
	$land_bonus = $def_land * 0.2;
	$tech_bonus = $def_tech * 0.3;
	$capital_bonus = $def_capital * 0.5;
	
	$combined_bonus = ($def_unit_value + $infra_bonus + $land_bonus + $tech_bonus + $capital_bonus);
	$applied_bonus = $aggres_them;
	
	$defender_raw = ($combined_bonus * $applied_bonus);
	return $defender_raw;
}

function chance_percent($ground_raw1, $ground_raw2)
{
	$total = ($ground_raw1 + $ground_raw2);
	$one_perc = ($total / 100);
	$raw_perc = ($ground_raw1 / $one_perc);
	
	return $raw_perc;
}

function aggressor_losses($ground_raw1, $ground_raw2)
{
	$total = ($ground_raw1 + $ground_raw2);
	$one_perc = ($total / 100);
	$raw_perc = ($ground_raw1 / $one_perc);
	
	return $raw_perc;
}

function warfare_GRF_five($unit1, $unit2, $unit3, $unit4, $unit5)
{
	// set up an array of values to be evaluated
	$values = array($unit1, $unit2, $unit3, $unit4, $unit5);

	// count the number of values in the array
	$num_values = count($values);

	// get the first 2 values in the array        
	$x = current($values);
	$y = next($values);
		
	// set up a for-loop to check through all of the values in the array
	// the first pass will check 2 numbers then each additional pass will check 1
	// make ($num_values - 1) passes
	for ($i = 1; $i < $num_values; $i ++)
	{
		// set up the larger and smaller of the values
		$a = max( $x, $y );
		$b = min( $x, $y );
		$c = 1;

		// find the GCF of $a and $b
		// it will be found when $c == 0
		do 
		{
			$c = $a % $b;

			// capture last value of $b as the potential last GCF result
			$gcf = $b;
			
			// if $c did not = 0 we need to repeat with the values held in $b and $c
			// at this point $b is higher than $c so we set up for the next iteration
			// set $a to the higher number and $b to the lower number
			$a = $b;
			$b = $c;
				
		}
		while ($c != 0);
		
		// if $c did == 0 then we have found the GCF of 2 numbers
		// now set up to find the GCF of the last GCF we found and the next value in the array()
		$x = $gcf;
		$y = next($values);
		
	}  // end for loop through array()

	//
	// the greatest common factor of our array of values is now held in $gcf
	//
	return $gcf;
}

function warfare_GRF_four($unit1, $unit2, $unit3, $unit4)
{
	// set up an array of values to be evaluated
	$values = array($unit1, $unit2, $unit3, $unit4);

	// count the number of values in the array
	$num_values = count($values);

	// get the first 2 values in the array        
	$x = current($values);
	$y = next($values);
		
	// set up a for-loop to check through all of the values in the array
	// the first pass will check 2 numbers then each additional pass will check 1
	// make ($num_values - 1) passes
	for ($i = 1; $i < $num_values; $i ++)
	{
		// set up the larger and smaller of the values
		$a = max( $x, $y );
		$b = min( $x, $y );
		$c = 1;

		// find the GCF of $a and $b
		// it will be found when $c == 0
		do 
		{
			$c = $a % $b;

			// capture last value of $b as the potential last GCF result
			$gcf = $b;
			
			// if $c did not = 0 we need to repeat with the values held in $b and $c
			// at this point $b is higher than $c so we set up for the next iteration
			// set $a to the higher number and $b to the lower number
			$a = $b;
			$b = $c;
				
		}
		while ($c != 0);
		
		// if $c did == 0 then we have found the GCF of 2 numbers
		// now set up to find the GCF of the last GCF we found and the next value in the array()
		$x = $gcf;
		$y = next($values);
		
	}  // end for loop through array()

	//
	// the greatest common factor of our array of values is now held in $gcf
	//
	return $gcf;
}

function warfare_GRF_three($unit1, $unit2, $unit3)
{
	// set up an array of values to be evaluated
	$values = array($unit1, $unit2, $unit3);

	// count the number of values in the array
	$num_values = count($values);

	// get the first 2 values in the array        
	$x = current($values);
	$y = next($values);
		
	// set up a for-loop to check through all of the values in the array
	// the first pass will check 2 numbers then each additional pass will check 1
	// make ($num_values - 1) passes
	for ($i = 1; $i < $num_values; $i ++)
	{
		// set up the larger and smaller of the values
		$a = max( $x, $y );
		$b = min( $x, $y );
		$c = 1;

		// find the GCF of $a and $b
		// it will be found when $c == 0
		do 
		{
			$c = $a % $b;

			// capture last value of $b as the potential last GCF result
			$gcf = $b;
			
			// if $c did not = 0 we need to repeat with the values held in $b and $c
			// at this point $b is higher than $c so we set up for the next iteration
			// set $a to the higher number and $b to the lower number
			$a = $b;
			$b = $c;
				
		}
		while ($c != 0);
		
		// if $c did == 0 then we have found the GCF of 2 numbers
		// now set up to find the GCF of the last GCF we found and the next value in the array()
		$x = $gcf;
		$y = next($values);
		
	}  // end for loop through array()

	//
	// the greatest common factor of our array of values is now held in $gcf
	//
	return $gcf;
}

function warfare_GRF_two($unit1, $unit2)
{
	// set up an array of values to be evaluated
	$values = array($unit1, $unit2);

	// count the number of values in the array
	$num_values = count($values);

	// get the first 2 values in the array        
	$x = current($values);
	$y = next($values);
		
	// set up a for-loop to check through all of the values in the array
	// the first pass will check 2 numbers then each additional pass will check 1
	// make ($num_values - 1) passes
	for ($i = 1; $i < $num_values; $i ++)
	{
		// set up the larger and smaller of the values
		$a = max( $x, $y );
		$b = min( $x, $y );
		$c = 1;

		// find the GCF of $a and $b
		// it will be found when $c == 0
		do 
		{
			$c = $a % $b;

			// capture last value of $b as the potential last GCF result
			$gcf = $b;
			
			// if $c did not = 0 we need to repeat with the values held in $b and $c
			// at this point $b is higher than $c so we set up for the next iteration
			// set $a to the higher number and $b to the lower number
			$a = $b;
			$b = $c;
				
		}
		while ($c != 0);
		
		// if $c did == 0 then we have found the GCF of 2 numbers
		// now set up to find the GCF of the last GCF we found and the next value in the array()
		$x = $gcf;
		$y = next($values);
		
	}  // end for loop through array()

	//
	// the greatest common factor of our array of values is now held in $gcf
	//
	return $gcf;
}
?>