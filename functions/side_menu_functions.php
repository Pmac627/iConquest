<?php
function which_side_menu($ID, $mod_admin, $site_area)
{
	if($mod_admin == 0 || $mod_admin == 1 && $ID != NULL && $site_area == 'main')
	{
		return side_menu_main($ID);
	}
	elseif($mod_admin == 1 && $ID != NULL && $site_area == 'sub')
	{
		return side_menu_subdir($ID);
	}
	elseif($mod_admin == 2 && $ID != NULL && $site_area == 'main')
	{
		return side_menu_admin($ID);
	}
	elseif($mod_admin == 2 && $ID != NULL && $site_area == 'sub')
	{
		return side_menu_admin_area($ID);
	}
	else
	{
		return side_menu_logout();
	}
}

function side_menu_main($ID)
{
// The main menu (on the left side)
	echo"
	<table align='center' border='0'>
	<tr valign='top'>
	<td>
	<table class='side_menu'>
		<tr>
			<td class='side_menu_head'>Menu</td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Quick Links</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='index.php' title='International Conquest Home Page'>iC Home</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='nation.php?ID=" . $ID . "' title='View My Nation'>My Nation</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='icentral.php' title='iCentral Information Center'>iCentral</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='change_password.php' title='Change My Password'>Change Password</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='nation_search.php' title='Search for a Nation'>Nation Search</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='leader_search.php' title='Search for a Leader'>Leader Search</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='docs/basicinfo.php' title='Basic Game Information'>Basic Info</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='docs/versioninfo.php' title='Game Version Summary'>Version Info</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='logout.php' title='Account Logout'>Logout</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>International Comm</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='pm_central.php' title='Private Message Inbox'>Message Central</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='pm_send.php' title='Send a Private Message'>Send a Message</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='foreign_aid.php' title='Foreign Transaction Center'>Foreign Transactions</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='resource_trade.php' title='Foreign Trade Center'>Foreign Trade Contracts</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Nation Actions</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='tax_collection.php' title='Collect Taxes'>Tax Collection</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='bill_payment.php' title='Pay Bills'>Bill Payment</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='land_purchase.php' title='Purchase Land'>Land Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='infra_purchase.php' title=Purchase Infrastructure'>Infra Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='technology_purchase.php' title='Purchase Technology'>Tech Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='capital_purchase.php' title='Purchase Capital'>Capital Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='civil_works.php' title='Purchase Civil Works'>Civil Works</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Military</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='military_command.php' title='Military Headquarters'>Military Command</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='purchase_infantry.php' title='Purchase Infantry Units'>Train Infantry</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='purchase_armor.php' title='Purchase Armor Units'>Build Armor</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Nation Edit</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='nation_settings.php' title='Edit Nation Settings'>Nation Settings</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='edit_history.php' title='Edit Nation History'>History Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='edit_title.php' title='Edit Ruler Title'>Title Name Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='edit_capitol.php' title='Edit Capitol City Name'>Capitol Name Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='edit_region.php' title='Edit Region Names'>Region Name Change</a></td>
		</tr>
	</table>
	</td>";
}

