<?php
/** nation_settings.php **/

// database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/side_menu_functions.php');
include ('functions/flag_to_image.php');
include ('functions/form_input_check_functions.php');
include ('functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'main';
$page_title_name = 'Change Nation Settings';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

//checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$pass = $_COOKIE['Key_i_Conquest'];
	$check = mysql_query("SELECT ID, password, mod_admin FROM users WHERE username = '$username'")or die(mysql_error());
	while($info = mysql_fetch_array( $check ))
	{
		$ID = $info['ID'];
		$info_pass = $info['password'];
		$mod_admin = $info['mod_admin'];
	}

	//if the cookie has the wrong password, they are taken to the expired session login page
	if($pass != $info_pass)
	{
		header("Location: expiredsession.php");
	}
	else
	{
		//otherwise they are shown the nation settings edit page

		// Collect the switchboard information
		$switch_stats = mysql_query("SELECT site_online, version, multiple_nations FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
		while($switch = mysql_fetch_array( $switch_stats ))
		{
			$site_online = stripslashes($switch['site_online']);
			$ic_version_marker = stripslashes($switch['version']);
		}

		site_online($site_online, $mod_admin);

		//collect the nation information for display from the nations table
		$nationstats = mysql_query("SELECT currency, language, ethnicity, creed, poli_sci, zone, land_type, tax_rate, peace_war, flag FROM nations WHERE ID = '$ID'") or die(mysql_error());
		while($row = mysql_fetch_array( $nationstats ))
		{
			// Collect the raw data from the nations table in the db 
			$currency = stripslashes($row['currency']);
			$language = stripslashes($row['language']);
			$ethnicity = stripslashes($row['ethnicity']);
			$creed = stripslashes($row['creed']);
			$poli_sci = stripslashes($row['poli_sci']);
			$zone = stripslashes($row['zone']);
			$land_type = stripslashes($row['land_type']);
			$tax_rate = stripslashes($row['tax_rate']);
			$peace_war = stripslashes($row['peace_war']);
			$flag = stripslashes($row['flag']);
		}

		//if the nation settings form is submitted
		if(isset($_POST['submit']))
		{
			$_POST['currency'] = strip_tags($_POST['currency']);
			$_POST['language'] = strip_tags($_POST['language']);
			$_POST['ethnicity'] = strip_tags($_POST['ethnicity']);
			$_POST['creed'] = strip_tags($_POST['creed']);
			$_POST['poli_sci'] = strip_tags($_POST['poli_sci']);
			$_POST['zone'] = strip_tags($_POST['zone']);
			$_POST['land_type'] = strip_tags($_POST['land_type']);
			$_POST['tax_rate'] = strip_tags($_POST['tax_rate']);
			$_POST['peace_war'] = strip_tags($_POST['peace_war']);
			$_POST['newflag'] = strip_tags($_POST['newflag']);

			if(isset($_POST['newflag'], $_POST['currency'], $_POST['language'], $_POST['ethnicity'], $_POST['creed'], $_POST['poli_sci'], $_POST['zone'], $_POST['land_type'], $_POST['tax_rate'], $_POST['peace_war']))
			{
				if(sanity_check($_POST['newflag'], 'numeric', 3) != FALSE && sanity_check($_POST['currency'], 'numeric', 2) != FALSE && sanity_check($_POST['language'], 'numeric', 2) != FALSE && sanity_check($_POST['ethnicity'], 'numeric', 2) != FALSE && sanity_check($_POST['creed'], 'numeric', 2) != FALSE && sanity_check($_POST['poli_sci'], 'numeric', 2) != FALSE && sanity_check($_POST['zone'], 'numeric', 2) != FALSE && sanity_check($_POST['land_type'], 'numeric', 2) != FALSE && sanity_check($_POST['tax_rate'], 'numeric', 2) != FALSE && sanity_check($_POST['peace_war'], 'numeric', 1) != FALSE)
				{
					$newcurrency = mysql_real_escape_string($_POST['currency']);
					$newlanguage = mysql_real_escape_string($_POST['language']);
					$newethnicity = mysql_real_escape_string($_POST['ethnicity']);
					$newcreed = mysql_real_escape_string($_POST['creed']);
					$newpolisci = mysql_real_escape_string($_POST['poli_sci']);
					$newzone = mysql_real_escape_string($_POST['zone']);
					$newlandtype = mysql_real_escape_string($_POST['land_type']);
					$newtaxrate = mysql_real_escape_string($_POST['tax_rate']);
					$newpeacewar = mysql_real_escape_string($_POST['peace_war']);
					$newflag = mysql_real_escape_string($_POST['newflag']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=38");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			// update the nation settings to the new ones!
			$insert = "UPDATE nations SET currency='" . $newcurrency . "', language='" . $newlanguage . "', ethnicity='" . $newethnicity . "', creed='" . $newcreed . "', poli_sci='" . $newpolisci . "', zone='" . $newzone . "', land_type='" . $newlandtype . "', tax_rate='" . $newtaxrate . "', peace_war='1', flag='" . $newflag . "' WHERE ID='" . $ID . "'";
			$add_member = mysql_query($insert);

			//then redirect them to the nation
			header("Location: nation.php?ID=$ID");
		}
		include ('header.php');
		
		// Determine which side menu to use
		which_side_menu($ID, $mod_admin, $site_area);
		?>
		<td>
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
		<table class='list_central'>
			<tr>
				<th class='list_central_header' colspan='2'>Update Nation Settings</th>
			</tr>
			<?php
				switch ($flag)
				{
					case "0":
					$_0 = "selected";
					break;
					case "1":
					$_1 = "selected";
					break;
					case "2":
					$_2 = "selected";
					break;
					case "3":
					$_3 = "selected";
					break;
					case "4":
					$_4 = "selected";
					break;
					case "5":
					$_5 = "selected";
					break;
					case "6":
					$_6 = "selected";
					break;
					case "7":
					$_7 = "selected";
					break;
					case "8":
					$_8 = "selected";
					break;
					case "9":
					$_9 = "selected";
					break;
					case "10":
					$_10 = "selected";
					break;
					case "11":
					$_11 = "selected";
					break;
					case "12":
					$_12 = "selected";
					break;
					case "13":
					$_13 = "selected";
					break;
					case "14":
					$_14 = "selected";
					break;
					case "15":
					$_15 = "selected";
					break;
					case "16":
					$_16 = "selected";
					break;
					case "17":
					$_17 = "selected";
					break;
					case "18":
					$_18 = "selected";
					break;
					case "19":
					$_19 = "selected";
					break;
					case "20":
					$_20 = "selected";
					break;
					case "21":
					$_21 = "selected";
					break;
					case "22":
					$_22 = "selected";
					break;
					case "23":
					$_23 = "selected";
					break;
					case "24":
					$_24 = "selected";
					break;
					case "25":
					$_25 = "selected";
					break;
					case "26":
					$_26 = "selected";
					break;
					case "27":
					$_27 = "selected";
					break;
					case "28":
					$_28 = "selected";
					break;
					case "29":
					$_29 = "selected";
					break;
					case "30":
					$_30 = "selected";
					break;
					case "31":
					$_31 = "selected";
					break;
					case "32":
					$_32 = "selected";
					break;
					case "33":
					$_33 = "selected";
					break;
					case "34":
					$_34 = "selected";
					break;
					case "35":
					$_35 = "selected";
					break;
					case "36":
					$_36 = "selected";
					break;
					case "37":
					$_37 = "selected";
					break;
					case "38":
					$_38 = "selected";
					break;
					case "39":
					$_39 = "selected";
					break;
					case "40":
					$_40 = "selected";
					break;
					case "41":
					$_41 = "selected";
					break;
					case "42":
					$_42 = "selected";
					break;
					case "43":
					$_43 = "selected";
					break;
					case "44":
					$_44 = "selected";
					break;
					case "45":
					$_45 = "selected";
					break;
					case "46":
					$_46 = "selected";
					break;
					case "47":
					$_47 = "selected";
					break;
					case "48":
					$_48 = "selected";
					break;
					case "49":
					$_49 = "selected";
					break;
					case "50":
					$_50 = "selected";
					break;
					case "51":
					$_51 = "selected";
					break;
					case "52":
					$_52 = "selected";
					break;
					case "53":
					$_53 = "selected";
					break;
					case "54":
					$_54 = "selected";
					break;
					case "55":
					$_55 = "selected";
					break;
					case "56":
					$_56 = "selected";
					break;
					case "57":
					$_57 = "selected";
					break;
					case "58":
					$_58 = "selected";
					break;
					case "59":
					$_59 = "selected";
					break;
					case "60":
					$_60 = "selected";
					break;
					case "61":
					$_61 = "selected";
					break;
					case "62":
					$_62 = "selected";
					break;
					case "63":
					$_63 = "selected";
					break;
					case "64":
					$_64 = "selected";
					break;
					case "65":
					$_65 = "selected";
					break;
					case "66":
					$_66 = "selected";
					break;
					case "67":
					$_67 = "selected";
					break;
					case "68":
					$_68 = "selected";
					break;
					case "69":
					$_69 = "selected";
					break;
					case "70":
					$_70 = "selected";
					break;
					case "71":
					$_71 = "selected";
					break;
					case "72":
					$_72 = "selected";
					break;
					case "73":
					$_73 = "selected";
					break;
					case "74":
					$_74 = "selected";
					break;
					case "75":
					$_75 = "selected";
					break;
					case "76":
					$_76 = "selected";
					break;
					case "77":
					$_77 = "selected";
					break;
					case "78":
					$_78 = "selected";
					break;
					case "79":
					$_79 = "selected";
					break;
					case "80":
					$_80 = "selected";
					break;
					case "81":
					$_81 = "selected";
					break;
					case "82":
					$_82 = "selected";
					break;
					case "83":
					$_83 = "selected";
					break;
					case "84":
					$_84 = "selected";
					break;
					case "85":
					$_85 = "selected";
					break;
					case "86":
					$_86 = "selected";
					break;
					case "87":
					$_87 = "selected";
					break;
					case "88":
					$_88 = "selected";
					break;
					case "89":
					$_89 = "selected";
					break;
					case "90":
					$_90 = "selected";
					break;
					case "91":
					$_91 = "selected";
					break;
					case "92":
					$_92 = "selected";
					break;
					case "93":
					$_93 = "selected";
					break;
					case "94":
					$_94 = "selected";
					break;
					case "95":
					$_95 = "selected";
					break;
					case "96":
					$_96 = "selected";
					break;
					case "97":
					$_97 = "selected";
					break;
					case "98":
					$_98 = "selected";
					break;
					case "99":
					$_99 = "selected";
					break;
					case "100":
					$_100 = "selected";
					break;
					case "101":
					$_101 = "selected";
					break;
					case "102":
					$_102 = "selected";
					break;
					case "103":
					$_103 = "selected";
					break;
					case "104":
					$_104 = "selected";
					break;
					case "105":
					$_105 = "selected";
					break;
					case "106":
					$_106 = "selected";
					break;
					case "107":
					$_107 = "selected";
					break;
					case "108":
					$_108 = "selected";
					break;
					case "109":
					$_109 = "selected";
					break;
					case "110":
					$_110 = "selected";
					break;
					case "111":
					$_111 = "selected";
					break;
					case "112":
					$_112 = "selected";
					break;
					case "113":
					$_113 = "selected";
					break;
					case "114":
					$_114 = "selected";
					break;
					case "115":
					$_115 = "selected";
					break;
					case "116":
					$_116 = "selected";
					break;
					case "117":
					$_117 = "selected";
					break;
					case "118":
					$_118 = "selected";
					break;
					case "119":
					$_119 = "selected";
					break;
					case "120":
					$_120 = "selected";
					break;
					case "121":
					$_121 = "selected";
					break;
					case "122":
					$_122 = "selected";
					break;
					case "123":
					$_123 = "selected";
					break;
					case "124":
					$_124 = "selected";
					break;
					case "125":
					$_125 = "selected";
					break;
					case "126":
					$_126 = "selected";
					break;
					case "127":
					$_127 = "selected";
					break;
					case "128":
					$_128 = "selected";
					break;
					case "129":
					$_129 = "selected";
					break;
					case "130":
					$_130 = "selected";
					break;
					case "131":
					$_131 = "selected";
					break;
					case "132":
					$_132 = "selected";
					break;
					case "133":
					$_133 = "selected";
					break;
					case "134":
					$_134 = "selected";
					break;
					case "135":
					$_135 = "selected";
					break;
					case "136":
					$_136 = "selected";
					break;
					case "137":
					$_137 = "selected";
					break;
					case "138":
					$_138 = "selected";
					break;
					case "139":
					$_139 = "selected";
					break;
					case "140":
					$_140 = "selected";
					break;
					case "141":
					$_141 = "selected";
					break;
					case "142":
					$_142 = "selected";
					break;
					case "143":
					$_143 = "selected";
					break;
					case "144":
					$_144 = "selected";
					break;
					case "145":
					$_145 = "selected";
					break;
					case "146":
					$_146 = "selected";
					break;
					case "147":
					$_147 = "selected";
					break;
					case "148":
					$_148 = "selected";
					break;
					case "149":
					$_149 = "selected";
					break;
					case "150":
					$_150 = "selected";
					break;
					case "151":
					$_151 = "selected";
					break;
					case "152":
					$_152 = "selected";
					break;
					case "153":
					$_153 = "selected";
					break;
					case "154":
					$_154 = "selected";
					break;
					case "155":
					$_155 = "selected";
					break;
					case "156":
					$_156 = "selected";
					break;
					case "157":
					$_157 = "selected";
					break;
					case "158":
					$_158 = "selected";
					break;
					case "159":
					$_159 = "selected";
					break;
					case "160":
					$_160 = "selected";
					break;
					case "161":
					$_161 = "selected";
					break;
					case "162":
					$_162 = "selected";
					break;
					case "163":
					$_163 = "selected";
					break;
					case "164":
					$_164 = "selected";
					break;
					case "165":
					$_165 = "selected";
					break;
					case "166":
					$_166 = "selected";
					break;
					case "167":
					$_167 = "selected";
					break;
					case "168":
					$_168 = "selected";
					break;
					case "169":
					$_169 = "selected";
					break;
					case "170":
					$_170 = "selected";
					break;
					case "171":
					$_171 = "selected";
					break;
					case "172":
					$_172 = "selected";
					break;
					case "173":
					$_173 = "selected";
					break;
					case "174":
					$_174 = "selected";
					break;
					case "175":
					$_175 = "selected";
					break;
					case "176":
					$_176 = "selected";
					break;
					case "177":
					$_177 = "selected";
					break;
					case "178":
					$_178 = "selected";
					break;
					case "179":
					$_179 = "selected";
					break;
					case "180":
					$_180 = "selected";
					break;
					case "181":
					$_181 = "selected";
					break;
					case "182":
					$_182 = "selected";
					break;
					case "183":
					$_183 = "selected";
					break;
					case "184":
					$_184 = "selected";
					break;
					case "185":
					$_185 = "selected";
					break;
					case "186":
					$_186 = "selected";
					break;
					case "187":
					$_187 = "selected";
					break;
					case "188":
					$_188 = "selected";
					break;
					case "189":
					$_189 = "selected";
					break;
					case "190":
					$_190 = "selected";
					break;
					case "191":
					$_191 = "selected";
					break;
					case "192":
					$_192 = "selected";
					break;
					case "193":
					$_193 = "selected";
					break;
					case "194":
					$_194 = "selected";
					break;
					case "195":
					$_195 = "selected";
					break;
					case "196":
					$_196 = "selected";
					break;
					case "197":
					$_197 = "selected";
					break;
					case "198":
					$_198 = "selected";
					break;
					case "199":
					$_199 = "selected";
					break;
					case "200":
					$_200 = "selected";
					break;
					case "201":
					$_201 = "selected";
					break;
					case "202":
					$_202 = "selected";
					break;
					case "203":
					$_203 = "selected";
					break;
					case "204":
					$_204 = "selected";
					break;
					case "205":
					$_205 = "selected";
					break;
					case "206":
					$_206 = "selected";
					break;
					case "207":
					$_207 = "selected";
					break;
					case "208":
					$_208 = "selected";
					break;
					case "209":
					$_209 = "selected";
					break;
				}
				echo "
					<tr>
						<td class='list_central_row_title'>Current Flag:</td>
						<td class='list_central_row_info'>" . flag_to_image($flag) . "</td>
					</tr>
					<tr>
						<td class='list_central_row_title'>Flag:</td>
						<td class='list_central_row_info'>
						<select name='newflag'>
						<option value='0' " . $_0 . ">None</option>
						<option value='1' " . $_1 . ">Afghanistan</option>
						<option value='2' " . $_2 . ">Albania</option>
						<option value='3' " . $_3 . ">Algeria</option>
						<option value='4' " . $_4 . ">Andorra</option>
						<option value='5' " . $_5 . ">Angola</option>
						<option value='6' " . $_6 . ">Anguilla</option>
						<option value='7' " . $_7 . ">Antigua</option>
						<option value='8' " . $_8 . ">Argentina</option>
						<option value='9' " . $_9 . ">Armenia</option>
						<option value='10' " . $_10 . ">Australia</option>
						<option value='11' " . $_11 . ">Austria</option>
						<option value='12' " . $_12 . ">Azerbaijan</option>
						<option value='13' " . $_13 . ">Bahamas</option>
						<option value='14' " . $_14 . ">Bahrain</option>
						<option value='15' " . $_15 . ">Bangladesh</option>
						<option value='16' " . $_16 . ">Barbados</option>
						<option value='17' " . $_17 . ">Belarus</option>
						<option value='18' " . $_18 . ">Belgium</option>
						<option value='19' " . $_19 . ">Belize</option>
						<option value='20' " . $_20 . ">Benin</option>
						<option value='21' " . $_21 . ">Bhutan</option>
						<option value='22' " . $_22 . ">Bolivia</option>
						<option value='23' " . $_23 . ">Botswana</option>
						<option value='24' " . $_24 . ">Brunei</option>
						<option value='25' " . $_25 . ">Bulgaria</option>
						<option value='26' " . $_26 . ">Burkina</option>
						<option value='27' " . $_27 . ">Burma</option>
						<option value='28' " . $_28 . ">Burundi</option>
						<option value='29' " . $_29 . ">Cambodia</option>
						<option value='30' " . $_30 . ">Cameroon</option>
						<option value='31' " . $_31 . ">Canada</option>
						<option value='32' " . $_32 . ">Cape Verde</option>
						<option value='33' " . $_33 . ">Central African Republic</option>
						<option value='34' " . $_34 . ">Chad</option>
						<option value='35' " . $_35 . ">Chile</option>
						<option value='36' " . $_36 . ">China</option>
						<option value='37' " . $_37 . ">Colombia</option>
						<option value='38' " . $_38 . ">Comoros</option>
						<option value='39' " . $_39 . ">Congo</option>
						<option value='40' " . $_40 . ">Costa Rica</option>
						<option value='41' " . $_41 . ">Cuba</option>
						<option value='42' " . $_42 . ">Cyprus</option>
						<option value='43' " . $_43 . ">Czech</option>
						<option value='44' " . $_44 . ">Denmark</option>
						<option value='45' " . $_45 . ">Djibouti</option>
						<option value='46' " . $_46 . ">Dominican</option>
						<option value='47' " . $_47 . ">Ecuador</option>
						<option value='48' " . $_48 . ">Egypt</option>
						<option value='49' " . $_49 . ">El Salvador</option>
						<option value='50' " . $_50 . ">Equatorial Guinea</option>
						<option value='51' " . $_51 . ">Eritrea</option>
						<option value='52' " . $_52 . ">Ethiopia</option>
						<option value='53' " . $_53 . ">European Union</option>
						<option value='54' " . $_54 . ">Fiji</option>
						<option value='55' " . $_55 . ">Finland</option>
						<option value='56' " . $_56 . ">France</option>
						<option value='57' " . $_57 . ">Gabon</option>
						<option value='58' " . $_58 . ">Gambia</option>
						<option value='59' " . $_59 . ">Georgia</option>
						<option value='60' " . $_60 . ">Germany</option>
						<option value='61' " . $_61 . ">Gibraltar</option>
						<option value='62' " . $_62 . ">Greece</option>
						<option value='63' " . $_63 . ">Greenland</option>
						<option value='64' " . $_64 . ">Grenada</option>
						<option value='65' " . $_65 . ">Guam</option>
						<option value='66' " . $_66 . ">Guatemala</option>
						<option value='67' " . $_67 . ">Guinea-Bissau</option>
						<option value='68' " . $_68 . ">Guinea</option>
						<option value='69' " . $_69 . ">Guyana</option>
						<option value='70' " . $_70 . ">Haiti</option>
						<option value='72' " . $_72 . ">Honduras</option>
						<option value='73' " . $_73 . ">Hong Kong</option>
						<option value='74' " . $_74 . ">Hungary</option>
						<option value='75' " . $_75 . ">Iceland</option>
						<option value='76' " . $_76 . ">India</option>
						<option value='77' " . $_77 . ">Indonesia</option>
						<option value='78' " . $_78 . ">Iran</option>
						<option value='79' " . $_79 . ">Iraq</option>
						<option value='80' " . $_80 . ">Ireland</option>
						<option value='81' " . $_81 . ">Isle of Man</option>
						<option value='82' " . $_82 . ">Israel</option>
						<option value='83' " . $_83 . ">Italy</option>
						<option value='84' " . $_84 . ">Jamaica</option>
						<option value='85' " . $_85 . ">Janmayen</option>
						<option value='86' " . $_86 . ">Japan</option>
						<option value='87' " . $_87 . ">Jarvis</option>
						<option value='88' " . $_88 . ">Jersey</option>
						<option value='89' " . $_89 . ">Jordan</option>
						<option value='90' " . $_90 . ">Kazakhstan</option>
						<option value='91' " . $_91 . ">Kenya</option>
						<option value='93' " . $_93 . ">Kuwait</option>
						<option value='94' " . $_94 . ">Kyrgyzstan</option>
						<option value='95' " . $_95 . ">Laos</option>
						<option value='96' " . $_96 . ">Lativa</option>
						<option value='97' " . $_97 . ">Lebanon</option>
						<option value='98' " . $_98 . ">Lesotho</option>
						<option value='99' " . $_99 . ">Liberia</option>
						<option value='100' " . $_100 . ">Libya</option>
						<option value='101' " . $_101 . ">Liechtenstein</option>
						<option value='102' " . $_102 . ">Lithuania</option>
						<option value='103' " . $_103 . ">Luxembourg</option>
						<option value='104' " . $_104 . ">Macau</option>
						<option value='105' " . $_105 . ">Macedonia</option>
						<option value='106' " . $_106 . ">Madagascar</option>
						<option value='107' " . $_107 . ">Malawi</option>
						<option value='108' " . $_108 . ">Malaysia</option>
						<option value='109' " . $_109 . ">Maldives</option>
						<option value='110' " . $_110 . ">Mali</option>
						<option value='111' " . $_111 . ">Malta</option>
						<option value='112' " . $_112 . ">Marshall</option>
						<option value='113' " . $_113 . ">Mauritania</option>
						<option value='114' " . $_114 . ">Mauritius</option>
						<option value='115' " . $_115 . ">Mayotte</option>
						<option value='116' " . $_116 . ">Mexico</option>
						<option value='117' " . $_117 . ">Micronesia</option>
						<option value='118' " . $_118 . ">Moldova</option>
						<option value='119' " . $_119 . ">Monaco</option>
						<option value='120' " . $_120 . ">Mongolia</option>
						<option value='121' " . $_121 . ">Montenegro</option>
						<option value='122' " . $_122 . ">Montserrat</option>
						<option value='123' " . $_123 . ">Morocco</option>
						<option value='124' " . $_124 . ">Mozambique</option>
						<option value='125' " . $_125 . ">Namibia</option>
						<option value='126' " . $_126 . ">Nauru</option>
						<option value='127' " . $_127 . ">Nepal</option>
						<option value='128' " . $_128 . ">Netherlands</option>
						<option value='129' " . $_129 . ">New Caledonia</option>
						<option value='130' " . $_130 . ">New Zealand</option>
						<option value='131' " . $_131 . ">Nicaragua</option>
						<option value='132' " . $_132 . ">Niger</option>
						<option value='133' " . $_133 . ">Nigeria</option>
						<option value='134' " . $_134 . ">Niue</option>
						<option value='135' " . $_135 . ">North Korea</option>
						<option value='136' " . $_136 . ">Norway</option>
						<option value='137' " . $_137 . ">Oman</option>
						<option value='138' " . $_138 . ">Pakistan</option>
						<option value='139' " . $_139 . ">Palau</option>
						<option value='140' " . $_140 . ">Panama</option>
						<option value='141' " . $_141 . ">Papua</option>
						<option value='142' " . $_142 . ">Paraguay</option>
						<option value='143' " . $_143 . ">Peru</option>
						<option value='144' " . $_144 . ">Philippines</option>
						<option value='145' " . $_145 . ">Poland</option>
						<option value='146' " . $_146 . ">Portugal</option>
						<option value='147' " . $_147 . ">Puertorico</option>
						<option value='148' " . $_148 . ">Qatar</option>
						<option value='149' " . $_149 . ">Romania</option>
						<option value='150' " . $_150 . ">Rwanda</option>
						<option value='92' " . $_92 . ">South Korea</option>
						<option value='151' " . $_151 . ">St. Pierre</option>
						<option value='152' " . $_152 . ">St. Vincent</option>
						<option value='153' " . $_153 . ">St. Bartholomew</option>
						<option value='154' " . $_154 . ">St. Helena</option>
						<option value='155' " . $_155 . ">St. Kitts</option>
						<option value='156' " . $_156 . ">St. Lucia</option>
						<option value='157' " . $_157 . ">St. Martin</option>
						<option value='158' " . $_158 . ">Samoa</option>
						<option value='159' " . $_159 . ">San Marino</option>
						<option value='160' " . $_160 . ">Sao Tome</option>
						<option value='161' " . $_161 . ">Saudi Arabia</option>
						<option value='162' " . $_162 . ">Senegal</option>
						<option value='163' " . $_163 . ">Serbia</option>
						<option value='164' " . $_164 . ">Seychelles</option>
						<option value='165' " . $_165 . ">Sierra Leone</option>
						<option value='166' " . $_166 . ">Singapore</option>
						<option value='167' " . $_167 . ">Slovakia</option>
						<option value='168' " . $_168 . ">Slovenia</option>
						<option value='169' " . $_169 . ">Solomon</option>
						<option value='170' " . $_170 . ">Somalia</option>
						<option value='171' " . $_171 . ">South Africa</option>
						<option value='172' " . $_172 . ">South Georgia</option>
						<option value='173' " . $_173 . ">Spain</option>
						<option value='174' " . $_174 . ">Sri Lanka</option>
						<option value='175' " . $_175 . ">Sudan</option>
						<option value='176' " . $_176 . ">Suriname</option>
						<option value='177' " . $_177 . ">Svalbard</option>
						<option value='178' " . $_178 . ">Swaziland</option>
						<option value='179' " . $_179 . ">Sweden</option>
						<option value='180' " . $_180 . ">Switzerland</option>
						<option value='181' " . $_181 . ">Syria</option>
						<option value='182' " . $_182 . ">Taiwan</option>
						<option value='183' " . $_183 . ">Tajikistan</option>
						<option value='184' " . $_184 . ">Tazania</option>
						<option value='185' " . $_185 . ">Thailand</option>
						<option value='186' " . $_186 . ">Timor</option>
						<option value='187' " . $_187 . ">Togo</option>
						<option value='188' " . $_188 . ">Tokelau</option>
						<option value='189' " . $_189 . ">Tonga</option>
						<option value='190' " . $_190 . ">Trinidad</option>
						<option value='191' " . $_191 . ">Tunisia</option>
						<option value='192' " . $_192 . ">Turkey</option>
						<option value='193' " . $_193 . ">Turkmenistan</option>
						<option value='194' " . $_194 . ">Turks</option>
						<option value='195' " . $_195 . ">Tuvalu</option>
						<option value='196' " . $_196 . ">Uganda</option>
						<option value='197' " . $_197 . ">Ukraine</option>
						<option value='198' " . $_198 . ">United Arab Emirates</option>
						<option value='199' " . $_199 . ">United Kingdom</option>
						<option value='200' " . $_200 . ">United States</option>
						<option value='201' " . $_201 . ">Uruguay</option>
						<option value='202' " . $_202 . ">Uzbekistan</option>
						<option value='71' " . $_71 . ">Vatican</option>
						<option value='203' " . $_203 . ">Vanuatu</option>
						<option value='204' " . $_204 . ">Vietnam</option>
						<option value='205' " . $_205 . ">Virgin Islands</option>
						<option value='206' " . $_206 . ">Wallis</option>
						<option value='207' " . $_207 . ">Yemen</option>
						<option value='208' " . $_208 . ">Zambia</option>
						<option value='209' " . $_209 . ">Zimbabwe</option>
						</select>
						</td>
					</tr>"; ?>
				<?php
				switch ($currency)
				{
					case "1":
					$_1a = "selected";
					break;
					case "2":
					$_2a = "selected";
					break;
					case "3":
					$_3a = "selected";
					break;
					case "4":
					$_4a = "selected";
					break;
					case "5":
					$_5a = "selected";
					break;
					case "6":
					$_6a = "selected";
					break;
					case "7":
					$_7a = "selected";
					break;
					case "8":
					$_8a = "selected";
					break;
					case "9":
					$_9a = "selected";
					break;
					case "10":
					$_10a = "selected";
					break;
					case "11":
					$_11a = "selected";
					break;
					case "12":
					$_12a = "selected";
					break;
					case "13":
					$_13a = "selected";
					break;
					case "14":
					$_14a = "selected";
					break;
					case "15":
					$_15a = "selected";
					break;
					case "16":
					$_16a = "selected";
					break;
					case "17":
					$_17a = "selected";
					break;
					case "18":
					$_18a = "selected";
					break;
					case "19":
					$_19a = "selected";
					break;
					case "20":
					$_20a = "selected";
					break;
					case "21":
					$_21a = "selected";
					break;
					case "22":
					$_22a = "selected";
					break;
					case "23":
					$_23a = "selected";
					break;
					case "24":
					$_24a = "selected";
					break;
					case "25":
					$_25a = "selected";
					break;
					case "26":
					$_26a = "selected";
					break;
					case "27":
					$_27a = "selected";
					break;
					case "28":
					$_28a = "selected";
					break;
					case "29":
					$_29a = "selected";
					break;
					case "30":
					$_30a = "selected";
					break;
					case "31":
					$_31a = "selected";
					break;
					case "32":
					$_32a = "selected";
					break;
					case "33":
					$_33a = "selected";
					break;
					case "34":
					$_34a = "selected";
					break;
					case "35":
					$_35a = "selected";
					break;
					case "36":
					$_36a = "selected";
					break;
					case "37":
					$_37a = "selected";
					break;
					case "38":
					$_38a = "selected";
					break;
					case "39":
					$_39a = "selected";
					break;
					case "40":
					$_40a = "selected";
					break;
					case "41":
					$_41a = "selected";
					break;
					case "42":
					$_42a = "selected";
					break;
					case "43":
					$_43a = "selected";
					break;
					case "44":
					$_44a = "selected";
					break;
					case "45":
					$_45a = "selected";
					break;
					case "46":
					$_46a = "selected";
					break;
					case "47":
					$_47a = "selected";
					break;
					case "48":
					$_48a = "selected";
					break;
					case "49":
					$_49a = "selected";
					break;
					case "50":
					$_50a = "selected";
					break;
					case "51":
					$_51a = "selected";
					break;
					case "52":
					$_52a = "selected";
					break;
					case "53":
					$_53a = "selected";
					break;
					case "54":
					$_54a = "selected";
					break;
					case "55":
					$_55a = "selected";
					break;
					case "56":
					$_56a = "selected";
					break;
					case "57":
					$_57a = "selected";
					break;
					case "58":
					$_58a = "selected";
					break;
					case "59":
					$_59a = "selected";
					break;
					case "60":
					$_60a = "selected";
					break;
					case "61":
					$_61a = "selected";
					break;
					case "62":
					$_62a = "selected";
					break;
					case "63":
					$_63a = "selected";
					break;
					case "64":
					$_64a = "selected";
					break;
					case "65":
					$_65a = "selected";
					break;
					case "66":
					$_66a = "selected";
					break;
					case "67":
					$_67a = "selected";
					break;
					case "68":
					$_68a = "selected";
					break;
					case "69":
					$_69a = "selected";
					break;
					case "70":
					$_70a = "selected";
					break;
					case "71":
					$_71a = "selected";
					break;
					case "72":
					$_72a = "selected";
					break;
					case "73":
					$_73a = "selected";
					break;
					case "74":
					$_74a = "selected";
					break;
					case "75":
					$_75a = "selected";
					break;
					case "76":
					$_76a = "selected";
					break;
					case "77":
					$_77a = "selected";
					break;
					case "78":
					$_78a = "selected";
					break;
					case "79":
					$_79a = "selected";
					break;
					case "80":
					$_80a = "selected";
					break;
					case "81":
					$_81a = "selected";
					break;
					case "82":
					$_82a = "selected";
					break;
					case "83":
					$_83a = "selected";
					break;
					case "84":
					$_84a = "selected";
					break;
					case "85":
					$_85a = "selected";
					break;
				}
				echo "
					<tr>
						<td class='list_central_row_title'>Currency:</td>
						<td class='list_central_row_info'>
						<select name='currency'>
						<option value='1' " . $_1a . ">Afghani</option>
						<option value='2' " . $_2a . ">Ariary</option>
						<option value='3' " . $_3a . ">Baht</option>
						<option value='4' " . $_4a . ">Balboa</option>
						<option value='5' " . $_5a . ">Birr</option>
						<option value='6' " . $_6a . ">Bolivar</option>
						<option value='7' " . $_7a . ">Cedi</option>
						<option value='8' " . $_8a . ">Colon</option>
						<option value='9' " . $_9a . ">Cordoba</option>
						<option value='10' " . $_10a . ">Dalasi</option>
						<option value='11' " . $_11a . ">Denar</option>
						<option value='12' " . $_12a . ">Dinar</option>
						<option value='13' " . $_13a . ">Dirham</option>
						<option value='14' " . $_14a . ">Dobra</option>
						<option value='15' " . $_15a . ">Dollar</option>
						<option value='16' " . $_16a . ">Dong</option>
						<option value='17' " . $_17a . ">Dram</option>
						<option value='18' " . $_18a . ">Escudo</option>
						<option value='19' " . $_19a . ">Euro</option>
						<option value='20' " . $_20a . ">Forint</option>
						<option value='21' " . $_21a . ">Florin</option>
						<option value='22' " . $_22a . ">Franc</option>
						<option value='23' " . $_23a . ">Gourde</option>
						<option value='24' " . $_24a . ">Guarani</option>
						<option value='25' " . $_25a . ">Guilder</option>
						<option value='26' " . $_26a . ">Hryvnya</option>
						<option value='27' " . $_27a . ">Kina</option>
						<option value='28' " . $_28a . ">Kip</option>
						<option value='29' " . $_29a . ">Koruna</option>
						<option value='30' " . $_30a . ">Krona</option>
						<option value='31' " . $_31a . ">Krone</option>
						<option value='32' " . $_32a . ">Kroon</option>
						<option value='33' " . $_33a . ">Kuna</option>
						<option value='34' " . $_34a . ">Kwacha</option>
						<option value='35' " . $_35a . ">Kwanza</option>
						<option value='36' " . $_36a . ">Kyat</option>
						<option value='37' " . $_37a . ">Lari</option>
						<option value='38' " . $_38a . ">Lats</option>
						<option value='39' " . $_39a . ">Lek</option>
						<option value='40' " . $_40a . ">Lempira</option>
						<option value='41' " . $_41a . ">Leone</option>
						<option value='42' " . $_42a . ">Leu</option>
						<option value='43' " . $_43a . ">Lev</option>
						<option value='44' " . $_44a . ">Lilangeni</option>
						<option value='45' " . $_45a . ">Lira</option>
						<option value='46' " . $_46a . ">Litas</option>
						<option value='47' " . $_47a . ">Loti</option>
						<option value='48' " . $_48a . ">Manat</option>
						<option value='49' " . $_49a . ">Marka</option>
						<option value='50' " . $_50a . ">Metical</option>
						<option value='51' " . $_51a . ">Naira</option>
						<option value='52' " . $_52a . ">Nakfa</option>
						<option value='53' " . $_53a . ">Ngultrum</option>
						<option value='54' " . $_54a . ">Nuevo Sol</option>
						<option value='55' " . $_55a . ">Oro</option>
						<option value='56' " . $_56a . ">Ouguiya</option>
						<option value='57' " . $_57a . ">Pa'anga</option>
						<option value='58' " . $_58a . ">Pataca</option>
						<option value='59' " . $_59a . ">Peso</option>
						<option value='60' " . $_60a . ">Pound</option>
						<option value='61' " . $_61a . ">Pula</option>
						<option value='62' " . $_62a . ">Qyetzal</option>
						<option value='63' " . $_63a . ">Rand</option>
						<option value='64' " . $_64a . ">Real</option>
						<option value='65' " . $_65a . ">Rial Omani</option>
						<option value='66' " . $_66a . ">Riel</option>
						<option value='67' " . $_67a . ">Ringgit</option>
						<option value='68' " . $_68a . ">Riyal</option>
						<option value='69' " . $_69a . ">Ruble</option>
						<option value='70' " . $_70a . ">Rufiyaa</option>
						<option value='71' " . $_71a . ">Rupee</option>
						<option value='72' " . $_72a . ">Rupiah</option>
						<option value='73' " . $_73a . ">Sheqel</option>
						<option value='74' " . $_74a . ">Shilling</option>
						<option value='75' " . $_75a . ">Som</option>
						<option value='76' " . $_76a . ">Somoni</option>
						<option value='77' " . $_77a . ">Taka</option>
						<option value='78' " . $_78a . ">Tala</option>
						<option value='79' " . $_79a . ">Tenge</option>
						<option value='80' " . $_80a . ">Touman</option>
						<option value='81' " . $_81a . ">Tugrug</option>
						<option value='82' " . $_82a . ">Vatu</option>
						<option value='83' " . $_83a . ">Won</option>
						<option value='84' " . $_84a . ">Yen</option>
						<option value='85' " . $_85a . ">Yuan</option>
						</select>
						</td>
					</tr>"; ?>
				<?php
				switch($language)
				{
					case "1":
					$_1b = "selected";
					break;
					case "2":
					$_2b = "selected";
					break;
					case "3":
					$_3b = "selected";
					break;
					case "4":
					$_4b = "selected";
					break;
					case "5":
					$_5b = "selected";
					break;
					case "6":
					$_6b = "selected";
					break;
					case "7":
					$_7b = "selected";
					break;
					case "8":
					$_8b = "selected";
					break;
					case "9":
					$_9b = "selected";
					break;
					case "10":
					$_10b = "selected";
					break;
					case "11":
					$_11b = "selected";
					break;
					case "12":
					$_12b = "selected";
					break;
					case "13":
					$_13b = "selected";
					break;
					case "14":
					$_14b = "selected";
					break;
					case "15":
					$_15b = "selected";
					break;
					case "16":
					$_16b = "selected";
					break;
					case "17":
					$_17b = "selected";
					break;
					case "18":
					$_18b = "selected";
					break;
					case "19":
					$_19b = "selected";
					break;
					case "20":
					$_20b = "selected";
					break;
					case "21":
					$_21b = "selected";
					break;
					case "22":
					$_22b = "selected";
					break;
					case "23":
					$_23b = "selected";
					break;
					case "24":
					$_24b = "selected";
					break;
					case "25":
					$_25b = "selected";
					break;
					case "26":
					$_26b = "selected";
					break;
					case "27":
					$_27b = "selected";
					break;
					case "28":
					$_28b = "selected";
					break;
					case "29":
					$_29b = "selected";
					break;
					case "30":
					$_30b = "selected";
					break;
					case "31":
					$_31b = "selected";
					break;
					case "32":
					$_32b = "selected";
					break;
					case "33":
					$_33b = "selected";
					break;
					case "34":
					$_34b = "selected";
					break;
					case "35":
					$_35b = "selected";
					break;
					case "36":
					$_36b = "selected";
					break;
					case "37":
					$_37b = "selected";
					break;
					case "38":
					$_38b = "selected";
					break;
					case "39":
					$_39b = "selected";
					break;
					case "40":
					$_40b = "selected";
					break;
					case "41":
					$_41b = "selected";
					break;
					case "42":
					$_42b = "selected";
					break;
					case "43":
					$_43b = "selected";
					break;
					case "44":
					$_44b = "selected";
					break;
					case "45":
					$_45b = "selected";
					break;
					case "46":
					$_46b = "selected";
					break;
					case "47":
					$_47b = "selected";
					break;
					case "48":
					$_48b = "selected";
					break;
					case "49":
					$_49b = "selected";
					break;
					case "50":
					$_50b = "selected";
					break;
					case "51":
					$_51b = "selected";
					break;
					case "52":
					$_52b = "selected";
					break;
					case "53":
					$_53b = "selected";
					break;
					case "54":
					$_54b = "selected";
					break;
					case "55":
					$_55b = "selected";
					break;
					case "56":
					$_56b = "selected";
					break;
					case "57":
					$_57b = "selected";
					break;
					case "58":
					$_58b = "selected";
					break;
					case "59":
					$_59b = "selected";
					break;
					case "60":
					$_60b = "selected";
					break;
					case "61":
					$_61b = "selected";
					break;
					case "62":
					$_62b = "selected";
					break;
				}
				echo "
					<tr>
						<td class='list_central_row_title'>Language:</td>
						<td class='list_central_row_info'>
						<select name='language'>
						<option value='1' " . $_1b . ">Albanian</option>
						<option value='2' " . $_2b . ">Arabic</option>
						<option value='3' " . $_3b . ">Armenian</option>
						<option value='4' " . $_4b . ">Azerbaijani</option>
						<option value='5' " . $_5b . ">Bantu</option>
						<option value='6' " . $_6b . ">Bengali</option>
						<option value='7' " . $_7b . ">Berber</option>
						<option value='8' " . $_8b . ">Bosnian</option>
						<option value='9' " . $_9b . ">Burmese</option>
						<option value='10' " . $_10b . ">Chinese</option>
						<option value='11' " . $_11b . ">Creole</option>
						<option value='12' " . $_12b . ">Croatian</option>
						<option value='13' " . $_13b . ">Czech</option>
						<option value='14' " . $_14b . ">Dutch</option>
						<option value='15' " . $_15b . ">English</option>
						<option value='16' " . $_16b . ">Farsi</option>
						<option value='17' " . $_17b . ">Finnish</option>
						<option value='18' " . $_18b . ">French</option>
						<option value='19' " . $_19b . ">German</option>
						<option value='20' " . $_20b . ">Greek</option>
						<option value='21' " . $_21b . ">Gujarati</option>
						<option value='22' " . $_22b . ">Hebrew</option>
						<option value='23' " . $_23b . ">Hindu</option>
						<option value='24' " . $_24b . ">Hungarian</option>
						<option value='25' " . $_25b . ">Indoneasian</option>
						<option value='26' " . $_26b . ">Irish</option>
						<option value='27' " . $_27b . ">Italian</option>
						<option value='28' " . $_28b . ">Japanese</option>
						<option value='29' " . $_29b . ">Javanese</option>
						<option value='30' " . $_30b . ">Korean</option>
						<option value='31' " . $_31b . ">Kurdish</option>
						<option value='32' " . $_32b . ">Macedonian</option>
						<option value='33' " . $_33b . ">Malay</option>
						<option value='34' " . $_34b . ">Marathi</option>
						<option value='35' " . $_35b . ">Norwegian</option>
						<option value='36' " . $_36b . ">Pashto</option>
						<option value='37' " . $_37b . ">Polish</option>
						<option value='38' " . $_38b . ">Portuguese</option>
						<option value='39' " . $_39b . ">Punjabi</option>
						<option value='40' " . $_40b . ">Quechua</option>
						<option value='41' " . $_41b . ">Romanian</option>
						<option value='42' " . $_42b . ">Russian</option>
						<option value='43' " . $_43b . ">Serbian</option>
						<option value='44' " . $_44b . ">Slovak</option>
						<option value='45' " . $_45b . ">Somali</option>
						<option value='46' " . $_46b . ">Spanish</option>
						<option value='47' " . $_47b . ">Sundanese</option>
						<option value='48' " . $_48b . ">Swahili</option>
						<option value='49' " . $_49b . ">Swazi</option>
						<option value='50' " . $_50b . ">Swedish</option>
						<option value='51' " . $_51b . ">Tagalog</option>
						<option value='52' " . $_52b . ">Taiwanese</option>
						<option value='53' " . $_53b . ">Tamil</option>
						<option value='54' " . $_54b . ">Telugu</option>
						<option value='55' " . $_55b . ">Thai</option>
						<option value='56' " . $_56b . ">Tibetan</option>
						<option value='57' " . $_57b . ">Tribal</option>
						<option value='58' " . $_58b . ">Turkish</option>
						<option value='59' " . $_59b . ">Ukrainian</option>
						<option value='60' " . $_60b . ">Uzbek</option>
						<option value='61' " . $_61b . ">Vietnamese</option>
						<option value='62' " . $_62b . ">Yoruba</option>
						</select>
						</td>
					</tr>"; ?>
				<?php
				switch ($ethnicity)
				{
					case "1":
					$_1c = "selected";
					break;
					case "2":
					$_2c = "selected";
					break;
					case "3":
					$_3c = "selected";
					break;
					case "4":
					$_4c = "selected";
					break;
					case "5":
					$_5c = "selected";
					break;
					case "6":
					$_6c = "selected";
					break;
					case "7":
					$_7c = "selected";
					break;
					case "8":
					$_8c = "selected";
					break;
					case "9":
					$_9c = "selected";
					break;
					case "10":
					$_10c = "selected";
					break;
					case "11":
					$_11c = "selected";
					break;
					case "12":
					$_12c = "selected";
					break;
					case "13":
					$_13c = "selected";
					break;
					case "14":
					$_14c = "selected";
					break;
					case "15":
					$_15c = "selected";
					break;
					case "16":
					$_16c = "selected";
					break;
					case "17":
					$_17c = "selected";
					break;
					case "18":
					$_18c = "selected";
					break;
					case "19":
					$_19c = "selected";
					break;
					case "20":
					$_20c = "selected";
					break;
					case "21":
					$_21c = "selected";
					break;
					case "22":
					$_22c = "selected";
					break;
					case "23":
					$_23c = "selected";
					break;
					case "24":
					$_24c = "selected";
					break;
					case "25":
					$_25c = "selected";
					break;
					case "26":
					$_26c = "selected";
					break;
					case "27":
					$_27c = "selected";
					break;
					case "28":
					$_28c = "selected";
					break;
					case "29":
					$_29c = "selected";
					break;
					case "30":
					$_30c = "selected";
					break;
					case "31":
					$_31c = "selected";
					break;
					case "32":
					$_32c = "selected";
					break;
					case "33":
					$_33c = "selected";
					break;
					case "34":
					$_34c = "selected";
					break;
					case "35":
					$_35c = "selected";
					break;
					case "36":
					$_36c = "selected";
					break;
					case "37":
					$_37c = "selected";
					break;
					case "38":
					$_38c = "selected";
					break;
					case "39":
					$_39c = "selected";
					break;
					case "40":
					$_40c = "selected";
					break;
					case "41":
					$_41c = "selected";
					break;
					case "42":
					$_42c = "selected";
					break;
					case "43":
					$_43c = "selected";
					break;
					case "44":
					$_44c = "selected";
					break;
					case "45":
					$_45c = "selected";
					break;
					case "46":
					$_46c = "selected";
					break;
					case "47":
					$_47c = "selected";
					break;
					case "48":
					$_48c = "selected";
					break;
					case "49":
					$_49c = "selected";
					break;
					case "50":
					$_50c = "selected";
					break;
					case "51":
					$_51c = "selected";
					break;
					case "52":
					$_52c = "selected";
					break;
					case "53":
					$_53c = "selected";
					break;
					case "54":
					$_54c = "selected";
					break;
					case "55":
					$_55c = "selected";
					break;
				}
				echo "
					<tr>
						<td class='list_central_row_title'>Ethnicity:</td>
						<td class='list_central_row_info'>
						<select name='ethnicity'>
						<option value='1' " . $_1c . ">Albanian</option>
						<option value='2' " . $_2c . ">American</option>
						<option value='3' " . $_3c . ">Amerindian</option>
						<option value='4' " . $_4c . ">Arab</option>
						<option value='5' " . $_5c . ">Armenian</option>
						<option value='6' " . $_6c . ">Australian</option>
						<option value='7' " . $_7c . ">Bavarian</option>
						<option value='8' " . $_8c . ">Berber</option>
						<option value='9' " . $_9c . ">Bosnian</option>
						<option value='10' " . $_10c . ">Brazilian</option>
						<option value='11' " . $_11c . ">British</option>
						<option value='12' " . $_12c . ">Bulgarian</option>
						<option value='13' " . $_13c . ">Burman</option>
						<option value='14' " . $_14c . ">Caucasian</option>
						<option value='15' " . $_15c . ">Celtic</option>
						<option value='16' " . $_16c . ">Chilean</option>
						<option value='17' " . $_17c . ">Chinese</option>
						<option value='18' " . $_18c . ">Creole</option>
						<option value='19' " . $_19c . ">Croatian</option>
						<option value='20' " . $_20c . ">Czech</option>
						<option value='21' " . $_21c . ">Dutch</option>
						<option value='22' " . $_22c . ">East African</option>
						<option value='23' " . $_23c . ">Egyptian</option>
						<option value='24' " . $_24c . ">Estonian</option>
						<option value='25' " . $_25c . ">Finnish</option>
						<option value='26' " . $_26c . ">French</option>
						<option value='27' " . $_27c . ">German</option>
						<option value='28' " . $_28c . ">Greek</option>
						<option value='29' " . $_29c . ">Indian</option>
						<option value='30' " . $_30c . ">Irish</option>
						<option value='31' " . $_31c . ">Israeli</option>
						<option value='32' " . $_32c . ">Italian</option>
						<option value='33' " . $_33c . ">Japanese</option>
						<option value='34' " . $_34c . ">Korean</option>
						<option value='35' " . $_35c . ">Mestizo</option>
						<option value='36' " . $_36c . ">Mexican</option>
						<option value='37' " . $_37c . ">Mixed</option>
						<option value='38' " . $_38c . ">North African</option>
						<option value='39' " . $_39c . ">Norwegian</option>
						<option value='40' " . $_40c . ">Pacific Islander</option>
						<option value='41' " . $_41c . ">Pashtun</option>
						<option value='42' " . $_42c . ">Persian</option>
						<option value='43' " . $_43c . ">Peruvian</option>
						<option value='44' " . $_44c . ">Polish</option>
						<option value='45' " . $_45c . ">Portuguese</option>
						<option value='46' " . $_46c . ">Russian</option>
						<option value='47' " . $_47c . ">Scandinavian</option>
						<option value='48' " . $_48c . ">Serbian</option>
						<option value='49' " . $_49c . ">Somalian</option>
						<option value='50' " . $_50c . ">South African</option>
						<option value='51' " . $_51c . ">Spanish</option>
						<option value='52' " . $_52c . ">Swiss</option>
						<option value='53' " . $_53c . ">Thai</option>
						<option value='54' " . $_54c . ">Turkish</option>
						<option value='55' " . $_55c . ">West African</option>
						</select>
						</td>
					</tr>"; ?>
				<?php
				switch ($creed)
				{
					case "1":
					$_1d = "selected";
					break;
					case "2":
					$_2d = "selected";
					break;
					case "3":
					$_3d = "selected";
					break;
					case "4":
					$_4d = "selected";
					break;
					case "5":
					$_5d = "selected";
					break;
					case "6":
					$_6d = "selected";
					break;
					case "7":
					$_7d = "selected";
					break;
					case "8":
					$_8d = "selected";
					break;
					case "9":
					$_9d = "selected";
					break;
					case "10":
					$_10d = "selected";
					break;
					case "11":
					$_11d = "selected";
					break;
					case "12":
					$_12d = "selected";
					break;
					case "13":
					$_13d = "selected";
					break;
					case "14":
					$_14d = "selected";
					break;
					case "15":
					$_15d = "selected";
					break;
					case "16":
					$_16d = "selected";
					break;
					case "17":
					$_17d = "selected";
					break;
					case "18":
					$_18d = "selected";
					break;
					case "19":
					$_19d = "selected";
					break;
					case "20":
					$_20d = "selected";
					break;
					case "21":
					$_21d = "selected";
					break;
					case "22":
					$_22d = "selected";
					break;
					case "23":
					$_23d = "selected";
					break;
					case "24":
					$_24d = "selected";
					break;
					case "25":
					$_25d = "selected";
					break;
				}
				echo "
					<tr>
						<td class='list_central_row_title'>Creed:</td>
						<td class='list_central_row_info'>
						<select name='creed'>
						<option value='1' " . $_1d . ">Agnostic</option>
						<option value='2' " . $_2d . ">Animist</option>
						<option value='3' " . $_3d . ">Atheist</option>
						<option value='4' " . $_4d . ">Ayyavazhi</option>
						<option value='5' " . $_5d . ">Bahá'í Faith</option>
						<option value='6' " . $_6d . ">Bön</option>
						<option value='7' " . $_7d . ">Buddhist</option>
						<option value='8' " . $_8d . ">Catholic</option>
						<option value='9' " . $_9d . ">Christian</option>
						<option value='10' " . $_10d . ">Confucian</option>
						<option value='11' " . $_11d . ">Gnosticism</option>
						<option value='12' " . $_12d . ">Hindu</option>
						<option value='13' " . $_13d . ">Indigenous</option>
						<option value='14' " . $_14d . ">Jainism</option>
						<option value='15' " . $_15d . ">Jewish</option>
						<option value='16' " . $_16d . ">Manichaeism</option>
						<option value='17' " . $_17d . ">Mixed</option>
						<option value='18' " . $_18d . ">Muslim</option>
						<option value='19' " . $_19d . ">Orthodox</option>
						<option value='20' " . $_20d . ">Shamanish</option>
						<option value='21' " . $_21d . ">Shinto</option>
						<option value='22' " . $_22d . ">Sikhist</option>
						<option value='23' " . $_23d . ">Taoist</option>
						<option value='24' " . $_24d . ">Voodoo</option>
						<option value='25' " . $_25d . ">Zoroastrianism</option>
						</select>
						</td>
					</tr>"; ?>
				<?php
				switch ($poli_sci)
				{
					case "1":
					$_1e = "selected";
					break;
					case "2":
					$_2e = "selected";
					break;
					case "3":
					$_3e = "selected";
					break;
					case "4":
					$_4e = "selected";
					break;
					case "5":
					$_5e = "selected";
					break;
					case "6":
					$_6e = "selected";
					break;
					case "7":
					$_7e = "selected";
					break;
					case "8":
					$_8e = "selected";
					break;
					case "9":
					$_9e = "selected";
					break;
					case "10":
					$_10e = "selected";
					break;
					case "11":
					$_11e = "selected";
					break;
					case "12":
					$_12e = "selected";
					break;
					case "13":
					$_13e = "selected";
					break;
					case "14":
					$_14e = "selected";
					break;
					case "15":
					$_15e = "selected";
					break;
					case "16":
					$_16e = "selected";
					break;
					case "17":
					$_17e = "selected";
					break;
					case "18":
					$_18e = "selected";
					break;
					case "19":
					$_19e = "selected";
					break;
					case "20":
					$_20e = "selected";
					break;
					case "21":
					$_21e = "selected";
					break;
					case "22":
					$_22e = "selected";
					break;
				}
				echo "
					<tr>
						<td class='list_central_row_title'>Political System:</td>
						<td class='list_central_row_info'>
						<select name='poli_sci'>
						<option value='1' " . $_1e . ">Absolute Monarchy</option>
						<option value='2' " . $_2e . ">Aristocracy</option>
						<option value='3' " . $_3e . ">Communism</option>
						<option value='4' " . $_4e . ">Constitutional Monarchy</option>
						<option value='5' " . $_5e . ">Constitutional Republic</option>
						<option value='6' " . $_6e . ">Corporatism</option>
						<option value='7' " . $_7e . ">Democratic Socialism</option>
						<option value='8' " . $_8e . ">Despotism</option>
						<option value='9' " . $_9e . ">Diarchy</option>
						<option value='10' " . $_10e . ">Dictatorship</option>
						<option value='11' " . $_11e . ">Direct Democracy</option>
						<option value='12' " . $_12e . ">Libertarianism</option>
						<option value='13' " . $_13e . ">Oligarchy</option>
						<option value='14' " . $_14e . ">Parliamentary Republic</option>
						<option value='15' " . $_15e . ">Participatory Democracy</option>
						<option value='16' " . $_16e . ">Plutocracy</option>
						<option value='17' " . $_17e . ">Police State</option>
						<option value='18' " . $_18e . ">Representative Democracy</option>
						<option value='19' " . $_19e . ">Socialism</option>
						<option value='20' " . $_20e . ">Theocracy</option>
						<option value='21' " . $_21e . ">Totalitarianism</option>
						<option value='22' " . $_22e . ">Tribalism</option>
						</select>
						</td>
					</tr>";?>
				<?php
				switch ($zone)
				{
					case "1":
					$_1f = "selected";
					break;
					case "2":
					$_2f = "selected";
					break;
					case "3":
					$_3f = "selected";
					break;
					case "4":
					$_4f = "selected";
					break;
					case "5":
					$_5f = "selected";
					break;
					case "6":
					$_6f = "selected";
					break;
					case "7":
					$_7f = "selected";
					break;
					case "8":
					$_8f = "selected";
					break;
					case "9":
					$_9f = "selected";
					break;
				}
				echo "
					<tr>
						<td class='list_central_row_title'>Zone:</td>
						<td class='list_central_row_info'>
						<select name='zone'>
						<option value='1' " . $_1f . ">&Alpha; - Alpha</option>
						<option value='2' " . $_2f . ">&Beta; - Beta</option>
						<option value='3' " . $_3f . ">&Gamma; - Gamma</option>
						<option value='4' " . $_4f . ">&Delta; - Delta</option>
						<option value='5' " . $_5f . ">&Epsilon; - Epsilon</option>
						<option value='6' " . $_6f . ">&Zeta; - Zeta</option>
						<option value='7' " . $_7f . ">&Eta; - Eta</option>
						<option value='8' " . $_8f . ">&Theta; - Theta</option>
						<option value='9' " . $_9f . ">&Iota; - Iota</option>
						</td>
					</tr>"; ?>
				<?php
				switch ($land_type)
				{
					case "1":
					$_1g = "selected";
					break;
					case "2":
					$_2g = "selected";
					break;
					case "3":
					$_3g = "selected";
					break;
					case "4":
					$_4g = "selected";
					break;
					case "5":
					$_5g = "selected";
					break;
					case "6":
					$_6g = "selected";
					break;
					case "7":
					$_7g = "selected";
					break;
					case "8":
					$_8g = "selected";
					break;
					case "9":
					$_9g = "selected";
					break;
					case "10":
					$_10g = "selected";
					break;
					case "11":
					$_11g = "selected";
					break;
				}
				echo "
					<tr>
						<td class='list_central_row_title'>Initial Land Area:</td>
						<td class='list_central_row_info'>
						<select name='land_type'>
						<option value='1' " . $_1g . ">Mountain</option>
						<option value='2' " . $_2g . ">Moorland</option>
						<option value='3' " . $_3g . ">Tundra</option>
						<option value='4' " . $_4g . ">Forest</option>
						<option value='5' " . $_5g . ">Prairie</option>
						<option value='6' " . $_6g . ">Savannah</option>
						<option value='7' " . $_7g . ">Polar</option>
						<option value='8' " . $_8g . ">Desert</option>
						<option value='9' " . $_9g . ">Marsh</option>
						<option value='10' " . $_10g . ">Rainforest</option>
						<option value='11' " . $_11g . ">River Delta</option>
						</td>
					</tr>"; ?>
				<?php
				switch ($tax_rate)
				{
					case "10":
					$_10h = "selected";
					break;
					case "11":
					$_11h = "selected";
					break;
					case "12":
					$_12h = "selected";
					break;
					case "13":
					$_13h = "selected";
					break;
					case "14":
					$_14h = "selected";
					break;
					case "15":
					$_15h = "selected";
					break;
					case "16":
					$_16h = "selected";
					break;
					case "17":
					$_17h = "selected";
					break;
					case "18":
					$_18h = "selected";
					break;
					case "19":
					$_19h = "selected";
					break;
					case "20":
					$_20h = "selected";
					break;
					case "21":
					$_21h = "selected";
					break;
					case "22":
					$_22h = "selected";
					break;
					case "23":
					$_23h = "selected";
					break;
					case "24":
					$_24h = "selected";
					break;
					case "25":
					$_25h = "selected";
					break;
				}
				echo "
					<tr>
						<td class='list_central_row_title'>Tax Rate:</td>
						<td class='list_central_row_info'>
						<select name='tax_rate'>
						<option value='10' " . $_10h . ">10%</option>
						<option value='11' " . $_11h . ">11%</option>
						<option value='12' " . $_12h . ">12%</option>
						<option value='13' " . $_13h . ">13%</option>
						<option value='14' " . $_14h . ">14%</option>
						<option value='15' " . $_15h . ">15%</option>
						<option value='16' " . $_16h . ">16%</option>
						<option value='17' " . $_17h . ">17%</option>
						<option value='18' " . $_18h . ">18%</option>
						<option value='19' " . $_19h . ">19%</option>
						<option value='20' " . $_20h . ">20%</option>
						<option value='21' " . $_21h . ">21%</option>
						<option value='22' " . $_22h . ">22%</option>
						<option value='23' " . $_23h . ">23%</option>
						<option value='24' " . $_24h . ">24%</option>
						<option value='25' " . $_25h . ">25%</option>
						</td>
					</tr>"; ?>
				<?php
				switch ($peace_war)
				{
					case "1":
					$_1i = "selected";
					break;
					case "2":
					$_2i = "selected";
					break;
				}
				echo "
					<tr>
						<td class='list_central_row_title'>Peace/War Setting:</td>
						<td class='list_central_row_info'>
						<select name='peace_war'>
						<option value='1' " . $_1i . ">Peace</option>
						<option value='2' " . $_2i . " disabled='disabled'>War</option>
						</td>
					</tr>"; ?>
			<tr>
				<td class='button' colspan='2'><input type='submit' name='submit' value='Update Nation!' /></td>
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
else
{
	//if the cookie does not exist, they are taken to the expired session login page
	header("Location: expiredsession.php");
}
?> 