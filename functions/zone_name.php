<?php
function zone_name($zone)
{
// Put the $zone to an english name
	if($zone == 1){
		$zone = "<abbr title='Alpha'>&Alpha;</abbr>";}
	elseif($zone == 2){
		$zone = "<abbr title='Beta'>&Beta;</abbr>";}
	elseif($zone == 3){
		$zone = "<abbr title='Gamma'>&Gamma;</abbr>";}
	elseif($zone == 4){
		$zone = "<abbr title='Delta'>&Delta;</abbr>";}
	elseif($zone == 5){
		$zone = "<abbr title='Epsilon'>&Epsilon;</abbr>";}
	elseif($zone == 6){
		$zone = "<abbr title='Zeta'>&Zeta;</abbr>";}
	elseif($zone == 7){
		$zone = "<abbr title='Eta'>&Eta;</abbr>";}
	elseif($zone == 8){
		$zone = "<abbr title='Theta'>&Theta;</abbr>";}
	else{
		$zone = "<abbr title='Iota'>&Iota;</abbr>";}
	
	return $zone;
}

?>