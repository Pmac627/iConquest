<?php
function ethnicity_name($ethnicity)
{
//put the $ethnicity to an english name
	if($ethnicity == 1)
	{ $ethnicity = "Albanian";}
	elseif($ethnicity == 2)
	{ $ethnicity = "American";}
	elseif($ethnicity == 3)
	{ $ethnicity = "Amerindian";}
	elseif($ethnicity == 4)
	{ $ethnicity = "Arab";}
	elseif($ethnicity == 5)
	{ $ethnicity = "Armenian";}
	elseif($ethnicity == 6)
	{ $ethnicity = "Australian";}
	elseif($ethnicity == 7)
	{ $ethnicity = "Bavarian";}
	elseif($ethnicity == 8)
	{ $ethnicity = "Berber";}
	elseif($ethnicity == 9)
	{ $ethnicity = "Bosnian";}
	elseif($ethnicity == 10)
	{ $ethnicity = "Brazilian";}
	elseif($ethnicity == 11)
	{ $ethnicity = "British";}
	elseif($ethnicity == 12)
	{ $ethnicity = "Bulgarian";}
	elseif($ethnicity == 13)
	{ $ethnicity = "Burman";}
	elseif($ethnicity == 14)
	{ $ethnicity = "Caucasian";}
	elseif($ethnicity == 15)
	{ $ethnicity = "Celtic";}
	elseif($ethnicity == 16)
	{ $ethnicity = "Chilean";}
	elseif($ethnicity == 17)
	{ $ethnicity = "Chinese";}
	elseif($ethnicity == 18)
	{ $ethnicity = "Creole";}
	elseif($ethnicity == 19)
	{ $ethnicity = "Croatian";}
	elseif($ethnicity == 20)
	{ $ethnicity = "Czech";}
	elseif($ethnicity == 21)
	{ $ethnicity = "Dutch";}
	elseif($ethnicity == 22)
	{ $ethnicity = "East African";}
	elseif($ethnicity == 23)
	{ $ethnicity = "Egyptian";}
	elseif($ethnicity == 24)
	{ $ethnicity = "Estonian";}
	elseif($ethnicity == 25)
	{ $ethnicity = "Finnish";}
	elseif($ethnicity == 26)
	{ $ethnicity = "French";}
	elseif($ethnicity == 27)
	{ $ethnicity = "German";}
	elseif($ethnicity == 28)
	{ $ethnicity = "Greek";}
	elseif($ethnicity == 29)
	{ $ethnicity = "Indian";}
	elseif($ethnicity == 30)
	{ $ethnicity = "Irish";}
	elseif($ethnicity == 31)
	{ $ethnicity = "Israeli";}
	elseif($ethnicity == 32)
	{ $ethnicity = "Italian";}
	elseif($ethnicity == 33)
	{ $ethnicity = "Japanese";}
	elseif($ethnicity == 34)
	{ $ethnicity = "Korean";}
	elseif($ethnicity == 35)
	{ $ethnicity = "Mestizo";}
	elseif($ethnicity == 36)
	{ $ethnicity = "Mexican";}
	elseif($ethnicity == 37)
	{ $ethnicity = "Mixed";}
	elseif($ethnicity == 38)
	{ $ethnicity = "North African";}
	elseif($ethnicity == 39)
	{ $ethnicity = "Norwegian";}
	elseif($ethnicity == 40)
	{ $ethnicity = "Pacific Islander";}
	elseif($ethnicity == 41)
	{ $ethnicity = "Pashtun";}
	elseif($ethnicity == 42)
	{ $ethnicity = "Persian";}
	elseif($ethnicity == 43)
	{ $ethnicity = "Peruvian";}
	elseif($ethnicity == 44)
	{ $ethnicity = "Polish";}
	elseif($ethnicity == 45)
	{ $ethnicity = "Portuguese";}
	elseif($ethnicity == 46)
	{ $ethnicity = "Russian";}
	elseif($ethnicity == 47)
	{ $ethnicity = "Scandinavian";}
	elseif($ethnicity == 48)
	{ $ethnicity = "Serbian";}
	elseif($ethnicity == 49)
	{ $ethnicity = "Somalian";}
	elseif($ethnicity == 50)
	{ $ethnicity = "South African";}
	elseif($ethnicity == 51)
	{ $ethnicity = "Spanish";}
	elseif($ethnicity == 52)
	{ $ethnicity = "Swiss";}
	elseif($ethnicity == 53)
	{ $ethnicity = "Thai";}
	elseif($ethnicity == 54)
	{ $ethnicity = "Turkish";}
	elseif($ethnicity == 55)
	{ $ethnicity = "West African";}
	else{
		$ethnicity = "";}

	return $ethnicity;
}

?>