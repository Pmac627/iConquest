<?php
function poli_sci_name($poli_sci)
{
//put the $poli_sci to an english name
	if($poli_sci == 1)
	{ $poli_sci = "Absolute Monarchy";}
	elseif($poli_sci == 2)
	{ $poli_sci = "Aristocracy";}
	elseif($poli_sci == 3)
	{ $poli_sci = "Communism";}
	elseif($poli_sci == 4)
	{ $poli_sci = "Constitutional Monarchy";}
	elseif($poli_sci == 5)
	{ $poli_sci = "Constitutional Republic";}
	elseif($poli_sci == 6)
	{ $poli_sci = "Corporatism";}
	elseif($poli_sci == 7)
	{ $poli_sci = "Democratic Socialism";}
	elseif($poli_sci == 8)
	{ $poli_sci = "Despotism";}
	elseif($poli_sci == 9)
	{ $poli_sci = "Diarchy";}
	elseif($poli_sci == 10)
	{ $poli_sci = "Dictatorship";}
	elseif($poli_sci == 11)
	{ $poli_sci = "Direct Democracy";}
	elseif($poli_sci == 12)
	{ $poli_sci = "Libertarianism";}
	elseif($poli_sci == 13)
	{ $poli_sci = "Oligarchy";}
	elseif($poli_sci == 14)
	{ $poli_sci = "Parliamentary Republic";}
	elseif($poli_sci == 15)
	{ $poli_sci = "Participatory Democracy";}
	elseif($poli_sci == 16)
	{ $poli_sci = "Plutocracy";}
	elseif($poli_sci == 17)
	{ $poli_sci = "Police State";}
	elseif($poli_sci == 18)
	{ $poli_sci = "Representative Democracy";}
	elseif($poli_sci == 19)
	{ $poli_sci = "Socialism";}
	elseif($poli_sci == 20)
	{ $poli_sci = "Theocracy";}
	elseif($poli_sci == 21)
	{ $poli_sci = "Totalitarianism";}
	elseif($poli_sci == 22)
	{ $poli_sci = "Tribalism";}
	else{
		$poli_sci = "";}

	return $poli_sci;
}

?>