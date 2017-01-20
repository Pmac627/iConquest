<?php
function display_civil_works($cw_iron_mine, $cw_ranch, $cw_small_farm, $cw_dock, $cw_war_factory, $cw_pipeline, $cw_museum, $cw_regional_mint, $cw_sci_inst)
{
	$count = ($cw_iron_mine + $cw_ranch + $cw_small_farm + $cw_dock + $cw_war_factory + $cw_pipeline + $cw_museum + $cw_regional_mint + $cw_sci_inst);

	if($count > 0)
	{
		if($cw_iron_mine == 1)
		{
			$cw_iron_mine = $cw_iron_mine . " Iron Mine";
		}
		elseif($cw_iron_mine > 1)
		{
			$cw_iron_mine = $cw_iron_mine . " Iron Mines";
		}
		else
		{
			$cw_iron_mine = "";
		}

		if($cw_ranch == 1)
		{
			$cw_ranch = $cw_ranch . " Ranch";
		}
		elseif($cw_ranch > 1)
		{
			$cw_ranch = $cw_ranch . " Ranches";
		}
		else
		{
			$cw_ranch = "";
		}

		if($cw_small_farm == 1)
		{
			$cw_small_farm = $cw_small_farm . " Small Farm";
		}
		elseif($cw_small_farm > 1)
		{
			$cw_small_farm = $cw_small_farm . " Small Farms";
		}
		else
		{
			$cw_small_farm = "";
		}

		if($cw_dock == 1)
		{
			$cw_dock = $cw_dock . " Dock";
		}
		elseif($cw_dock > 1)
		{
			$cw_dock = $cw_dock . " Docks";
		}
		else
		{
			$cw_dock = "";
		}

		if($cw_war_factory == 1)
		{
			$cw_war_factory = $cw_war_factory . " War Factory";
		}
		else
		{
			$cw_war_factory = "";
		}

		if($cw_pipeline == 1)
		{
			$cw_pipeline = $cw_pipeline . " Pipeline";
		}
		elseif($cw_pipeline == 2)
		{
			$cw_pipeline = $cw_pipeline . " Pipelines";
		}
		else
		{
			$cw_pipeline = "";
		}

		if($cw_museum == 1)
		{
			$cw_museum = $cw_museum . " Museum";
		}
		elseif($cw_museum > 1)
		{
			$cw_museum = $cw_museum . " Museums";
		}
		else
		{
			$cw_museum = "";
		}

		if($cw_regional_mint == 1)
		{
			$cw_regional_mint = $cw_regional_mint . " Regional Mint";
		}
		elseif($cw_regional_mint > 1)
		{
			$cw_regional_mint = $cw_regional_mint . " Regional Mints";
		}
		else
		{
			$cw_regional_mint = "";
		}

		if($cw_sci_inst == 1)
		{
			$cw_sci_inst = $cw_sci_inst . " Science Institute";
		}
		elseif($cw_sci_inst > 1)
		{
			$cw_sci_inst = $cw_sci_inst . " Science Institutes";
		}
		else
		{
			$cw_sci_inst = "";
		}

		$civil_works[0] = $cw_iron_mine;
		$civil_works[1] = $cw_ranch;
		$civil_works[2] = $cw_small_farm;
		$civil_works[3] = $cw_dock;
		$civil_works[4] = $cw_war_factory;
		$civil_works[5] = $cw_pipeline;
		$civil_works[6] = $cw_museum;
		$civil_works[7] = $cw_regional_mint;
		$civil_works[8] = $cw_sci_inst;
		$civil_works = array_unique($civil_works);

		$civil_works = array_filter($civil_works);
		$civil_works_display = implode(", ", $civil_works);
	}
	else
	{
		$civil_works_display = "No Civil Works Have Been Developed Yet";
	}

	return $civil_works_display;
}