function side_menu_subdir($ID)
{
// The main menu (on the left side)
	echo"
	<table align='center' border='0'>
	<tr valign='top'>
	<td>
	<table class='side_menu'>
		<tr>
			<td class='side_menu_head'>Menu</td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Quick Links</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/index.php' title='International Conquest Home Page'>iC Home</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/nation.php?ID=" . $ID . "' title='View My Nation'>My Nation</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/icentral.php' title='iCentral Information Center'>iCentral</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/change_password.php' title='Change My Password'>Change Password</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/nation_search.php' title='Search for a Nation'>Nation Search</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/leader_search.php' title='Search for a Leader'>Leader Search</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/docs/basicinfo.php' title='Basic Game Information'>Basic Info</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/docs/versioninfo.php' title='Game Version Summary'>Version Info</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/logout.php' title='Account Logout'>Logout</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>International Comm</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/pm_central.php' title='Private Message Inbox'>Message Central</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/pm_send.php' title='Send a Private Message'>Send a Message</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/foreign_aid.php' title='Foreign Transaction Center'>Foreign Transactions</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/resource_trade.php' title='Foreign Trade Center'>Foreign Trade Contracts</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Nation Actions</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/tax_collection.php' title='Collect Taxes'>Tax Collection</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/bill_payment.php' title='Pay Bills'>Bill Payment</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/land_purchase.php' title='Purchase Land'>Land Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/infra_purchase.php' title=Purchase Infrastructure'>Infra Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/technology_purchase.php' title='Purchase Technology'>Tech Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/capital_purchase.php' title='Purchase Capital'>Capital Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/civil_works.php' title='Purchase Civil Works'>Civil Works</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Military</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/military_command.php' title='Military Headquarters'>Military Command</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/purchase_infantry.php' title='Purchase Infantry Units'>Train Infantry</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/purchase_armor.php' title='Purchase Armor Units'>Build Armor</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Nation Edit</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/nation_settings.php' title='Edit Nation Settings'>Nation Settings</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/edit_history.php' title='Edit Nation History'>History Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/edit_title.php' title='Edit Ruler Title'>Title Name Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/edit_capitol.php' title='Edit Capitol City Name'>Capitol Name Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/edit_region.php' title='Edit Region Names'>Region Name Change</a></td>
		</tr>
	</table>
	</td>";
}

function side_menu_logout()
{
// Side Menu Logout: When not logged in
	echo"
	<table align='center' border='0'>
	<tr valign='top'>
	<td>
	<table class='side_menu'>
		<tr>
			<td class='side_menu_head'>Menu</td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Quick Links</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='index.php' title='International Conquest Home Page'>iC Home</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='login.php' title='Account Login'>Login</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='register.php' title='Register for an Account'>Register</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='docs/basicinfo.php' title='Basic Game Information'>Basic Info</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='docs/versioninfo.php' title='Game Version Summary'>Version Info</a></td>
		</tr>
	</table>
	</td>";
}

function side_menu_logout_sub()
{
// Side Menu Logout: When not logged in and viewing a sub-sirectory
	echo"
	<table align='center' border='0'>
	<tr valign='top'>
	<td>
	<table class='side_menu'>
		<tr>
			<td class='side_menu_head'>Menu</td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Quick Links</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/index.php' title='International Conquest Home Page'>iC Home</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/login.php' title='Account Login'>Login</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/register.php' title='Register for an Account'>Register</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/docs/basicinfo.php' title='Basic Game Information'>Basic Info</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='/docs/versioninfo.php' title='Game Version Summary'>Version Info</a></td>
		</tr>
	</table>
	</td>";
}

