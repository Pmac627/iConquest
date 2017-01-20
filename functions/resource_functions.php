<?php
// Produce a list of resources in their numerical db value
function trade_list($ID_recip)
{
	$var_1 = 2;
	// List trades offered by others to this nation
	// Count the number of trade partners a user has
	$trade_list = mysql_query("SELECT ID_offerer, ID_trade, trade_date, trade_stat FROM resource_trade WHERE ID_recip = '$ID_recip'") or die(mysql_error());
	while($partner_list = mysql_fetch_array( $trade_list ))
	{
		$ID_offerer = stripslashes($partner_list['ID_offerer']);
		$ID_trade = stripslashes($partner_list['ID_trade']);
		$trade_date = stripslashes($partner_list['trade_date']);
		$trade_stat = stripslashes($partner_list['trade_stat']);

		// Collect the resources that correspond with  ID_offerer
		$result3 = mysql_query("SELECT resource_1, resource_2 FROM nation_variables WHERE ID = '$ID_offerer'") or die(mysql_error());
		while($res = mysql_fetch_array($result3))
		{
			$var_1++;
			$resource[$var_1] = stripslashes($res['resource_1']);
			$var_1++;
			$resource[$var_1] = stripslashes($res['resource_2']);
		}
	}

	// List trades offered by this nation to others
	// Count the number of trade partners a user has
	$trade_list = mysql_query("SELECT ID_recip, ID_trade, trade_date, trade_stat FROM resource_trade WHERE ID_offerer = '$ID_recip'") or die(mysql_error());
	while($partner_list = mysql_fetch_array( $trade_list ))
	{
		$ID_recip = stripslashes($partner_list['ID_recip']);
		$ID_trade = stripslashes($partner_list['ID_trade']);
		$trade_date = stripslashes($partner_list['trade_date']);
		$trade_stat = stripslashes($partner_list['trade_stat']);

		// Collect the resources that correspond with  ID_offerer
		$result3 = mysql_query("SELECT resource_1, resource_2 FROM nation_variables WHERE ID = '$ID_recip'") or die(mysql_error());
		while($res = mysql_fetch_array($result3))
		{
			$var_1++;
			$resource[$var_1] = stripslashes($res['resource_1']);
			$var_1++;
			$resource[$var_1] = stripslashes($res['resource_2']);
		}
	}
	$var_1++;
	$resource[$var_1] = 99;

	return $resource;
}

function res_to_image($resource)
{
	// Put the resources to images
	if($resource == 1)
	{
		$resource = "<img src='images/resources/aluminum.png' alt='Aluminum' title='Aluminum' />";
	}
	elseif($resource == 2)
	{
		$resource = "<img src='images/resources/livestock.png' alt='Livestock' title='Livestock' />";
	}
	elseif($resource == 3)
	{
		$resource = "<img src='images/resources/coal_deposits.png' alt='Coal Deposits' title='Coal Deposits' />";
	}
	elseif($resource == 4)
	{
		$resource = "<img src='images/resources/ore_deposits.png' alt='Ore Deposits' title='Ore Deposits' />";
	}
	elseif($resource == 5)
	{
		$resource = "<img src='images/resources/grains.png' alt='Grains' title='Grains' />";
	}
	elseif($resource == 6)
	{
		$resource = "<img src='images/resources/crude_oil.png' alt='Crude Oil' title='Crude Oil' />";
	}
	elseif($resource == 7)
	{
		$resource = "<img src='images/resources/diamond_deposits.png' alt='Diamond Deposits' title='Diamond Deposits' />";
	}
	elseif($resource == 8)
	{
		$resource = "<img src='images/resources/fresh_water.png' alt='Fresh Water' title='Fresh Water' />";
	}
	elseif($resource == 9)
	{
		$resource = "<img src='images/resources/precious_metals.png' alt='Precious Metals' title='Precious Metals' />";
	}
	elseif($resource == 10)
	{
		$resource = "<img src='images/resources/lumber.png' alt='Lumber' title='Lumber' />";
	}
	elseif($resource == 11)
	{
		$resource = "<img src='images/resources/natural_gas.png' alt='Natural Gas' title='Natural Gas' />";
	}
	elseif($resource == 12)
	{
		$resource = "<img src='images/resources/rubber.png' alt='Rubber' title='Rubber' />";
	}
	elseif($resource == 13)
	{
		$resource = "<img src='images/resources/fish.png' alt='Fish' title='Fish' />";
	}
	elseif($resource == 14)
	{
		$resource = "<img src='images/resources/uranium.png' alt='Uranium' title='Uranium' />";
	}
	elseif($resource == 15)
	{
		$resource = "<img src='images/resources/granite.png' alt='Granite' title='Granite' />";
	}
	else
	{
	}

	return $resource;
}

