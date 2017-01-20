<?php
/** nation_alter.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();
include ('../functions/side_menu_functions.php');
include ('../functions/resource_functions.php');
include ('../functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'sub';
$route = '../';
$page_title_name = 'Alter A Nation';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

?>
<script language="JavaScript">
<!--
function restrict(history)
{
	// Specify the maximum length  if (history.value.length > maxlength)
	var maxlength = 300;

	history.value = history.value.substring(0,maxlength);
}
-->
</script>
<?php

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$pass = $_COOKIE['Key_i_Conquest'];
	$check = mysql_query("SELECT * FROM users WHERE username = '$username'")or die(mysql_error());
	while($info = mysql_fetch_array( $check ))
	{
		// If the cookie has the wrong password, they are taken to the login page
		if ($pass != $info['password'])
		{
			header("Location: ../login.php");
		}
		else
		{
			// Otherwise they are shown the members area

			// Collect the nation ID that corresponds with the username
			$query = "SELECT * FROM users";
			$result = mysql_query("SELECT ID, mod_admin FROM users WHERE username = '$username'") or die(mysql_error());
			$IDcheck = mysql_fetch_array($result) or die(mysql_error());
			$ID = $IDcheck['ID'];
			$mod_admin = $IDcheck['mod_admin'];
			if($mod_admin != 2)
			{
				echo "<META HTTP-EQUIV='Refresh' Content='0; URL=../login.php'>";
			}

			// Collect the switchboard information
			$switch_stats = mysql_query("SELECT site_online, version FROM switchboard WHERE ID_switch = '1'") or die(mysql_error());
			while($switch = mysql_fetch_array( $switch_stats ))
			{
				$site_online = stripslashes($switch['site_online']);
				$ic_version_marker = stripslashes($switch['version']);
			}

			site_online($site_online, $mod_admin);

			include ('../header.php');

			// Determine which side menu to use
			which_side_menu($ID, $mod_admin, $site_area);

			// If the nation settings form is submitted
			if (isset($_POST['find']))
			{
				$ID = $_POST['ID'];

				// Collect the nation leader that corresponds with ID
				$result = mysql_query("SELECT * FROM users WHERE ID = '$ID'") or die(mysql_error());
				while($verify = mysql_fetch_array($result))
				{
					$ID_verify = $verify['ID'];
				}

				if($ID == $ID_verify)
				{
					// Grab all of the nation data we can to be edited
					// Collect the nation information for display from the nations table
					$nationstats = mysql_query("SELECT * FROM nations WHERE ID = '$ID'") or die(mysql_error());
					while($row = mysql_fetch_array( $nationstats ))
					{
						// Collect the raw data from the nations table in the db 
						$nation = $row['nation'];
						$region = $row['region'];
						$capitol = $row['capitol'];
						$title = $row['title'];
						$tax_rate = $row['tax_rate'];
						$land_type = $row['land_type'];
						$zone = $row['zone'];
						$poli_sci = $row['poli_sci'];
						$currency = $row['currency'];
						$ethnicity = $row['ethnicity'];
						$language = $row['language'];
						$creed = $row['creed'];
						$peace_war = $row['peace_war'];
					}

					// Collect the nation information for display from the nation_variables table
					$nationstats2 = mysql_query("SELECT * FROM nation_variables WHERE ID = '$ID'") or die(mysql_error());
					while($row2 = mysql_fetch_array( $nationstats2 ))
					{
						// Collect the raw data from the nation_variables table in the db 
						$resource1 = $row2['resource_1'];
						$resource2 = $row2['resource_2'];
						$treasury = $row2['treasury'];
						$land = $row2['land'];
						$infra = $row2['infra'];
						$tech = $row2['tech'];
						$capital = $row2['capital'];
						$last_col_bill = $row2['last_bill'];
						$last_col_tax = $row2['last_tax'];
						$history = $row2['history'];
					}

					// Collect the nation information for display from the military table
					$military = mysql_query("SELECT * FROM military WHERE ID = '$ID'") or die(mysql_error());
					while($mil = mysql_fetch_array( $military ))
					{
						// Collect the raw data from the military table in the db 
						$inf_1 = $mil['inf_1'];
						$inf_2 = $mil['inf_2'];
						$inf_3 = $mil['inf_3'];
						$inf_losses = $mil['inf_loss'];
					}

					// Insert change form
					?>
					<td>
					<form action='../admin/complete_nation_edit.php' method='post'>
					<table border='1' width='600'>
					<tr>
						<th colspan='2'>Admin Terminal</th>
					</tr>
					<tr>
						<td colspan='2'><em>Altering <?php echo $nation ?></em></td>
					</tr>
					<tr>
						<td colspan='2'><strong><em><font color='red'><center>BE VERY CAREFUL WHEN EDITING A NATION!<br />ALL FIELDS ARE CHANGED!</center></font></em></strong></td>
					</tr>
					<tr>
						<td width='150'>Nation ID: </td>
						<td><input type='text' readonly='readonly' name='ID' value='<?php echo $ID ?>' /></td>
					</tr>
					<tr>
						<td width='150'>Nation Name: </td>
						<td><input type='text' name='nation' maxlength='20' value='<?php echo $nation ?>' /><br />(20 character limit)</td>
					</tr>
					<tr>
						<td>Region Name: </td>
						<td><input type='text' name='region' maxlength='20' value='<?php echo $region ?>' /><br />(20 character limit)</td>
					</tr>
					<tr>
						<td>Capitol Name: </td>
						<td><input type='text' name='capitol' maxlength='20' value='<?php echo $capitol ?>' /><br />(20 character limit)</td>
					</tr>
					<tr>
						<td>Title Name: </td>
						<td><input type='text' name='title' maxlength='20' value='<?php echo $title ?>' /><br />(20 character limit)</td>
					</tr>
					<tr>
						<td>Treasury: </td>
						<td><input type='text' name='treasury' maxlength='20' value='<?php echo $treasury ?>' /><br />(do not use commas, only 1 decimal)</td>
					</tr>
					<tr>
						<td>Infrastructure: </td>
						<td><input type='text' name='infra' maxlength='20' value='<?php echo $infra ?>' /><br />(do not use commas, only 1 decimal)</td>
					</tr>
					<tr>
						<td>Technology: </td>
						<td><input type='text' name='tech' maxlength='20' value='<?php echo $tech ?>' /><br />(do not use commas, only 1 decimal)</td>
					</tr>
					<tr>
						<td>Land: </td>
						<td><input type='text' name='land' maxlength='20' value='<?php echo $land ?>' /><br />(do not use commas, only 1 decimal)</td>
					</tr>
					<tr>
						<td colspan='2'><strong><em><font color='red'><center>BE VERY CAREFUL WHEN EDITING A NATION!<br />ALL FIELDS ARE CHANGED!</center></font></em></strong></td>
					</tr>
					<tr>
						<td>Capital: </td>
						<td><input type='text' name='capital' maxlength='20' value='<?php echo $capital ?>' /><br />(do not use commas, only 1 decimal)</td>
					</tr>
					<tr>
						<td>Last Bills Collect: </td>
						<td><input type='text' name='last_col_bill' maxlength='10' value='<?php echo $last_col_bill ?>' /><br />(Unix Timestamp)</td>
					</tr>
					<tr>
						<td>Last Tax Collect: </td>
						<td><input type='text' name='last_col_tax' maxlength='10' value='<?php echo $last_col_tax ?>' /><br />(Unix Timestamp)</td>
					</tr>
					<tr>
						<td>Infantry 1: </td>
						<td><input type='text' name='inf_1' maxlength='20' value='<?php echo $inf_1 ?>' /><br />(do not use commas, only 1 decimal)</td>
					</tr>
					<tr>
						<td>Infantry 2: </td>
						<td><input type='text' name='inf_2' maxlength='20' value='<?php echo $inf_2 ?>' /><br />(do not use commas, only 1 decimal)</td>
					</tr>
					<tr>
						<td>Infantry 3: </td>
						<td><input type='text' name='inf_3' maxlength='20' value='<?php echo $inf_3 ?>' /><br />(do not use commas, only 1 decimal)</td>
					</tr>
					<tr>
						<td>Infantry Losses: </td>
						<td><input type='text' name='inf_losses' maxlength='20' value='<?php echo $inf_losses ?>' /><br />(do not use commas, only 1 decimal)</td>
					</tr>
					<tr>
						<td>History: </td>
						<td><textarea rows='8' cols='40' name='history' onkeyup="restrict(this.form.history)"><?php echo $history ?></textarea><br />(300 character limit)</td>
					</tr>
					<tr>
						<td colspan='2'><strong><em><font color='red'><center>BE VERY CAREFUL WHEN EDITING A NATION!<br />ALL FIELDS ARE CHANGED!</center></font></em></strong></td>
					</tr>
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
							<td>Currency:</td>
							<td>
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
							<td>Language:</td>
							<td>
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
							<td>Ethnicity:</td>
							<td>
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
							<td>Creed:</td>
							<td>
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
							<td>Political System:</td>
							<td>
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
						$_1f="selected";
						break;
						case "2":
						$_2f="selected";
						break;
						case "3":
						$_3f="selected";
						break;
						case "4":
						$_4f="selected";
						break;
						case "5":
						$_5f="selected";
						break;
						case "6":
						$_6f="selected";
						break;
						case "7":
						$_7f="selected";
						break;
						case "8":
						$_8f="selected";
						break;
						case "9":
						$_9f="selected";
						break;
					}
					echo "
						<tr>
							<td>Zone:</td><td>
							<select name='zone'>
							<option value='1' $_1f>Alpha</option>
							<option value='2' $_2f>Beta</option>
							<option value='3' $_3f>Gamma</option>
							<option value='4' $_4f>Delta</option>
							<option value='5' $_5f>Epsilon</option>
							<option value='6' $_6f>Zeta</option>
							<option value='7' $_7f>Eta</option>
							<option value='8' $_8f>Theta</option>
							<option value='9' $_9f>Iota</option>
							</td>
						</tr>"; ?>
					<?php
					// Switch to retrieve previous land_type selection from db and making it the selected item in the form.
					switch ($land_type)
					{
						case "1":
						$_1g="selected";
						break;
						case "2":
						$_2g="selected";
						break;
						case "3":
						$_3g="selected";
						break;
						case "4":
						$_4g="selected";
						break;
						case "5":
						$_5g="selected";
						break;
						case "6":
						$_6g="selected";
						break;
						case "7":
						$_7g="selected";
						break;
						case "8":
						$_8g="selected";
						break;
						case "9":
						$_9g="selected";
						break;
						case "10":
						$_10g="selected";
						break;
						case "11":
						$_11g="selected";
						break;
					}
					echo "
						<tr>
							<td>Initial Land Area:</td><td>
							<select name='land_type'>
							<option value='1' $_1g>Mountain</option>
							<option value='2' $_2g>Moorland</option>
							<option value='3' $_3g>Tundra</option>
							<option value='4' $_4g>Forest</option>
							<option value='5' $_5g>Prairie</option>
							<option value='6' $_6g>Savannah</option>
							<option value='7' $_7g>Polar</option>
							<option value='8' $_8g>Desert</option>
							<option value='9' $_9g>Marsh</option>
							<option value='10' $_10g>Rainforest</option>
							<option value='11' $_11g>River Delta</option>
							</td>
						</tr>"; ?>
					<?php
					// Switch to retrieve previous tax_rate selection from db and making it the selected item in the form.
					switch ($tax_rate)
					{
						case "10":
						$_10h="selected";
						break;
						case "11":
						$_11h="selected";
						break;
						case "12":
						$_12h="selected";
						break;
						case "13":
						$_13h="selected";
						break;
						case "14":
						$_14h="selected";
						break;
						case "15":
						$_15h="selected";
						break;
						case "16":
						$_16h="selected";
						break;
						case "17":
						$_17h="selected";
						break;
						case "18":
						$_18h="selected";
						break;
						case "19":
						$_19h="selected";
						break;
						case "20":
						$_20h="selected";
						break;
						case "21":
						$_21h="selected";
						break;
						case "22":
						$_22h="selected";
						break;
						case "23":
						$_23h="selected";
						break;
						case "24":
						$_24h="selected";
						break;
						case "25":
						$_25h="selected";
						break;
					}
					echo "
						<tr>
							<td>Tax Rate:</td><td>
							<select name='tax_rate'>
							<option value='10' $_10h>10%</option>
							<option value='11' $_11h>11%</option>
							<option value='12' $_12h>12%</option>
							<option value='13' $_13h>13%</option>
							<option value='14' $_14h>14%</option>
							<option value='15' $_15h>15%</option>
							<option value='16' $_16h>16%</option>
							<option value='17' $_17h>17%</option>
							<option value='18' $_18h>18%</option>
							<option value='19' $_19h>19%</option>
							<option value='20' $_20h>20%</option>
							<option value='21' $_21h>21%</option>
							<option value='22' $_22h>22%</option>
							<option value='23' $_23h>23%</option>
							<option value='24' $_24h>24%</option>
							<option value='25' $_25h>25%</option>
							</td>
						</tr>"; ?>
					<tr>
						<td colspan='2'><strong><em><font color='red'><center>BE VERY CAREFUL WHEN EDITING A NATION!<br />ALL FIELDS ARE CHANGED!</center></font></em></strong></td>
					</tr>
					<?php
					// Switch to retrieve previous peace_war selection from db and making it the selected item in the form.
					switch ($peace_war)
					{
						case "1":
						$_1i="selected";
						break;
						case "2":
						$_2i="selected";
						break;
					}
					echo "
						<tr>
							<td>Peace/War Setting:</td><td>
							<select name='peace_war'>
							<option value='1' $_1i>Peace</option>
							<option value='2' $_2i>War</option>
							</td>
						</tr>"; ?>
					<?php
					switch ($resource1)
					{
						case "1":
						$_1j="checked='checked'";
						break;
						case "2":
						$_2j="checked='checked'";
						break;
						case "3":
						$_3j="checked='checked'";
						break;
						case "4":
						$_4j="checked='checked'";
						break;
						case "5":
						$_5j="checked='checked'";
						break;
						case "6":
						$_6j="checked='checked'";
						break;
						case "7":
						$_7j="checked='checked'";
						break;
						case "8":
						$_8j="checked='checked'";
						break;
						case "9":
						$_9j="checked='checked'";
						break;
						case "10":
						$_10j="checked='checked'";
						break;
						case "11":
						$_11j="checked='checked'";
						break;
						case "12":
						$_12j="checked='checked'";
						break;
						case "13":
						$_13j="checked='checked'";
						break;
						case "14":
						$_14j="checked='checked'";
						break;
						case "15":
						$_15j="checked='checked'";
						break;
					}
					echo"
					<tr>
						<td>Resource 1:</td>
						<td>
						<input type='radio' name='resource1' value='1' $_1j /> ". res_to_image_sub(1) ."
						<input type='radio' name='resource1' value='2' $_2j /> ". res_to_image_sub(2) ."
						<input type='radio' name='resource1' value='3' $_3j /> ". res_to_image_sub(3) ."
						<input type='radio' name='resource1' value='4' $_4j /> ". res_to_image_sub(4) ."
						<input type='radio' name='resource1' value='5' $_5j /> ". res_to_image_sub(5) ."<br />
						<input type='radio' name='resource1' value='6' $_6j /> ". res_to_image_sub(6) ."
						<input type='radio' name='resource1' value='7' $_7j /> ". res_to_image_sub(7) ."
						<input type='radio' name='resource1' value='8' $_8j /> ". res_to_image_sub(8) ."
						<input type='radio' name='resource1' value='9' $_9j /> ". res_to_image_sub(9) ."
						<input type='radio' name='resource1' value='10' $_10j /> ". res_to_image_sub(10) ."<br />
						<input type='radio' name='resource1' value='11' $_11j /> ". res_to_image_sub(11) ."
						<input type='radio' name='resource1' value='12' $_12j /> ". res_to_image_sub(12) ."
						<input type='radio' name='resource1' value='13' $_13j /> ". res_to_image_sub(13) ."
						<input type='radio' name='resource1' value='14' $_14j /> ". res_to_image_sub(14) ."
						<input type='radio' name='resource1' value='15' $_15j /> ". res_to_image_sub(15) ."
						</td>
					</tr>";
					switch ($resource2)
					{
						case "1":
						$_1k="checked='checked'";
						break;
						case "2":
						$_2k="checked='checked'";
						break;
						case "3":
						$_3k="checked='checked'";
						break;
						case "4":
						$_4k="checked='checked'";
						break;
						case "5":
						$_5k="checked='checked'";
						break;
						case "6":
						$_6k="checked='checked'";
						break;
						case "7":
						$_7k="checked='checked'";
						break;
						case "8":
						$_8k="checked='checked'";
						break;
						case "9":
						$_9k="checked='checked'";
						break;
						case "10":
						$_10k="checked='checked'";
						break;
						case "11":
						$_11k="checked='checked'";
						break;
						case "12":
						$_12k="checked='checked'";
						break;
						case "13":
						$_13k="checked='checked'";
						break;
						case "14":
						$_14k="checked='checked'";
						break;
						case "15":
						$_15k="checked='checked'";
						break;
					}
					echo"
					<tr>
						<td>Resource 2:</td>
						<td>
						<input type='radio' name='resource2' value='1' $_1k /> ". res_to_image_sub(1) ."
						<input type='radio' name='resource2' value='2' $_2k /> ". res_to_image_sub(2) ."
						<input type='radio' name='resource2' value='3' $_3k /> ". res_to_image_sub(3) ."
						<input type='radio' name='resource2' value='4' $_4k /> ". res_to_image_sub(4) ."
						<input type='radio' name='resource2' value='5' $_5k /> ". res_to_image_sub(5) ."<br />
						<input type='radio' name='resource2' value='6' $_6k /> ". res_to_image_sub(6) ."
						<input type='radio' name='resource2' value='7' $_7k /> ". res_to_image_sub(7) ."
						<input type='radio' name='resource2' value='8' $_8k /> ". res_to_image_sub(8) ."
						<input type='radio' name='resource2' value='9' $_9k /> ". res_to_image_sub(9) ."
						<input type='radio' name='resource2' value='10' $_10k /> ". res_to_image_sub(10) ."<br />
						<input type='radio' name='resource2' value='11' $_11k /> ". res_to_image_sub(11) ."
						<input type='radio' name='resource2' value='12' $_12k /> ". res_to_image_sub(12) ."
						<input type='radio' name='resource2' value='13' $_13k /> ". res_to_image_sub(13) ."
						<input type='radio' name='resource2' value='14' $_14k /> ". res_to_image_sub(14) ."
						<input type='radio' name='resource2' value='15' $_15k /> ". res_to_image_sub(15) ."
						</td>
					</tr>";
					?>
					<tr>
						<td colspan='2' align='center'><input type='submit' name='change' value='Change' /></td>
					</tr>
					</form>
					</table>
				<?php
				}
				else
				{
					echo "Nation does not exist.";
				}
			}
			
			// Check to see what type of user they are
			// 0 = player; 1 = mod; 2 = admin
			if($IDcheck['mod_admin'] == 2)
			{
				?>
				<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
				<table border='1' width='600'>
				<tr>
					<th colspan='2'>Admin Terminal</th>
				</tr>
				<tr>
					<td colspan='2'><em>Alter a Nation</em></td>
				</tr>
				<tr>
					<td>Enter a Nation ID: </td>
					<td><input type='text' name='ID' maxlength='6' value='<?php echo $ID_verify; ?>' /></td>
				</tr>
				<tr>
					<td colspan='2' align='center'><input type='submit' name='find' value='Find' /></td>
				</tr>
				</form>
				</table>
				</td>
				</tr>
				</table>
				<?php
				include ('../footer.php');
			}
		}
	}
}
else
{
	// If the cookie does not exist, they are taken to the login screen
	header("Location: ../login.php");
}
?>