<?php
function currency_name($currency)
{
//put the $currency to an english name
	if($currency == 1)
	{ $currency = "Afghani";}
	elseif($currency == 2)
	{ $currency = "Ariary";}
	elseif($currency == 3)
	{ $currency = "Baht";}
	elseif($currency == 4)
	{ $currency = "Balboa";}
	elseif($currency == 5)
	{ $currency = "Birr";}
	elseif($currency == 6)
	{ $currency = "Bolivar";}
	elseif($currency == 7)
	{ $currency = "Cedi";}
	elseif($currency == 8)
	{ $currency = "Colon";}
	elseif($currency == 9)
	{ $currency = "Cordoba";}
	elseif($currency == 10)
	{ $currency = "Dalasi";}
	elseif($currency == 11)
	{ $currency = "Denar";}
	elseif($currency == 12)
	{ $currency = "Dinar";}
	elseif($currency == 13)
	{ $currency = "Dirham";}
	elseif($currency == 14)
	{ $currency = "Dobra";}
	elseif($currency == 15)
	{ $currency = "Dollar";}
	elseif($currency == 16)
	{ $currency = "Dong";}
	elseif($currency == 17)
	{ $currency = "Dram";}
	elseif($currency == 18)
	{ $currency = "Escudo";}
	elseif($currency == 19)
	{ $currency = "Euro";}
	elseif($currency == 20)
	{ $currency = "Forint";}
	elseif($currency == 21)
	{ $currency = "Florin";}
	elseif($currency == 22)
	{ $currency = "Franc";}
	elseif($currency == 23)
	{ $currency = "Gourde";}
	elseif($currency == 24)
	{ $currency = "Guarani";}
	elseif($currency == 25)
	{ $currency = "Guilder";}
	elseif($currency == 26)
	{ $currency = "Hryvnya";}
	elseif($currency == 27)
	{ $currency = "Kina";}
	elseif($currency == 28)
	{ $currency = "Kip";}
	elseif($currency == 29)
	{ $currency = "Koruna";}
	elseif($currency == 30)
	{ $currency = "Krona";}
	elseif($currency == 31)
	{ $currency = "Krone";}
	elseif($currency == 32)
	{ $currency = "Kroon";}
	elseif($currency == 33)
	{ $currency = "Kuna";}
	elseif($currency == 34)
	{ $currency = "Kwacha";}
	elseif($currency == 35)
	{ $currency = "Kwanza";}
	elseif($currency == 36)
	{ $currency = "Kyat";}
	elseif($currency == 37)
	{ $currency = "Lari";}
	elseif($currency == 38)
	{ $currency = "Lats";}
	elseif($currency == 39)
	{ $currency = "Lek";}
	elseif($currency == 40)
	{ $currency = "Lempira";}
	elseif($currency == 41)
	{ $currency = "Leone";}
	elseif($currency == 42)
	{ $currency = "Leu";}
	elseif($currency == 43)
	{ $currency = "Lev";}
	elseif($currency == 44)
	{ $currency = "Lilangeni";}
	elseif($currency == 45)
	{ $currency = "Lira";}
	elseif($currency == 46)
	{ $currency = "Litas";}
	elseif($currency == 47)
	{ $currency = "Loti";}
	elseif($currency == 48)
	{ $currency = "Manat";}
	elseif($currency == 49)
	{ $currency = "Marka";}
	elseif($currency == 50)
	{ $currency = "Metical";}
	elseif($currency == 51)
	{ $currency = "Naira";}
	elseif($currency == 52)
	{ $currency = "Nakfa";}
	elseif($currency == 53)
	{ $currency = "Ngultrum";}
	elseif($currency == 54)
	{ $currency = "Nuevo Sol";}
	elseif($currency == 55)
	{ $currency = "Oro";}
	elseif($currency == 56)
	{ $currency = "Ouguiya";}
	elseif($currency == 57)
	{ $currency = "Pa'anga";}
	elseif($currency == 58)
	{ $currency = "Pataca";}
	elseif($currency == 59)
	{ $currency = "Peso";}
	elseif($currency == 60)
	{ $currency = "Pound";}
	elseif($currency == 61)
	{ $currency = "Pula";}
	elseif($currency == 62)
	{ $currency = "Qyetzal";}
	elseif($currency == 63)
	{ $currency = "Rand";}
	elseif($currency == 64)
	{ $currency = "Real";}
	elseif($currency == 65)
	{ $currency = "Rial Omani";}
	elseif($currency == 66)
	{ $currency = "Riel";}
	elseif($currency == 67)
	{ $currency = "Ringgit";}
	elseif($currency == 68)
	{ $currency = "Riyal";}
	elseif($currency == 69)
	{ $currency = "Ruble";}
	elseif($currency == 70)
	{ $currency = "Rufiyaa";}
	elseif($currency == 71)
	{ $currency = "Rupee";}
	elseif($currency == 72)
	{ $currency = "Rupiah";}
	elseif($currency == 73)
	{ $currency = "Sheqel";}
	elseif($currency == 74)
	{ $currency = "Shilling";}
	elseif($currency == 75)
	{ $currency = "Som";}
	elseif($currency == 76)
	{ $currency = "Somoni";}
	elseif($currency == 77)
	{ $currency = "Taka";}
	elseif($currency == 78)
	{ $currency = "Tala";}
	elseif($currency == 79)
	{ $currency = "Tenge";}
	elseif($currency == 80)
	{ $currency = "Touman";}
	elseif($currency == 81)
	{ $currency = "Tugrug";}
	elseif($currency == 82)
	{ $currency = "Vatu";}
	elseif($currency == 83)
	{ $currency = "Won";}
	elseif($currency == 84)
	{ $currency = "Yen";}
	elseif($currency == 85)
	{ $currency = "Yuan";}
	else
	{
		$currency = "";
	}

	return $currency;
}

?>