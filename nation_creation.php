<?php
/** nation_creation.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/resource_functions.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$mod_admin = 3;
$page_title_name = 'Create Your Nation';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$pass = $_COOKIE['Key_i_Conquest'];
	$check = mysql_query("SELECT ID, password, nat_exist, mod_admin, IP, IP_block FROM users WHERE username = '$username'")or die(mysql_error());
	while($info = mysql_fetch_array( $check ))
	{
		$ID = $info['ID'];
		$info_pass = $info['password'];
		$info_nat_exist = $info['nat_exist'];
		$mod_admin = $info['mod_admin'];
		$IP = $info['IP'];
		$IP_block = $info['IP_block'];
	}

	// If the cookie has the wrong password, they are taken to the login page
	if($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}

	// Check to see if the user has a nation
	// 0 = no nation; 1 = nation exists; 2 = nation temp-banned; 3 = nation deleted; 
	if($info_nat_exist == 1)
	{
		// Redirect them to the error page
		header("Location: error_page.php?error=41");
	}
	elseif($info_nat_exist == 2)
	{
		// Redirect them to the error page
		header("Location: error_page.php?error=42");
	}
	elseif($info_nat_exist == 3)
	{
		// Redirect them to the error page
		header("Location: error_page.php?error=43");
	}
	else
	{

	// Collect the switchboard information
	$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
	while($switch = mysql_fetch_array( $switch_stats ))
	{
		$site_online = stripslashes($switch['site_online']);
		$ic_version_marker = stripslashes($switch['version']);
		$multiple_nations = stripslashes($switch['multiple_nations']);
	}

	site_online($site_online, $mod_admin);
	multi_check($IP_block, $IP_total, $multiple_nations, $mod_admin);

		// This code runs if the form has been submitted
		if(isset($_POST['submit']))
		{
			$_POST['resources'] = strip_tags($_POST['resources']);
			$_POST['username'] = strip_tags($_POST['username']);
			$_POST['nation'] = strip_tags($_POST['nation']);
			$_POST['title'] = strip_tags($_POST['title']);
			$_POST['capitol'] = strip_tags($_POST['capitol']);
			$_POST['region'] = strip_tags($_POST['region']);
			$_POST['currency'] = strip_tags($_POST['currency']);
			$_POST['language'] = strip_tags($_POST['language']);
			$_POST['ethnicity'] = strip_tags($_POST['ethnicity']);
			$_POST['creed'] = strip_tags($_POST['creed']);
			$_POST['poli_sci'] = strip_tags($_POST['poli_sci']);
			$_POST['zone'] = strip_tags($_POST['zone']);
			$_POST['land_type'] = strip_tags($_POST['land_type']);
			$_POST['tax_rate'] = strip_tags($_POST['tax_rate']);
			$_POST['peace_war'] = strip_tags($_POST['peace_war']);

			// This makes sure they did not leave any fields blank
			if(isset($_POST['resources'], $_POST['username'], $_POST['nation'], $_POST['title'], $_POST['capitol'], $_POST['region'], $_POST['currency'], $_POST['language'], $_POST['ethnicity'], $_POST['creed'], $_POST['poli_sci'], $_POST['zone'], $_POST['land_type'], $_POST['tax_rate'], $_POST['peace_war']))
			{
				if(sanity_check($_POST['resources'], 'string', 5) != FALSE && sanity_check($_POST['username'], 'string', 20) != FALSE && sanity_check($_POST['nation'], 'string', 20) != FALSE && sanity_check($_POST['title'], 'string', 20) != FALSE && sanity_check($_POST['capitol'], 'string', 20) != FALSE && sanity_check($_POST['region'], 'string', 20) != FALSE && sanity_check($_POST['currency'], 'numeric', 2) != FALSE && sanity_check($_POST['language'], 'numeric', 2) != FALSE && sanity_check($_POST['ethnicity'], 'numeric', 2) != FALSE && sanity_check($_POST['creed'], 'numeric', 2) != FALSE && sanity_check($_POST['poli_sci'], 'numeric', 2) != FALSE && sanity_check($_POST['zone'], 'numeric', 2) != FALSE && sanity_check($_POST['land_type'], 'numeric', 2) != FALSE && sanity_check($_POST['tax_rate'], 'numeric', 2) != FALSE && sanity_check($_POST['peace_war'], 'numeric', 2) != FALSE)
				{
					$input_resources = mysql_real_escape_string($_POST['resources']);
					$input_username = mysql_real_escape_string($_POST['username']);
					$input_nation = mysql_real_escape_string($_POST['nation']);
					$input_title = mysql_real_escape_string($_POST['title']);
					$input_capitol = mysql_real_escape_string($_POST['capitol']);
					$input_region = mysql_real_escape_string($_POST['region']);
					$input_currency = mysql_real_escape_string($_POST['currency']);
					$input_language = mysql_real_escape_string($_POST['language']);
					$input_ethnicity = mysql_real_escape_string($_POST['ethnicity']);
					$input_creed = mysql_real_escape_string($_POST['creed']);
					$input_poli_sci = mysql_real_escape_string($_POST['poli_sci']);
					$input_zone_type = mysql_real_escape_string($_POST['zone']);
					$input_land_type = mysql_real_escape_string($_POST['land_type']);
					$input_tax_rate = mysql_real_escape_string($_POST['tax_rate']);
					$input_peace_war = mysql_real_escape_string($_POST['peace_war']);
					$creation = mysql_real_escape_string(gmdate('U'));
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=16");
				}

				$check = mysql_query("SELECT nation FROM nations WHERE nation = '$input_nation'")
				or die(mysql_error());
				$check2 = mysql_num_rows($check);

				// If the nation name exists it gives an error
				if ($check2 != 0)
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=39");
				}

				// Splitting the two resources apart and inputting them seperately into the database
				if($input_resources == "set_1")
				{
					$resource_1 = $_POST['a'];
					$resource_2 = $_POST['b'];
				}
				elseif($input_resources == "set_2")
				{
					$resource_1 = $_POST['c'];
					$resource_2 = $_POST['d'];
				}
				elseif($input_resources == "set_3")
				{
					$resource_1 = $_POST['e'];
					$resource_2 = $_POST['f'];
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=40");
				}

				// Now we insert it into the nation database
				$insert1 = "UPDATE users SET nat_exist='1' WHERE ID='" . $ID . "'";
				$add_member1 = mysql_query($insert1);

				// Now we insert it into the nation database
				$insert2 = "INSERT INTO nations (ID, nation, title, capitol, currency, language, ethnicity, creed, poli_sci, zone, land_type, region, tax_rate, peace_war, creation) VALUES ('" . $ID . "', '" . $input_nation . "', '" . $input_title . "', '" . $input_capitol . "', '" . $input_currency . "', '" . $input_language. "', '" . $input_ethnicity . "', '" . $input_creed . "', '" . $input_poli_sci . "', '" . $input_zone_type . "', '" . $input_land_type . "', '" . $input_region . "', '" . $input_tax_rate . "', '1', '" . $creation . "')";
				$add_member2 = mysql_query($insert2);

				// Now we insert the nation ID into the nation_variables database
				$insert3 = "INSERT INTO nation_variables (ID, last_tax, last_bill, resource_1, resource_2) VALUES ('" . $ID . "', '" . $creation . "', '" . $creation . "', '" . $resource_1 . "', '" . $resource_2 . "')";
				$add_member3 = mysql_query($insert3);

				// Now we insert the nation ID into the military database
				$insert4 = "INSERT INTO military (ID) VALUES ('" . $ID . "')";
				$add_member4 = mysql_query($insert4);

				// Now we insert the nation ID into the civil_works database
				$insert6 = "INSERT INTO civil_works (ID) VALUES ('" . $ID . "')";
				$add_member6 = mysql_query($insert6);

				// Now we insert the nation ID into the civil_works database
				$insert7 = "INSERT INTO deployed (ID) VALUES ('" . $ID . "')";
				$add_member7 = mysql_query($insert7);

				// Now we delete the temp row in the temp database
				$insert8 = "DELETE FROM temp WHERE username = '" . $input_username . "'";
				$add_member8 = mysql_query($insert8);
				?>

				<h1>Nation Created!</h1>
				<?php echo "Thank you, you have registered - you may now visit your <a href='nation.php?ID=" . $ID . "'>nation</a>."; ?>

				<?php
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=36");
			}
		}
		else
		{
			include ('header.php');

			// Determine which side menu to use
			which_side_menu($ID, $mod_admin, $site_area);
			?>

			<td>
			<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
			<table class='main'>
				<tr>
					<th class='form_head' colspan='4'>iC Nation Creation Page</th>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Leader:</td>
					<td class='input'><input type='text' name='username' readonly='readonly' value='<?php echo $username; ?>' maxlength='20' /></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Leader Title: (King, Despot, etc)</td>
					<td class='input'><input type='text' name='title' maxlength='20' /></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Nation Name:</td>
					<td class='input'><input type='text' name='nation' maxlength='20' /></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Capitol City Name:</td>
					<td class='input'><input type='text' name='capitol' maxlength='20' /></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Currency:</td>
					<td class='input'>
						<select name='currency'>
							<option value='1'>Afghani</option>
							<option value='2'>Ariary</option>
							<option value='3'>Baht</option>
							<option value='4'>Balboa</option>
							<option value='5'>Birr</option>
							<option value='6'>Bolivar</option>
							<option value='7'>Cedi</option>
							<option value='8'>Colon</option>
							<option value='9'>Cordoba</option>
							<option value='10'>Dalasi</option>
							<option value='11'>Denar</option>
							<option value='12'>Dinar</option>
							<option value='13'>Dirham</option>
							<option value='14'>Dobra</option>
							<option value='15'>Dollar</option>
							<option value='16'>Dong</option>
							<option value='17'>Dram</option>
							<option value='18'>Escudo</option>
							<option value='19'>Euro</option>
							<option value='20'>Forint</option>
							<option value='21'>Florin</option>
							<option value='22'>Franc</option>
							<option value='23'>Gourde</option>
							<option value='24'>Guarani</option>
							<option value='25'>Guilder</option>
							<option value='26'>Hryvnya</option>
							<option value='27'>Kina</option>
							<option value='28'>Kip</option>
							<option value='29'>Koruna</option>
							<option value='30'>Krona</option>
							<option value='31'>Krone</option>
							<option value='32'>Kroon</option>
							<option value='33'>Kuna</option>
							<option value='34'>Kwacha</option>
							<option value='35'>Kwanza</option>
							<option value='36'>Kyat</option>
							<option value='37'>Lari</option>
							<option value='38'>Lats</option>
							<option value='39'>Lek</option>
							<option value='40'>Lempira</option>
							<option value='41'>Leone</option>
							<option value='42'>Leu</option>
							<option value='43'>Lev</option>
							<option value='44'>Lilangeni</option>
							<option value='45'>Lira</option>
							<option value='46'>Litas</option>
							<option value='47'>Loti</option>
							<option value='48'>Manat</option>
							<option value='49'>Marka</option>
							<option value='50'>Metical</option>
							<option value='51'>Naira</option>
							<option value='52'>Nakfa</option>
							<option value='53'>Ngultrum</option>
							<option value='54'>Nuevo Sol</option>
							<option value='55'>Oro</option>
							<option value='56'>Ouguiya</option>
							<option value='57'>Pa'anga</option>
							<option value='58'>Pataca</option>
							<option value='59'>Peso</option>
							<option value='60'>Pound</option>
							<option value='61'>Pula</option>
							<option value='62'>Qyetzal</option>
							<option value='63'>Rand</option>
							<option value='64'>Real</option>
							<option value='65'>Rial Omani</option>
							<option value='66'>Riel</option>
							<option value='67'>Ringgit</option>
							<option value='68'>Riyal</option>
							<option value='69'>Ruble</option>
							<option value='70'>Rufiyaa</option>
							<option value='71'>Rupee</option>
							<option value='72'>Rupiah</option>
							<option value='73'>Sheqel</option>
							<option value='74'>Shilling</option>
							<option value='75'>Som</option>
							<option value='76'>Somoni</option>
							<option value='77'>Taka</option>
							<option value='78'>Tala</option>
							<option value='79'>Tenge</option>
							<option value='80'>Touman</option>
							<option value='81'>Tugrug</option>
							<option value='82'>Vatu</option>
							<option value='83'>Won</option>
							<option value='84'>Yen</option>
							<option value='85'>Yuan</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Language:</td>
					<td class='input'>
						<select name='language'>
							<option value='1'>Albanian</option>
							<option value='2'>Arabic</option>
							<option value='3'>Armenian</option>
							<option value='4'>Azerbaijani</option>
							<option value='5'>Bantu</option>
							<option value='6'>Bengali</option>
							<option value='7'>Berber</option>
							<option value='8'>Bosnian</option>
							<option value='9'>Burmese</option>
							<option value='10'>Chinese</option>
							<option value='11'>Creole</option>
							<option value='12'>Croatian</option>
							<option value='13'>Czech</option>
							<option value='14'>Dutch</option>
							<option value='15'>English</option>
							<option value='16'>Farsi</option>
							<option value='17'>Finnish</option>
							<option value='18'>French</option>
							<option value='19'>German</option>
							<option value='20'>Greek</option>
							<option value='21'>Gujarati</option>
							<option value='22'>Hebrew</option>
							<option value='23'>Hindu</option>
							<option value='24'>Hungarian</option>
							<option value='25'>Indoneasian</option>
							<option value='26'>Irish</option>
							<option value='27'>Italian</option>
							<option value='28'>Japanese</option>
							<option value='29'>Javanese</option>
							<option value='30'>Korean</option>
							<option value='31'>Kurdish</option>
							<option value='32'>Macedonian</option>
							<option value='33'>Malay</option>
							<option value='34'>Marathi</option>
							<option value='35'>Norwegian</option>
							<option value='36'>Pashto</option>
							<option value='37'>Polish</option>
							<option value='38'>Portuguese</option>
							<option value='39'>Punjabi</option>
							<option value='40'>Quechua</option>
							<option value='41'>Romanian</option>
							<option value='42'>Russian</option>
							<option value='43'>Serbian</option>
							<option value='44'>Slovak</option>
							<option value='45'>Somali</option>
							<option value='46'>Spanish</option>
							<option value='47'>Sundanese</option>
							<option value='48'>Swahili</option>
							<option value='49'>Swazi</option>
							<option value='50'>Swedish</option>
							<option value='51'>Tagalog</option>
							<option value='52'>Taiwanese</option>
							<option value='53'>Tamil</option>
							<option value='54'>Telugu</option>
							<option value='55'>Thai</option>
							<option value='56'>Tibetan</option>
							<option value='57'>Tribal</option>
							<option value='58'>Turkish</option>
							<option value='59'>Ukrainian</option>
							<option value='60'>Uzbek</option>
							<option value='61'>Vietnamese</option>
							<option value='62'>Yoruba</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Ethnicity:</td>
					<td class='input'>
						<select name='ethnicity'>
							<option value='1'>Albanian</option>
							<option value='2'>American</option>
							<option value='3'>Amerindian</option>
							<option value='4'>Arab</option>
							<option value='5'>Armenian</option>
							<option value='6'>Australian</option>
							<option value='7'>Bavarian</option>
							<option value='8'>Berber</option>
							<option value='9'>Bosnian</option>
							<option value='10'>Brazilian</option>
							<option value='11'>British</option>
							<option value='12'>Bulgarian</option>
							<option value='13'>Burman</option>
							<option value='14'>Caucasian</option>
							<option value='15'>Celtic</option>
							<option value='16'>Chilean</option>
							<option value='17'>Chinese</option>
							<option value='18'>Creole</option>
							<option value='19'>Croatian</option>
							<option value='20'>Czech</option>
							<option value='21'>Dutch</option>
							<option value='22'>East African</option>
							<option value='23'>Egyptian</option>
							<option value='24'>Estonian</option>
							<option value='25'>Finnish</option>
							<option value='26'>French</option>
							<option value='27'>German</option>
							<option value='28'>Greek</option>
							<option value='29'>Indian</option>
							<option value='30'>Irish</option>
							<option value='31'>Israeli</option>
							<option value='32'>Italian</option>
							<option value='33'>Japanese</option>
							<option value='34'>Korean</option>
							<option value='35'>Mestizo</option>
							<option value='36'>Mexican</option>
							<option value='37'>Mixed</option>
							<option value='38'>North African</option>
							<option value='39'>Norwegian</option>
							<option value='40'>Pacific Islander</option>
							<option value='41'>Pashtun</option>
							<option value='42'>Persian</option>
							<option value='43'>Peruvian</option>
							<option value='44'>Polish</option>
							<option value='45'>Portuguese</option>
							<option value='46'>Russian</option>
							<option value='47'>Scandinavian</option>
							<option value='48'>Serbian</option>
							<option value='49'>Somalian</option>
							<option value='50'>South African</option>
							<option value='51'>Spanish</option>
							<option value='52'>Swiss</option>
							<option value='53'>Thai</option>
							<option value='54'>Turkish</option>
							<option value='55'>West African</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Creed:</td>
					<td class='input'>
						<select name='creed'>
							<option value='1'>Agnostic</option>
							<option value='2'>Animist</option>
							<option value='3'>Atheist</option>
							<option value='4'>Ayyavazhi</option>
							<option value='5'>Bahá'í Faith</option>
							<option value='6'>Bön</option>
							<option value='7'>Buddhist</option>
							<option value='8'>Catholic</option>
							<option value='9'>Christian</option>
							<option value='10'>Confucian</option>
							<option value='11'>Gnosticism</option>
							<option value='12'>Hindu</option>
							<option value='13'>Indigenous</option>
							<option value='14'>Jainism</option>
							<option value='15'>Jewish</option>
							<option value='16'>Manichaeism</option>
							<option value='17'>Mixed</option>
							<option value='18'>Muslim</option>
							<option value='19'>Orthodox</option>
							<option value='20'>Shamanish</option>
							<option value='21'>Shinto</option>
							<option value='22'>Sikhist</option>
							<option value='23'>Taoist</option>
							<option value='24'>Voodoo</option>
							<option value='25'>Zoroastrianism</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Political System (Government Type):</td>
					<td class='input'>
						<select name='poli_sci'>
							<option value='1'>Absolute Monarchy</option>
							<option value='2'>Aristocracy</option>
							<option value='3'>Communism</option>
							<option value='4'>Constitutional Monarchy</option>
							<option value='5'>Constitutional Republic</option>
							<option value='6'>Corporatism</option>
							<option value='7'>Democratic Socialism</option>
							<option value='8'>Despotism</option>
							<option value='9'>Diarchy</option>
							<option value='10'>Dictatorship</option>
							<option value='11'>Direct Democracy</option>
							<option value='12'>Libertarianism</option>
							<option value='13'>Oligarchy</option>
							<option value='14'>Parliamentary Republic</option>
							<option value='15'>Participatory Democracy</option>
							<option value='16'>Plutocracy</option>
							<option value='17'>Police State</option>
							<option value='18'>Representative Democracy</option>
							<option value='19'>Socialism</option>
							<option value='20'>Theocracy</option>
							<option value='21'>Totalitarianism</option>
							<option value='22'>Tribalism</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Zone:</td>
					<td class='input'>
						<select name='zone'>
							<option value='1'>&Alpha; - Alpha</option>
							<option value='2'>&Beta; - Beta</option>
							<option value='3'>&Gamma; - Gamma</option>
							<option value='4'>&Delta; - Delta</option>
							<option value='5'>&Epsilon; - Epsilon</option>
							<option value='6'>&Zeta; - Zeta</option>
							<option value='7'>&Eta; - Eta</option>
							<option value='8'>&Theta; - Theta</option>
							<option value='9'>&Iota; - Iota</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Initial Land Area:</td>
					<td class='input'>
						<select name='land_type'>
							<option value='1'>Mountain</option>
							<option value='2'>Moorland</option>
							<option value='3'>Tundra</option>
							<option value='4'>Forest</option>
							<option value='5'>Prairie</option>
							<option value='6'>Savannah</option>
							<option value='7'>Polar</option>
							<option value='8'>Desert</option>
							<option value='9'>Marsh</option>
							<option value='10'>Rainforest</option>
							<option value='11'>River Delta</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Land Division Name (State, Province, etc):</td>
					<td class='input'><input type='text' name='region' maxlength='20' /></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Tax Rate:</td>
					<td class='input'>
						<select name='tax_rate'>
							<option value='10'>10%</option>
							<option value='11'>11%</option>
							<option value='12'>12%</option>
							<option value='13'>13%</option>
							<option value='14'>14%</option>
							<option value='15'>15%</option>
							<option value='16'>16%</option>
							<option value='17'>17%</option>
							<option value='18'>18%</option>
							<option value='19'>19%</option>
							<option value='20'>20%</option>
							<option value='21'>21%</option>
							<option value='22'>22%</option>
							<option value='23'>23%</option>
							<option value='24'>24%</option>
							<option value='25'>25%</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td class='input_title_register'>Peace/War Setting:</td>
					<td class='input'>
						<select name='peace_war'>
							<option value='1' selected='selected'>Peace</option>
							<option value='2' disabled='disabled'>War</option>
						</select>
					</td>
					<td></td>
				</tr>
				<?php
				$temp_count = 0;
				// Collect the resource information for display from the temp table
				$temp_stats = mysql_query("SELECT res_1, res_2, res_3, res_4, res_5, res_6 FROM temp WHERE username = '$username'") or die(mysql_error());
				while($temp = mysql_fetch_array( $temp_stats ))
				{
					// Collect the raw data from the temp table in the db 
					$res_1 = stripslashes($temp['res_1']);
					$res_2 = stripslashes($temp['res_2']);
					$res_3 = stripslashes($temp['res_3']);
					$res_4 = stripslashes($temp['res_4']);
					$res_5 = stripslashes($temp['res_5']);
					$res_6 = stripslashes($temp['res_6']);
					$temp_count++;
				}

				if($temp_count == 0)
				{
					// Create three resource pairings for a nation to choose from
					// The larger WHILE loop helps prevent repeated pairs

					while($ab == $cd || $ab == $ef || $cd == $ef)
					{
						while($a == $b)
						{
							$ab = 0;
							$a = rand(1, 15);
							$b = rand(1, 15);
							$ab = $a * $b;
						}
						while($c == $d)
						{
							$cd = 0;
							$c = rand(1, 15);
							$d = rand(1, 15);
							$cd = $c * $d;
						}
						while($e == $f)
						{
							$ef = 0;
							$e = rand(1, 15);
							$f = rand(1, 15);
							$ef = $e * $f;
						}
					}

					// Now we insert the 6 random resources into a temp table to prevent refresh cheating
					$insert5 = "INSERT INTO temp (username, res_1, res_2, res_3, res_4, res_5, res_6) VALUES ('" . $username . "', '" . $a . "', '" . $b . "', '" . $c . "', '" . $d . "', '" . $e . "', '" . $f . "')";
					$add_member5 = mysql_query($insert5);
				}
				else
				{
					$a = $res_1;
					$b = $res_2;
					$c = $res_3;
					$d = $res_4;
					$e = $res_5;
					$f = $res_6;
				}
				?>

				<tr>
					<td></td>
					<td class='input_title'>Resource Selection:</td>
					<td class='input'><input type='radio' name='resources' value='set_1' />
						<?php
							$a_re = $a;
							$b_re = $b;

							// Output the resource images.
							echo res_to_image($a_re) . res_to_image($b_re);
							echo "<input type='hidden' name='a' value='" . $a . "'>";
							echo "<input type='hidden' name='b' value='" . $b . "'>";
						?><br />
					<input type='radio' name='resources' value='set_2' />
						<?php
							$c_re = $c;
							$d_re = $d;
							
							// Output the resource images.
							echo res_to_image($c_re) . res_to_image($d_re);
							echo "<input type='hidden' name='c' value='" . $c . "'>";
							echo "<input type='hidden' name='d' value='" . $d . "'>";
						?><br />
					<input type='radio' name='resources' value='set_3' />
						<?php
							$e_re = $e;
							$f_re = $f;
							
							// Output the resource images.
							echo res_to_image($e_re) . res_to_image($f_re);
							echo "<input type='hidden' name='e' value='" . $e . "'>";
							echo "<input type='hidden' name='f' value='" . $f . "'>";
						?>
					</td>
					<td></td>
				</tr>
				<tr>
					<td class='button' colspan='4'><input type='submit' name='submit' value='Register' /></td>
				</tr>
			</table>
			</form>
			</td>
			</tr>
			</table>
			<?php
			include ('footer.php');
		}
	}
}
else
{
	// If the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?>