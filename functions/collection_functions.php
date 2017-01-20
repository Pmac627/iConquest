<?php
function tax_calculation($old_collection, $labor_force, $opinion, $tax_rate)
{
	// Collection Calculation
	$C1 = $labor_force * $opinion;
	$C2 = $C1 * 10;
	$mod_collection = $C2 - $old_collection;
	$new_collection = $old_collection + $mod_collection;
	$collection = (($C2 * $tax_rate) / 100);

	return $collection;
}

function collection_output($days_diff_tax, $collection)
{
	// format the output for collection on the nation page
	if($days_diff_tax == 0)
		{
			$collection = number_format($collection,2);
			echo "\$" . $collection . " (Already collected)";
		}
	elseif($days_diff_tax == 1)
		{
			$collection = number_format($collection,2);
			echo "\$" . $collection . " (It has been 1 day since the last tax collection)";
		}
	else
		{
			$collection = ($collection * $days_diff_tax);
			$collection = number_format($collection,2);
			echo "\$" . $collection . " (It has been " . $days_diff_tax . " days since the last tax collection)";
		}
}
?>