function res_to_image_sub($resource)
{
	// Put the resources to images
	if($resource == 1)
	{
		$resource = "<img src='../images/resources/aluminum.png' alt='Aluminum' title='Aluminum' />";
	}
	elseif($resource == 2)
	{
		$resource = "<img src='../images/resources/livestock.png' alt='Livestock' title='Livestock' />";
	}
	elseif($resource == 3)
	{
		$resource = "<img src='../images/resources/coal_deposits.png' alt='Coal Deposits' title='Coal Deposits' />";
	}
	elseif($resource == 4)
	{
		$resource = "<img src='../images/resources/ore_deposits.png' alt='Ore Deposits' title='Ore Deposits' />";
	}
	elseif($resource == 5)
	{
		$resource = "<img src='../images/resources/grains.png' alt='Grains' title='Grains' />";
	}
	elseif($resource == 6)
	{
		$resource = "<img src='../images/resources/crude_oil.png' alt='Crude Oil' title='Crude Oil' />";
	}
	elseif($resource == 7)
	{
		$resource = "<img src='../images/resources/diamond_deposits.png' alt='Diamond Deposits' title='Diamond Deposits' />";
	}
	elseif($resource == 8)
	{
		$resource = "<img src='../images/resources/fresh_water.png' alt='Fresh Water' title='Fresh Water' />";
	}
	elseif($resource == 9)
	{
		$resource = "<img src='../images/resources/precious_metals.png' alt='Precious Metals' title='Precious Metals' />";
	}
	elseif($resource == 10)
	{
		$resource = "<img src='../images/resources/lumber.png' alt='Lumber' title='Lumber' />";
	}
	elseif($resource == 11)
	{
		$resource = "<img src='../images/resources/natural_gas.png' alt='Natural Gas' title='Natural Gas' />";
	}
	elseif($resource == 12)
	{
		$resource = "<img src='../images/resources/rubber.png' alt='Rubber' title='Rubber' />";
	}
	elseif($resource == 13)
	{
		$resource = "<img src='../images/resources/fish.png' alt='Fish' title='Fish' />";
	}
	elseif($resource == 14)
	{
		$resource = "<img src='../images/resources/uranium.png' alt='Uranium' title='Uranium' />";
	}
	elseif($resource == 15)
	{
		$resource = "<img src='../images/resources/granite.png' alt='Granite' title='Granite' />";
	}
	else
	{
	}

	return $resource;
}

function resource_cost_tech($price, $resource)
{
	// Aluminum - Decreases TECHNOLOGY cost by 4%.
	if($resource == 1)
	{
		$tech_cost_impact = ($price * 0.96);
	}
	else
	{
		$tech_cost_impact = $price;
	}

	return $tech_cost_impact;
}

function resource_cost_infra($price, $resource)
{
	// Lumber - Decreases INFRASTRUCTURE cost by 3%.
	if($resource == 10)
	{
		$infra_cost_impact = ($price * 0.97);
	}
	else
	{
		$infra_cost_impact = $price;
	}

	return $infra_cost_impact;
}

function resource_cost_capital($price, $resource)
{
	// Natural Gas Deposits - Decreases CAPITAL cost by 5%.
	if($resource == 11)
	{
		$capital_cost_impact = ($price * 0.95);
	}
	else
	{
		$capital_cost_impact = $price;
	}

	return $capital_cost_impact;
}

function resource_bills($bills, $resource)
{
	// Coal Deposits - Reduces BILLS by 2%.
	if($resource == 3)
	{
		$bills_impact = ($bills * 0.98);
	}
	else
	{
		$bills_impact = $bills;
	}

	return $bills_impact;
}

function resource_collection($collection, $resource)
{
	// Diamond Deposits - Increases TAX COLLECTION by 2%.
	if($resource == 7)
	{
		$collection_impact = ($collection * 1.02);
	}
	else
	{
		$collection_impact = $collection;
	}

	return $collection_impact;
}