function civil_works_opinion($opinion, $cw_museum, $resource)
{
	// Museum - Increases PUBLIC OPINION by 1% each, 1.5% each with Diamond Deposits.
	// Diamonds are currently resource #7
	if($cw_museum == 4)
	{
		if($resource == 7)
		{
			$opinion_impact = ($opinion * 1.06);
		}
		else
		{
			$opinion_impact = ($opinion * 1.04);
		}
	}
	elseif($cw_museum == 3)
	{
		if($resource == 7)
		{
			$opinion_impact = ($opinion * 1.045);
		}
		else
		{
			$opinion_impact = ($opinion * 1.03);
		}
	}
	elseif($cw_museum == 2)
	{
		if($resource == 7)
		{
			$opinion_impact = ($opinion * 1.03);
		}
		else
		{
			$opinion_impact = ($opinion * 1.02);
		}
	}
	elseif($cw_museum == 1)
	{
		if($resource == 7)
		{
			$opinion_impact = ($opinion * 1.015);
		}
		else
		{
			$opinion_impact = ($opinion * 1.01);
		}
	}
	else
	{
		$opinion_impact = $opinion;
	}

	return $opinion_impact;
}

function civil_works_pollution($pollution, $cw_pipeline)
{
	// Pipelines - Increases POLLUTION by 1%. Limit 2.
	if($cw_pipeline == 2)
	{
		$pollution_impact = ($pollution * 1.02);
	}
	elseif($cw_pipeline == 1)
	{
		$pollution_impact = ($pollution * 1.01);
	}
	else
	{
		$pollution_impact = $pollution;
	}

	return $pollution_impact;
}

function civil_works_collection($collection, $cw_regional_mint)
{
	// Regional Mint - Increases TAX COLLECTION by 1% each.
	if($cw_regional_mint == 4)
	{
		$collection_impact = ($collection * 1.04);
	}
	elseif($cw_regional_mint == 3)
	{
		$collection_impact = ($collection * 1.03);
	}
	elseif($cw_regional_mint == 2)
	{
		$collection_impact = ($collection * 1.02);
	}
	elseif($cw_regional_mint == 1)
	{
		$collection_impact = ($collection * 1.01);
	}
	else
	{
		$collection_impact = $collection;
	}

	return $collection_impact;
}

function civil_works_infra($infra, $cw_iron_mine)
{
	// Iron Mine - Decreases cost of building Civil Works by 2% each. Decreases the cost of INFRASTRUCTURE purchase by 1% each.
	if($cw_iron_mine == 4)
	{
		$infra_impact = ($infra * 0.96);
	}
	elseif($cw_iron_mine == 3)
	{
		$infra_impact = ($infra * 0.97);
	}
	elseif($cw_iron_mine == 2)
	{
		$infra_impact = ($infra * 0.98);
	}
	elseif($cw_iron_mine == 1)
	{
		$infra_impact = ($infra * 0.99);
	}
	else
	{
		$infra_impact = $infra;
	}

	return $infra_impact;
}

function civil_works_cw_cost($price, $cw_iron_mine)
{
	// Iron Mine - Decreases cost of building CIVIL WORKS by 2% each. Decreases the cost of infrastructure purchase by 1% each.
	if($cw_iron_mine == 4)
	{
		$price_impact = ($price * 0.92);
	}
	elseif($cw_iron_mine == 3)
	{
		$price_impact = ($price * 0.94);
	}
	elseif($cw_iron_mine == 2)
	{
		$price_impact = ($price * 0.96);
	}
	elseif($cw_iron_mine == 1)
	{
		$price_impact = ($price * 0.98);
	}
	else
	{
		$price_impact = $price;
	}

	return $price_impact;
}

function civil_works_national_project($price, $cw_sci_institute)
{
	// Science Institutes - Requires 2 to unlock National Projects, 2% decrease on NATIONAL PROJECT cost each after 2.
	if($cw_sci_institute == 4)
	{
		$price_impact = ($price * 0.96);
	}
	elseif($cw_sci_institute == 3)
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