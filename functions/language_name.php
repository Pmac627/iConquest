<?php
function language_name($language)
{
//put the $language to an english name
	if($language == 1)
	{ $language = "Albanian";}
	elseif($language == 2)
	{ $language = "Arabic";}
	elseif($language == 3)
	{ $language = "Armenian";}
	elseif($language == 4)
	{ $language = "Azerbaijani";}
	elseif($language == 5)
	{ $language = "Bantu";}
	elseif($language == 6)
	{ $language = "Bengali";}
	elseif($language == 7)
	{ $language = "Berber";}
	elseif($language == 8)
	{ $language = "Bosnian";}
	elseif($language == 9)
	{ $language = "Burmese";}
	elseif($language == 10)
	{ $language = "Chinese";}
	elseif($language == 11)
	{ $language = "Creole";}
	elseif($language == 12)
	{ $language = "Croatian";}
	elseif($language == 13)
	{ $language = "Czech";}
	elseif($language == 14)
	{ $language = "Dutch";}
	elseif($language == 15)
	{ $language = "English";}
	elseif($language == 16)
	{ $language = "Farsi";}
	elseif($language == 17)
	{ $language = "Finnish";}
	elseif($language == 18)
	{ $language = "French";}
	elseif($language == 19)
	{ $language = "German";}
	elseif($language == 20)
	{ $language = "Greek";}
	elseif($language == 21)
	{ $language = "Gujarati";}
	elseif($language == 22)
	{ $language = "Hebrew";}
	elseif($language == 23)
	{ $language = "Hindu";}
	elseif($language == 24)
	{ $language = "Hungarian";}
	elseif($language == 25)
	{ $language = "Indoneasian";}
	elseif($language == 26)
	{ $language = "Irish";}
	elseif($language == 27)
	{ $language = "Italian";}
	elseif($language == 28)
	{ $language = "Japanese";}
	elseif($language == 29)
	{ $language = "Javanese";}
	elseif($language == 30)
	{ $language = "Korean";}
	elseif($language == 31)
	{ $language = "Kurdish";}
	elseif($language == 32)
	{ $language = "Macedonian";}
	elseif($language == 33)
	{ $language = "Malay";}
	elseif($language == 34)
	{ $language = "Marathi";}
	elseif($language == 35)
	{ $language = "Norwegian";}
	elseif($language == 36)
	{ $language = "Pashto";}
	elseif($language == 37)
	{ $language = "Polish";}
	elseif($language == 38)
	{ $language = "Portuguese";}
	elseif($language == 39)
	{ $language = "Punjabi";}
	elseif($language == 40)
	{ $language = "Quechua";}
	elseif($language == 41)
	{ $language = "Romanian";}
	elseif($language == 42)
	{ $language = "Russian";}
	elseif($language == 43)
	{ $language = "Serbian";}
	elseif($language == 44)
	{ $language = "Slovak";}
	elseif($language == 45)
	{ $language = "Somali";}
	elseif($language == 46)
	{ $language = "Spanish";}
	elseif($language == 47)
	{ $language = "Sundanese";}
	elseif($language == 48)
	{ $language = "Swahili";}
	elseif($language == 49)
	{ $language = "Swazi";}
	elseif($language == 50)
	{ $language = "Swedish";}
	elseif($language == 51)
	{ $language = "Tagalog";}
	elseif($language == 52)
	{ $language = "Taiwanese";}
	elseif($language == 53)
	{ $language = "Tamil";}
	elseif($language == 54)
	{ $language = "Telugu";}
	elseif($language == 55)
	{ $language = "Thai";}
	elseif($language == 56)
	{ $language = "Tibetan";}
	elseif($language == 57)
	{ $language = "Tribal";}
	elseif($language == 58)
	{ $language = "Turkish";}
	elseif($language == 59)
	{ $language = "Ukrainian";}
	elseif($language == 60)
	{ $language = "Uzbek";}
	elseif($language == 61)
	{ $language = "Vietnamese";}
	elseif($language == 62)
	{ $language = "Yoruba";}
	else{
		$language = "";}

	return $language;
}

?>