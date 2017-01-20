<?php
function land_type_name($land_type)
{
//put the $land_type to an english name
	if($land_type == 1){
		$land_type = "Mountain";}
	elseif($land_type == 2){
		$land_type = "Moorland";}
	elseif($land_type == 3){
		$land_type = "Tundra";}
	elseif($land_type == 4){
		$land_type = "Forest";}
	elseif($land_type == 5){
		$land_type = "Prairie";}
	elseif($land_type == 6){
		$land_type = "Savannah";}
	elseif($land_type == 7){
		$land_type = "Polar";}
	elseif($land_type == 8){
		$land_type = "Desert";}
	elseif($land_type == 9){
		$land_type = "Marsh";}
	elseif($land_type == 10){
		$land_type = "Rainforest";}
	else{
		$land_type = "River Delta";}
	
	return $land_type;
}

?>