function resource_land_area($land_area, $resource, $cw_animal_farm)
{
	// Livestock - Increases LAND AREA by 1%.
	// Animal Farm - Increases impact of LIVESTOCK by 1% each.
	if($resource == 2)
	{
		if($cw_animal_farm == 4)
		{
			$land_area_impact = ($land_area * 1.05);
		}
		elseif($cw_animal_farm == 3)
		{
			$land_area_impact = ($land_area * 1.04);
		}
		elseif($cw_animal_farm == 2)
		{
			$land_area_impact = ($land_area * 1.03);
		}
		elseif($cw_animal_farm == 1)
		{
			$land_area_impact = ($land_area * 1.02);
		}
		else
		{
			$land_area_impact = ($land_area * 1.01);
		}
	}
	else
	{
		$land_area_impact = $land_area;
	}

	return $land_area_impact;
}

function resource_armor_cost($price, $resource, $cw_iron_mine)
{
	// Ore Deposits - Reduces the cost of ARMOR purchase by 5% with Iron Mine.
	if($resource == 4 && $cw_iron_mine > 0)
	{
		$price_impact = ($price * 0.95);
	}
	else
	{
		$price_impact = $price;
	}

	return $price_impact;
}

function resource_total_population($citizens, $resource1, $resource2, $cw_small_farm, $cw_dock)
{
	// Grains - Increases TOTAL POPULATION by 2%.
	// Fish - Increases TOTAL POPULATION by 1%.
	// Small Farm - Increases impact of GRAINS by 1% each.
	// Docks - Increases impact of FISH by 1% each.
	if($resource1 == 5 && $resource2 == 13 || $resource1 == 13 && $resource2 == 5)
	{
		if($cw_small_farm == 4)
		{
			if($cw_dock == 4)
			{
				$population_impact = ($citizens * 1.11);
			}
			elseif($cw_dock == 3)
			{
				$population_impact = ($citizens * 1.10);
			}
			elseif($cw_dock == 2)
			{
				$population_impact = ($citizens * 1.09);
			}
			elseif($cw_dock == 1)
			{
				$population_impact = ($citizens * 1.08);
			}
			else
			{
				$population_impact = ($citizens * 1.07);
			}
		}
		elseif($cw_small_farm == 3)
		{
			if($cw_dock == 4)
			{
				$population_impact = ($citizens * 1.10);
			}
			elseif($cw_dock == 3)
			{
				$population_impact = ($citizens * 1.09);
			}
			elseif($cw_dock == 2)
			{
				$population_impact = ($citizens * 1.08);
			}
			elseif($cw_dock == 1)
			{
				$population_impact = ($citizens * 1.07);
			}
			else
			{
				$population_impact = ($citizens * 1.06);
			}
		}
		elseif($cw_small_farm == 2)
		{
			if($cw_dock == 4)
			{
				$population_impact = ($citizens * 1.09);
			}
			elseif($cw_dock == 3)
			{
				$population_impact = ($citizens * 1.08);
			}
			elseif($cw_dock == 2)
			{
				$population_impact = ($citizens * 1.07);
			}
			elseif($cw_dock == 1)
			{
				$population_impact = ($citizens * 1.06);
			}
			else
			{
				$population_impact = ($citizens * 1.05);
			}
		}
		elseif($cw_small_farm == 1)
		{
			if($cw_dock == 4)
			{
				$population_impact = ($citizens * 1.08);
			}
			elseif($cw_dock == 3)
			{
				$population_impact = ($citizens * 1.07);
			}
			elseif($cw_dock == 2)
			{
				$population_impact = ($citizens * 1.06);
			}
			elseif($cw_dock == 1)
			{
				$population_impact = ($citizens * 1.05);
			}
			else
			{
				$population_impact = ($citizens * 1.04);
			}
		}
		else
		{
			if($cw_dock == 4)
			{
				$population_impact = ($citizens * 1.07);
			}
			elseif($cw_dock == 3)
			{
				$population_impact = ($citizens * 1.06);
			}
			elseif($cw_dock == 2)
			{
				$population_impact = ($citizens * 1.05);
			}
			elseif($cw_dock == 1)
			{
				$population_impact = ($citizens * 1.04);
			}
			else
			{
				$population_impact = ($citizens * 1.03);
			}
		}
	}
	elseif($resource1 == 5 || $resource2 == 5)
	{
		if($cw_small_farm == 4)
		{
			$population_impact = ($citizens * 1.06);
		}
		elseif($cw_small_farm == 3)
		{
			$population_impact = ($citizens * 1.05);
		}
		elseif($cw_small_farm == 2)
		{
			$population_impact = ($citizens * 1.04);
		}
		elseif($cw_small_farm == 1)
		{
			$population_impact = ($citizens * 1.03);
		}
		else
		{
			$population_impact = ($citizens * 1.02);
		}
	}
	elseif($resource1 == 13 || $resource2 == 13)
	{
		if($cw_dock == 4)
		{
			$population_impact = ($citizens * 1.05);
		}
		elseif($cw_dock == 3)
		{
			$population_impact = ($citizens * 1.04);
		}
		elseif($cw_dock == 2)
		{
			$population_impact = ($citizens * 1.03);
		}
		elseif($cw_dock == 1)
		{
			$population_impact = ($citizens * 1.02);
		}
		else
		{
			$population_impact = ($citizens * 1.01);
		}
	}
	else
	{
		$population_impact = $citizens;
	}

	return $population_impact;
}

