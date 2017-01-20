<?php
function creed_name($creed)
{
//put the $creed to an english name
	if($creed == 1)
	{ $creed = "Agnostic";}
	elseif($creed == 2)
	{ $creed = "Animist";}
	elseif($creed == 3)
	{ $creed = "Atheist";}
	elseif($creed == 4)
	{ $creed = "Ayyavazhi";}
	elseif($creed == 5)
	{ $creed = "Bahá'í Faith";}
	elseif($creed == 6)
	{ $creed = "Bön";}
	elseif($creed == 7)
	{ $creed = "Buddhist";}
	elseif($creed == 8)
	{ $creed = "Catholic";}
	elseif($creed == 9)
	{ $creed = "Christian";}
	elseif($creed == 10)
	{ $creed = "Confucian";}
	elseif($creed == 11)
	{ $creed = "Gnosticism";}
	elseif($creed == 12)
	{ $creed = "Hindu";}
	elseif($creed == 13)
	{ $creed = "Indigenous";}
	elseif($creed == 14)
	{ $creed = "Jainism";}
	elseif($creed == 15)
	{ $creed = "Jewish";}
	elseif($creed == 16)
	{ $creed = "Manichaeism";}
	elseif($creed == 17)
	{ $creed = "Mixed";}
	elseif($creed == 18)
	{ $creed = "Muslim";}
	elseif($creed == 19)
	{ $creed = "Orthodox";}
	elseif($creed == 20)
	{ $creed = "Shamanish";}
	elseif($creed == 21)
	{ $creed = "Shinto";}
	elseif($creed == 22)
	{ $creed = "Sikhist";}
	elseif($creed == 23)
	{ $creed = "Taoist";}
	elseif($creed == 24)
	{ $creed = "Voodoo";}
	elseif($creed == 25)
	{ $creed = "Zoroastrianism";}
	else{
		$creed = "";}

	return $creed;
}

?>