<?php
function flag_to_image($flag)
{
// Put the resources to images
	if($flag == 0) { $flag = "<img src='/game/images/flags/none.png' alt='None' title='None'>" ; } 
	elseif($flag == 1) { $flag = "<img src='/game/images/flags/afghanistan.png' alt='Afghanistan' title='Afghanistan'>" ; }
	elseif($flag == 2) { $flag = "<img src='/game/images/flags/albania.png' alt='Albania' title='Albania'>" ; }
	elseif($flag == 3) { $flag = "<img src='/game/images/flags/algeria.png' alt='Algeria' title='Algeria'>" ; }
	elseif($flag == 4) { $flag = "<img src='/game/images/flags/andorra.png' alt='Andorra' title='Andorra'>" ; }
	elseif($flag == 5) { $flag = "<img src='/game/images/flags/angola.png' alt='Angola' title='Angola'>" ; }
	elseif($flag == 6) { $flag = "<img src='/game/images/flags/anguilla.png' alt='Anguilla' title='Anguilla'>" ; }
	elseif($flag == 7) { $flag = "<img src='/game/images/flags/antigua.png' alt='Antigua' title='Antigua'>" ; }
	elseif($flag == 8) { $flag = "<img src='/game/images/flags/argentina.png' alt='Argentina' title='Argentina'>" ; }
	elseif($flag == 9) { $flag = "<img src='/game/images/flags/armenia.png' alt='Armenia' title='Arnenia'>" ; }
	elseif($flag == 10) { $flag = "<img src='/game/images/flags/australia.png' alt='Australia' title='Australia'>" ; }
	elseif($flag == 11) { $flag = "<img src='/game/images/flags/austria.png' alt='Austria' title='Austria'>" ; }
	elseif($flag == 12) { $flag = "<img src='/game/images/flags/azerbaijan.png' alt='Azerbaijan' title='Azerbaijan'>" ; }
	elseif($flag == 13) { $flag = "<img src='/game/images/flags/bahamas.png' alt='Bahamas' title='Bahamas'>" ; }
	elseif($flag == 14) { $flag = "<img src='/game/images/flags/bahrain.png' alt='Bahrain' title='Bahrain'>" ; }
	elseif($flag == 15) { $flag = "<img src='/game/images/flags/bangladesh.png' alt='Bangladesh' title='Bangladesh'>" ; }
	elseif($flag == 16) { $flag = "<img src='/game/images/flags/barbados.png' alt='Barbados' title='Barbados'>" ; }
	elseif($flag == 17) { $flag = "<img src='/game/images/flags/belarus.png' alt='Belarus' title='Belarus'>" ; }
	elseif($flag == 18) { $flag = "<img src='/game/images/flags/belgium.png' alt='Belgium' title='Belgium'>" ; }
	elseif($flag == 19) { $flag = "<img src='/game/images/flags/belize.png' alt='Belize' title='Belize'>" ; }
	elseif($flag == 20) { $flag = "<img src='/game/images/flags/benin.png' alt='Benin' title='Benin'>" ; }
	elseif($flag == 21) { $flag = "<img src='/game/images/flags/bhutan.png' alt='Bhutan' title='Bhutan'>" ; }
	elseif($flag == 22) { $flag = "<img src='/game/images/flags/bolivia.png' alt='Bolivia' title='Bolivia'>" ; }
	elseif($flag == 23) { $flag = "<img src='/game/images/flags/botswana.png' alt='Botswana' title='Botswana'>" ; }
	elseif($flag == 24) { $flag = "<img src='/game/images/flags/brunei.png' alt='Brunei' title='Brunei'>" ; }
	elseif($flag == 25) { $flag = "<img src='/game/images/flags/bulgaria.png' alt='Bulgaria' title='Bulgaria'>" ; }
	elseif($flag == 26) { $flag = "<img src='/game/images/flags/burkina.png' alt='Burkina' title='Burkina'>" ; }
	elseif($flag == 27) { $flag = "<img src='/game/images/flags/burma.png' alt='Burma' title='Burma'>" ; }
	elseif($flag == 28) { $flag = "<img src='/game/images/flags/burundi.png' alt='Burundi' title='Burundi'>" ; }
	elseif($flag == 29) { $flag = "<img src='/game/images/flags/cambodia.png' alt='Cambodia' title='Cambodia'>" ; }
	elseif($flag == 30) { $flag = "<img src='/game/images/flags/cameroon.png' alt='Cameroon' title='Cameroon'>" ; }
	elseif($flag == 31) { $flag = "<img src='/game/images/flags/canada.png' alt='Canada' title='Canada'>" ; }
	elseif($flag == 32) { $flag = "<img src='/game/images/flags/capeverde.png' alt='Cape Verde' title='Cape Verde'>" ; }
	elseif($flag == 33) { $flag = "<img src='/game/images/flags/car.png' alt='Central African Republic' title='Central African Republic'>" ; }
	elseif($flag == 34) { $flag = "<img src='/game/images/flags/chad.png' alt='Chad' title='Chad'>" ; }
	elseif($flag == 35) { $flag = "<img src='/game/images/flags/chile.png' alt='Chile' title='Chile'>" ; }
	elseif($flag == 36) { $flag = "<img src='/game/images/flags/china.png' alt='China' title='China'>" ; }
	elseif($flag == 37) { $flag = "<img src='/game/images/flags/colombia.png' alt='Colombia' title='Colombia'>" ; }
	elseif($flag == 38) { $flag = "<img src='/game/images/flags/comoros.png' alt='Comoros' title='Comoros'>" ; }
	elseif($flag == 39) { $flag = "<img src='/game/images/flags/congo.png' alt='Congo' title='Congo'>" ; }
	elseif($flag == 40) { $flag = "<img src='/game/images/flags/costarica.png' alt='Costa Rica' title='Costa Rica'>" ; }
	elseif($flag == 41) { $flag = "<img src='/game/images/flags/cuba.png' alt='Cuba' title='Cuba'>" ; }
	elseif($flag == 42) { $flag = "<img src='/game/images/flags/cyprus.png' alt='Cyprus' title='Cyprus'>" ; }
	elseif($flag == 43) { $flag = "<img src='/game/images/flags/czech.png' alt='Czech Republic' title='Czech Republic'>" ; }
	elseif($flag == 44) { $flag = "<img src='/game/images/flags/denmark.png' alt='Denmark' title='Denmark'>" ; }
	elseif($flag == 45) { $flag = "<img src='/game/images/flags/djibouti.png' alt='Djibouti' title='Dijbouti'>" ; }
	elseif($flag == 46) { $flag = "<img src='/game/images/flags/dominican.png' alt='Dominican Republic' title='Dominican Republic'>" ; }
	elseif($flag == 47) { $flag = "<img src='/game/images/flags/ecuador.png' alt='Ecuador' title='Ecuador'>" ; }
	elseif($flag == 48) { $flag = "<img src='/game/images/flags/egypt.png' alt='Egypt' title='Egypt'>" ; }
	elseif($flag == 49) { $flag = "<img src='/game/images/flags/elsalvador.png' alt='El Salvador' title='El Salvador'>" ; }
	elseif($flag == 50) { $flag = "<img src='/game/images/flags/equatorial.png' alt='Equatorial Guinea' title='Equatorial Guinea'>" ; }
	elseif($flag == 51) { $flag = "<img src='/game/images/flags/eritrea.png' alt='Eritrea' title='Eritrea'>" ; }
	elseif($flag == 52) { $flag = "<img src='/game/images/flags/ethiopia.png' alt='Ethiopia' title='Ethiopia'>" ; }
	elseif($flag == 53) { $flag = "<img src='/game/images/flags/eu.png' alt='European Union' title='European Union'>" ; }
	elseif($flag == 54) { $flag = "<img src='/game/images/flags/fiji.png' alt='Fiji' title='Fiji'>" ; }
	elseif($flag == 55) { $flag = "<img src='/game/images/flags/finland.png' alt='Finland' title='Finland'>" ; }
	elseif($flag == 56) { $flag = "<img src='/game/images/flags/france.png' alt='France' title='France'>" ; }
	elseif($flag == 57) { $flag = "<img src='/game/images/flags/gabon.png' alt='Gabon' title='Gabon'>" ; }
	elseif($flag == 58) { $flag = "<img src='/game/images/flags/gambia.png' alt='Gambia' title='Gambia'>" ; }
	elseif($flag == 59) { $flag = "<img src='/game/images/flags/georgia.png' alt='Georgia' title='Georgia'>" ; }
	elseif($flag == 60) { $flag = "<img src='/game/images/flags/germany.png' alt='Germany' title='Germany'>" ; }
	elseif($flag == 61) { $flag = "<img src='/game/images/flags/gibraltar.png' alt='Gibraltar' title='Gibraltar'>" ; }
	elseif($flag == 62) { $flag = "<img src='/game/images/flags/greece.png' alt='Greece' title='Greece'>" ; }
	elseif($flag == 63) { $flag = "<img src='/game/images/flags/greenland.png' alt='Greenland' title='Greenland'>" ; }
	elseif($flag == 64) { $flag = "<img src='/game/images/flags/grenada.png' alt='Grenada' title='Grenada'>" ; }
	elseif($flag == 65) { $flag = "<img src='/game/images/flags/guam.png' alt='Guam' title='Guam'>" ; }
	elseif($flag == 66) { $flag = "<img src='/game/images/flags/guatemala.png' alt='Guatemala' title='Guatemala'>" ; }
	elseif($flag == 67) { $flag = "<img src='/game/images/flags/guineabissau.png' alt='Guinea-Bissau' title='Guinea-Bissau'>" ; }
	elseif($flag == 68) { $flag = "<img src='/game/images/flags/guinea.png' alt='Guinea' title='Guinea'>" ; }
	elseif($flag == 69) { $flag = "<img src='/game/images/flags/guyana.png' alt='Guyana' title='Guyana'>" ; }
	elseif($flag == 70) { $flag = "<img src='/game/images/flags/haiti.png' alt='Haiti' title='Haiti'>" ; }
	elseif($flag == 71) { $flag = "<img src='/game/images/flags/holysee.png' alt='Vatican' title='Vatican'>" ; }
	elseif($flag == 72) { $flag = "<img src='/game/images/flags/honduras.png' alt='Honduras' title='Honduras'>" ; }
	elseif($flag == 73) { $flag = "<img src='/game/images/flags/hongkong.png' alt='Hong Kong' title='Hong Kong'>" ; }
	elseif($flag == 74) { $flag = "<img src='/game/images/flags/hungary.png' alt='Hungary' title='Hungary'>" ; }
	elseif($flag == 75) { $flag = "<img src='/game/images/flags/iceland.png' alt='Iceland' title='Iceland'>" ; }
	elseif($flag == 76) { $flag = "<img src='/game/images/flags/india.png' alt='India' title='India'>" ; }
	elseif($flag == 77) { $flag = "<img src='/game/images/flags/indonesia.png' alt='Indonesia' title='Indonesia'>" ; }
	elseif($flag == 78) { $flag = "<img src='/game/images/flags/iran.png' alt='Iran' title='Iran'>" ; }
	elseif($flag == 79) { $flag = "<img src='/game/images/flags/iraq.png' alt='Iraq' title='Iraq'>" ; }
	elseif($flag == 80) { $flag = "<img src='/game/images/flags/ireland.png' alt='Ireland' title='Ireland'>" ; }
	elseif($flag == 81) { $flag = "<img src='/game/images/flags/isleofman.png' alt='Isle of Man' title='Isle of Man'>" ; }
	elseif($flag == 82) { $flag = "<img src='/game/images/flags/israel.png' alt='Israel' title='Israel'>" ; }
	elseif($flag == 83) { $flag = "<img src='/game/images/flags/italy.png' alt='Italy' title='Italy'>" ; }
	elseif($flag == 84) { $flag = "<img src='/game/images/flags/jamaica.png' alt='Jamaica' title='Jamaica'>" ; }
	elseif($flag == 85) { $flag = "<img src='/game/images/flags/janmayen.png' alt='Janmayen' title='Janmayen'>" ; }
	elseif($flag == 86) { $flag = "<img src='/game/images/flags/japan.png' alt='Japan' title='Japan'>" ; }
	elseif($flag == 87) { $flag = "<img src='/game/images/flags/jarvis.png' alt='Jarvis' title='Jarvis'>" ; }
	elseif($flag == 88) { $flag = "<img src='/game/images/flags/jersey.png' alt='Jersey' title='Jersey'>" ; }
	elseif($flag == 89) { $flag = "<img src='/game/images/flags/jordan.png' alt='Jordan' title='Jordan'>" ; }
	elseif($flag == 90) { $flag = "<img src='/game/images/flags/kazakhstan.png' alt='Kazakhstan' title='Kazakhstan'>" ; }
	elseif($flag == 91) { $flag = "<img src='/game/images/flags/kenya.png' alt='Kenya' title='Kenya'>" ; }
	elseif($flag == 92) { $flag = "<img src='/game/images/flags/southkorea.png' alt='South Korea' title='South Korea'>" ; }
	elseif($flag == 93) { $flag = "<img src='/game/images/flags/kuwait.png' alt='Kuwait' title='Kuwait'>" ; }
	elseif($flag == 94) { $flag = "<img src='/game/images/flags/kyrgyzstan.png' alt='Kyrgyzstan' title='Kyrgyzstan'>" ; }
	elseif($flag == 95) { $flag = "<img src='/game/images/flags/laos.png' alt='Laos' title='Laos'>" ; }
	elseif($flag == 96) { $flag = "<img src='/game/images/flags/lativa.png' alt='Lativa' title='Lativa'>" ; }
	elseif($flag == 97) { $flag = "<img src='/game/images/flags/lebanon.png' alt='Lebanon' title='Lebanon'>" ; }
	elseif($flag == 98) { $flag = "<img src='/game/images/flags/lesotho.png' alt='Lesotho' title='Lesotho'>" ; }
	elseif($flag == 99) { $flag = "<img src='/game/images/flags/liberia.png' alt='Liberia' title='Liberia'>" ; }
	elseif($flag == 100) { $flag = "<img src='/game/images/flags/libya.png' alt='Libya' title='Libya'>" ; }
	elseif($flag == 101) { $flag = "<img src='/game/images/flags/liechtenstein.png' alt='Liechtenstein' title='Liechtenstein'>" ; }
	elseif($flag == 102) { $flag = "<img src='/game/images/flags/lithuania.png' alt='Lithuania' title='Lithuania'>" ; }
	elseif($flag == 103) { $flag = "<img src='/game/images/flags/luxembourg.png' alt='Luxembourg' title='luxembourg'>" ; }
	elseif($flag == 104) { $flag = "<img src='/game/images/flags/macau.png' alt='Macau' title='Macau'>" ; }
	elseif($flag == 105) { $flag = "<img src='/game/images/flags/macedonia.png' alt='Macedonia' title='Macedonia'>" ; }
	elseif($flag == 106) { $flag = "<img src='/game/images/flags/madagascar.png' alt='Madagascar' title='Madagascar'>" ; }
	elseif($flag == 107) { $flag = "<img src='/game/images/flags/malawi.png' alt='Malawi' title='Malawi'>" ; }
	elseif($flag == 108) { $flag = "<img src='/game/images/flags/malaysia.png' alt='Malaysia' title='Malaysia'>" ; }
	elseif($flag == 109) { $flag = "<img src='/game/images/flags/maldives.png' alt='Maldives' title='Maldives'>" ; }
	elseif($flag == 110) { $flag = "<img src='/game/images/flags/mali.png' alt='Mali' title='Mali'>" ; }
	elseif($flag == 111) { $flag = "<img src='/game/images/flags/malta.png' alt='Malta' title='Malta'>" ; }
	elseif($flag == 112) { $flag = "<img src='/game/images/flags/marshall.png' alt='Marshall' title='Marshall'>" ; }
	elseif($flag == 113) { $flag = "<img src='/game/images/flags/mauritania.png' alt='Mauritania' title='Mauritania'>" ; }
	elseif($flag == 114) { $flag = "<img src='/game/images/flags/mauritius.png' alt='Mauritius' title='Mauritius'>" ; }
	elseif($flag == 115) { $flag = "<img src='/game/images/flags/mayotte.png' alt='Mayotte' title='Mayotte'>" ; }
	elseif($flag == 116) { $flag = "<img src='/game/images/flags/mexico.png' alt='Mexico' title='Mexico'>" ; }
	elseif($flag == 117) { $flag = "<img src='/game/images/flags/micronesia.png' alt='Micronesia' title='Micronesia'>" ; }
	elseif($flag == 118) { $flag = "<img src='/game/images/flags/moldova.png' alt='Moldova' title='Moldova'>" ; }
	elseif($flag == 119) { $flag = "<img src='/game/images/flags/monaco.png' alt='Monaco' title='Monaco'>" ; }
	elseif($flag == 120) { $flag = "<img src='/game/images/flags/mongolia.png' alt='Mongolia' title='Mongolia'>" ; }
	elseif($flag == 121) { $flag = "<img src='/game/images/flags/montenegro.png' alt='Montenegro' title='Montenegro'>" ; }
	elseif($flag == 122) { $flag = "<img src='/game/images/flags/montserrat.png' alt='Montserrat' title='Montserrat'>" ; }
	elseif($flag == 123) { $flag = "<img src='/game/images/flags/morocco.png' alt='Morocco' title='Morocco'>" ; }
	elseif($flag == 124) { $flag = "<img src='/game/images/flags/mozambique.png' alt='Mozambique' title='Mozambique'>" ; }
	elseif($flag == 125) { $flag = "<img src='/game/images/flags/namibia.png' alt='Namibia' title='Namibia'>" ; }
	elseif($flag == 126) { $flag = "<img src='/game/images/flags/nauru.png' alt='Nauru' title='Nauru'>" ; }
	elseif($flag == 127) { $flag = "<img src='/game/images/flags/nepal.png' alt='Nepal' title='Nepal'>" ; }
	elseif($flag == 128) { $flag = "<img src='/game/images/flags/netherlands.png' alt='Netherlands' title='Netherlands'>" ; }
	elseif($flag == 129) { $flag = "<img src='/game/images/flags/newcaledonia.png' alt='New Caledonia' title='New Caledonia'>" ; }
	elseif($flag == 130) { $flag = "<img src='/game/images/flags/newzealand.png' alt='New Zealand' title='New Zealand'>" ; }
	elseif($flag == 131) { $flag = "<img src='/game/images/flags/nicaragua.png' alt='Nicaragua' title='Nicaragua'>" ; }
	elseif($flag == 132) { $flag = "<img src='/game/images/flags/niger.png' alt='Niger' title='Niger'>" ; }
	elseif($flag == 133) { $flag = "<img src='/game/images/flags/nigeria.png' alt='Nigeria' title='Nigeria'>" ; }
	elseif($flag == 134) { $flag = "<img src='/game/images/flags/niue.png' alt='Niue' title='Niue'>" ; }
	elseif($flag == 135) { $flag = "<img src='/game/images/flags/northkorea.png' alt='North Korea' title='North Korea'>" ; }
	elseif($flag == 136) { $flag = "<img src='/game/images/flags/norway.png' alt='Norway' title='Norway'>" ; }
	elseif($flag == 137) { $flag = "<img src='/game/images/flags/oman.png' alt='Oman' title='Oman'>" ; }
	elseif($flag == 138) { $flag = "<img src='/game/images/flags/pakistan.png' alt='Pakistan' title='Pakistan'>" ; }
	elseif($flag == 139) { $flag = "<img src='/game/images/flags/palau.png' alt='Palau' title='Palau'>" ; }
	elseif($flag == 140) { $flag = "<img src='/game/images/flags/panama.png' alt='Panama' title='Panama'>" ; }
	elseif($flag == 141) { $flag = "<img src='/game/images/flags/papua.png' alt='Papua' title='Papua'>" ; }
	elseif($flag == 142) { $flag = "<img src='/game/images/flags/paraguay.png' alt='Paraguay' title='Paraguay'>" ; }
	elseif($flag == 143) { $flag = "<img src='/game/images/flags/peru.png' alt='Peru' title='Peru'>" ; }
	elseif($flag == 144) { $flag = "<img src='/game/images/flags/philippines.png' alt='Philippines' title='Philippines'>" ; }
	elseif($flag == 145) { $flag = "<img src='/game/images/flags/poland.png' alt='Poland' title='Poland'>" ; }
	elseif($flag == 146) { $flag = "<img src='/game/images/flags/portugal.png' alt='Portugal' title='Portugal'>" ; }
	elseif($flag == 147) { $flag = "<img src='/game/images/flags/puertorico.png' alt='Puerto Rico' title='Puerto Rico'>" ; }
	elseif($flag == 148) { $flag = "<img src='/game/images/flags/qatar.png' alt='Qatar' title='Qatar'>" ; }
	elseif($flag == 149) { $flag = "<img src='/game/images/flags/romania.png' alt='Romania' title='Romania'>" ; }
	elseif($flag == 150) { $flag = "<img src='/game/images/flags/rwanda.png' alt='Rwanda' title='Rwanda'>" ; }
	elseif($flag == 151) { $flag = "<img src='/game/images/flags/stpierre.png' alt='St. Pierre' title='St. Pierre'>" ; }
	elseif($flag == 152) { $flag = "<img src='/game/images/flags/stvincent.png' alt='St. Vincent' title='St. Vincent'>" ; }
	elseif($flag == 153) { $flag = "<img src='/game/images/flags/stbart.png' alt='St. Bartholomew' title='St. Bartholomew'>" ; }
	elseif($flag == 154) { $flag = "<img src='/game/images/flags/sthelena.png' alt='St. Helena' title='St. Helena'>" ; }
	elseif($flag == 155) { $flag = "<img src='/game/images/flags/stkitts.png' alt='St. Kitts' title='St. Kitts'>" ; }
	elseif($flag == 156) { $flag = "<img src='/game/images/flags/stlucia.png' alt='St. Lucia' title='St. Lucia'>" ; }
	elseif($flag == 157) { $flag = "<img src='/game/images/flags/stmartin.png' alt='St. Martin' title='St. Martin'>" ; }
	elseif($flag == 158) { $flag = "<img src='/game/images/flags/samoa.png' alt='Samoa' title='Samoa'>" ; }
	elseif($flag == 159) { $flag = "<img src='/game/images/flags/sanmarino.png' alt='San Marino' title='San Marino'>" ; }
	elseif($flag == 160) { $flag = "<img src='/game/images/flags/saotome.png' alt='Sao Tome' title='Sao Tome'>" ; }
	elseif($flag == 161) { $flag = "<img src='/game/images/flags/saudiarabia.png' alt='Saudi Arabia' title='Saudi Arabia'>" ; }
	elseif($flag == 162) { $flag = "<img src='/game/images/flags/senegal.png' alt='Senegal' title='Senegal'>" ; }
	elseif($flag == 163) { $flag = "<img src='/game/images/flags/serbia.png' alt='Serbia' title='Serbia'>" ; }
	elseif($flag == 164) { $flag = "<img src='/game/images/flags/seychelles.png' alt='Seychelles' title='Seychelles'>" ; }
	elseif($flag == 165) { $flag = "<img src='/game/images/flags/sierraleone.png' alt='Sierra Leone' title='Sierra Leone'>" ; }
	elseif($flag == 166) { $flag = "<img src='/game/images/flags/singapore.png' alt='Singapore' title='Singapore'>" ; }
	elseif($flag == 167) { $flag = "<img src='/game/images/flags/slovakia.png' alt='Slovakia' title='Slovakia'>" ; }
	elseif($flag == 168) { $flag = "<img src='/game/images/flags/slovenia.png' alt='Slovenia' title='Slovenia'>" ; }
	elseif($flag == 169) { $flag = "<img src='/game/images/flags/solomon.png' alt='Solomon' title='Solomon'>" ; }
	elseif($flag == 170) { $flag = "<img src='/game/images/flags/somalia.png' alt='Somalia' title='Somalia'>" ; }
	elseif($flag == 171) { $flag = "<img src='/game/images/flags/southafrica.png' alt='South Africa' title='South Africa'>" ; }
	elseif($flag == 172) { $flag = "<img src='/game/images/flags/southgeorgia.png' alt='South Georgia' title='South Georgia'>" ; }
	elseif($flag == 173) { $flag = "<img src='/game/images/flags/spain.png' alt='Spain' title='Spain'>" ; }
	elseif($flag == 174) { $flag = "<img src='/game/images/flags/srilanka.png' alt='Sri Lanka' title='Sri Lanka'>" ; }
	elseif($flag == 175) { $flag = "<img src='/game/images/flags/sudan.png' alt='Sudan' title='Sudan'>" ; }
	elseif($flag == 176) { $flag = "<img src='/game/images/flags/suriname.png' alt='Suriname' title='Suriname'>" ; }
	elseif($flag == 177) { $flag = "<img src='/game/images/flags/svalbard.png' alt='Svalbard' title='Svalbard'>" ; }
	elseif($flag == 178) { $flag = "<img src='/game/images/flags/swaziland.png' alt='Swaziland' title='Swaziland'>" ; }
	elseif($flag == 179) { $flag = "<img src='/game/images/flags/sweden.png' alt='Sweden' title='Sweden'>" ; }
	elseif($flag == 180) { $flag = "<img src='/game/images/flags/switzerland.png' alt='Switzerland' title='Switzerland'>" ; }
	elseif($flag == 181) { $flag = "<img src='/game/images/flags/syria.png' alt='Syria' title='Syria'>" ; }
	elseif($flag == 182) { $flag = "<img src='/game/images/flags/taiwan.png' alt='Taiwan' title='Taiwan'>" ; }
	elseif($flag == 183) { $flag = "<img src='/game/images/flags/tajikistan.png' alt='Tajikistan' title='Tajikistan'>" ; }
	elseif($flag == 184) { $flag = "<img src='/game/images/flags/tazania.png' alt='Tazania' title='Tazania'>" ; }
	elseif($flag == 185) { $flag = "<img src='/game/images/flags/thailand.png' alt='Thailand' title='Thailand'>" ; }
	elseif($flag == 186) { $flag = "<img src='/game/images/flags/timor.png' alt='Timor' title='Timor'>" ; }
	elseif($flag == 187) { $flag = "<img src='/game/images/flags/togo.png' alt='Togo' title='Togo'>" ; }
	elseif($flag == 188) { $flag = "<img src='/game/images/flags/tokelau.png' alt='Tokelau' title='Tokelau'>" ; }
	elseif($flag == 189) { $flag = "<img src='/game/images/flags/tonga.png' alt='Tonga' title='Tonga'>" ; }
	elseif($flag == 190) { $flag = "<img src='/game/images/flags/trinidad.png' alt='Trinidad' title='Trinidad'>" ; }
	elseif($flag == 191) { $flag = "<img src='/game/images/flags/tunisia.png' alt='Tunisia' title='Tunisia'>" ; }
	elseif($flag == 192) { $flag = "<img src='/game/images/flags/turkey.png' alt='Turkey' title='Turkey'>" ; }
	elseif($flag == 193) { $flag = "<img src='/game/images/flags/turkmenistan.png' alt='Turkmenistan' title='Turkmenistan'>" ; }
	elseif($flag == 194) { $flag = "<img src='/game/images/flags/turks.png' alt='Turks' title='Turks'>" ; }
	elseif($flag == 195) { $flag = "<img src='/game/images/flags/tuvalu.png' alt='Tuvalu' title='Tuvalu'>" ; }
	elseif($flag == 196) { $flag = "<img src='/game/images/flags/uganda.png' alt='Uganda' title='Uganda'>" ; }
	elseif($flag == 197) { $flag = "<img src='/game/images/flags/ukraine.png' alt='Ukraine' title='Ukraine'>" ; }
	elseif($flag == 198) { $flag = "<img src='/game/images/flags/uae.png' alt='United Arab Emirates' title='United Arab Emirates'>" ; }
	elseif($flag == 199) { $flag = "<img src='/game/images/flags/uk.png' alt='United Kingdom' title='United Kingdom'>" ; }
	elseif($flag == 200) { $flag = "<img src='/game/images/flags/us.png' alt='United States' title='United States'>" ; }
	elseif($flag == 201) { $flag = "<img src='/game/images/flags/uruguay.png' alt='Uruguay' title='Uruguay'>" ; }
	elseif($flag == 202) { $flag = "<img src='/game/images/flags/uzbekistan.png' alt='Uzbekistan' title='Uzbekistan'>" ; }
	elseif($flag == 203) { $flag = "<img src='/game/images/flags/vanuatu.png' alt='Vanuatu' title='Vanuatu'>" ; }
	elseif($flag == 204) { $flag = "<img src='/game/images/flags/vietnam.png' alt='Vietnam' title='Vietnam'>" ; }
	elseif($flag == 205) { $flag = "<img src='/game/images/flags/virgin.png' alt='Virgin Islands' title='Virgin Islands'>" ; }
	elseif($flag == 206) { $flag = "<img src='/game/images/flags/wallis.png' alt='Wallis' title='Wallis'>" ; }
	elseif($flag == 207) { $flag = "<img src='/game/images/flags/yemen.png' alt='Yemen' title='Yemen'>" ; }
	elseif($flag == 208) { $flag = "<img src='/game/images/flags/zambia.png' alt='Zambia' title='Zambia'>" ; }
	elseif($flag == 209) { $flag = "<img src='/game/images/flags/zimbabwe.png' alt='Zimbabwe' title='Zimbabwe'>" ; }
	else{ $flag = "<img src='/game/images/flags/none.png' alt='None' title='None'>" ; }

	return $flag;
}
?>