function side_menu_admin($ID)
{
// The main menu (on the left side)
	echo"
	<table align='center' border='0'>
	<tr valign='top'>
	<td>
	<table class='side_menu'>
		<tr>
			<td class='side_menu_head'>Menu</td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Admin Menu</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='admin/admin_terminal.php' title='Administrative Panel'>Admin Terminal</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Quick Links</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../index.php' title='International Conquest Home Page'>iC Home</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../nation.php?ID=" . $ID . "' title='View My Nation'>My Nation</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../icentral.php' title='iCentral Information Center'>iCentral</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../change_password.php' title='Change My Password'>Change Password</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../nation_search.php' title='Search for a Nation'>Nation Search</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../leader_search.php' title='Search for a Leader'>Leader Search</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../docs/basicinfo.php' title='Basic Game Information'>Basic Info</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../docs/versioninfo.php' title='Game Version Summary'>Version Info</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../logout.php' title='Account Logout'>Logout</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>International Comm</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../pm_central.php' title='Private Message Inbox'>Message Central</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../pm_send.php' title='Send a Private Message'>Send a Message</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../foreign_aid.php' title='Foreign Transaction Center'>Foreign Transactions</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../resource_trade.php' title='Foreign Trade Center'>Foreign Trade Contracts</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Nation Actions</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../tax_collection.php' title='Collect Taxes'>Tax Collection</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../bill_payment.php' title='Pay Bills'>Bill Payment</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../land_purchase.php' title='Purchase Land'>Land Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../infra_purchase.php' title=Purchase Infrastructure'>Infra Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../technology_purchase.php' title='Purchase Technology'>Tech Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../capital_purchase.php' title='Purchase Capital'>Capital Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../civil_works.php' title='Purchase Civil Works'>Civil Works</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Military</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../military_command.php' title='Military Headquarters'>Military Command</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../purchase_infantry.php' title='Purchase Infantry Units'>Train Infantry</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../purchase_armor.php' title='Purchase Armor Units'>Build Armor</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Nation Edit</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../nation_settings.php' title='Edit Nation Settings'>Nation Settings</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../edit_history.php' title='Edit Nation History'>History Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../edit_title.php' title='Edit Ruler Title'>Title Name Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../edit_capitol.php' title='Edit Capitol City Name'>Capitol Name Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../edit_region.php' title='Edit Region Names'>Region Name Change</a></td>
		</tr>
	</table>
	</td>
	<td>";
}

function side_menu_admin_area($ID)
{
// The main menu (on the left side)
	echo"
	<table align='center' border='0'>
	<tr valign='top'>
	<td>
	<table class='side_menu'>
		<tr>
			<td class='side_menu_head'>Menu</td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Admin Menu</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='admin_terminal.php' title='Administrative Panel'>Admin Terminal</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Quick Links</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../index.php' title='International Conquest Home Page'>iC Home</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../nation.php?ID=" . $ID . "' title='View My Nation'>My Nation</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../icentral.php' title='iCentral Information Center'>iCentral</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../change_password.php' title='Change My Password'>Change Password</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../nation_search.php' title='Search for a Nation'>Nation Search</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../leader_search.php' title='Search for a Leader'>Leader Search</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../docs/basicinfo.php' title='Basic Game Information'>Basic Info</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../docs/versioninfo.php' title='Game Version Summary'>Version Info</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../logout.php' title='Account Logout'>Logout</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>International Comm</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../pm_central.php' title='Private Message Inbox'>Message Central</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../pm_send.php' title='Send a Private Message'>Send a Message</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../foreign_aid.php' title='Foreign Transaction Center'>Foreign Transactions</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../resource_trade.php' title='Foreign Trade Center'>Foreign Trade Contracts</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Nation Actions</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../tax_collection.php' title='Collect Taxes'>Tax Collection</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../bill_payment.php' title='Pay Bills'>Bill Payment</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../land_purchase.php' title='Purchase Land'>Land Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../infra_purchase.php' title=Purchase Infrastructure'>Infra Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../technology_purchase.php' title='Purchase Technology'>Tech Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../capital_purchase.php' title='Purchase Capital'>Capital Purchase</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../civil_works.php' title='Purchase Civil Works'>Civil Works</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Military</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../military_command.php' title='Military Headquarters'>Military Command</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../purchase_infantry.php' title='Purchase Infantry Units'>Train Infantry</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../purchase_armor.php' title='Purchase Armor Units'>Build Armor</a></td>
		</tr>
		<tr>
			<th class='side_menu_lable'>Nation Edit</th>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../nation_settings.php' title='Edit Nation Settings'>Nation Settings</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../edit_history.php' title='Edit Nation History'>History Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../edit_title.php' title='Edit Ruler Title'>Title Name Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../edit_capitol.php' title='Edit Capitol City Name'>Capitol Name Change</a></td>
		</tr>
		<tr>
			<td class='side_menu_field'><a class='side_menu' href='../edit_region.php' title='Edit Region Names'>Region Name Change</a></td>
		</tr>
	</table>
	</td>
	<td>";
}
?>