function resource_labor_force($labor_force, $resource, $economic_power)
{
	// Fresh Water - Increases LABOR FORCE by 2%. +1% labor force increase for every 1000 economic rating up to 3000.
	if($resource == 8)
	{
		if($economic_power >= 1000 && $economic_power < 2000)
		{
			$labor_force_impact = ($labor_force * 1.03);
		}
		elseif($economic_power >= 2000 && $economic_power < 3000)
		{
			$labor_force_impact = ($labor_force * 1.04);
		}
		elseif($economic_power >= 3000)
		{
			$labor_force_impact = ($labor_force * 1.05);
		}
		else
		{
			$labor_force_impact = ($labor_force * 1.02);
		}
	}
	else
	{
		$labor_force_impact = $labor_force;
	}

	return $labor_force_impact;
}

function resource_opinion($opinion, $resource)
{
	// Precious Metals Deposits - Increases PUBLIC OPINION by 1%. Allows the construction of Regional Mints.
	if($resource == 9)
	{
		$opinion_impact = ($opinion * 1.01);
	}
	else
	{
		$opinion_impact = $opinion;
	}

	return $opinion_impact;
}

function resource_pollution($pollution, $resource, $national_rating)
{
	// Uranium - Increases POLLUTION by 4% after a nation passes 10,000 national rating. Allows nuclear weapons.
	if($resource == 14 && $national_rating >= 10000)
	{
		$pollution_impact = ($pollution * 1.04);
	}
	else
	{
		$pollution_impact = $pollution;
	}

	return $pollution_impact;
}

function resource_civil_works($price, $resource)
{
	// Granite - Decreases the cost of CIVIL WORKS by 2%.
	if($resource == 15)
	{
		$price_impact = ($price * 0.98);
	}
	else
	{
		$price_impact = $price;
	}

	return $price_impact;
}

function resource_armor_limit($limit, $resource)
{
	// Crude Oil - Increases maximum ARMOR DEPLOYMENT by 2%.
	if($resource == 6)
	{
		$limit_impact = 0.22;
	}
	else
	{
		$limit_impact = $limit;
	}

	return $limit_impact;
}

function resource_infantry_1_upkeep($price, $resource)
{
	// Rubber - Decreases INFANTRY MAINTENANCE cost by 4%.
	if($resource == 12)
	{
		$inf_1_upkeep = ($price * 0.96);
	}
	else
	{
		$inf_1_upkeep = $price;
	}

	return $inf_1_upkeep;
}

function resource_infantry_2_upkeep($price, $resource)
{
	// Rubber - Decreases INFANTRY MAINTENANCE cost by 4%.
	if($resource == 12)
	{
		$inf_2_upkeep = ($price * 0.96);
	}
	else
	{
		$inf_2_upkeep = $price;
	}

	return $inf_2_upkeep;
}

function resource_infantry_3_upkeep($price, $resource)
{
	// Rubber - Decreases INFANTRY MAINTENANCE cost by 4%.
	if($resource == 12)
	{
		$inf_3_upkeep = ($price * 0.96);
	}
	else
	{
		$inf_3_upkeep = $price;
	}

	return $inf_3_upkeep;
}

function resource_cw_purchase($price, $resource)
{
	// Granite - Decreases the cost of CIVIL WORKS by 2%.
	if($resource == 15)
	{
		$price_impact = ($price * 0.98);
	}
	else
	{
		$price_impact = $price;
	}

	return $price_impact;
}
?>