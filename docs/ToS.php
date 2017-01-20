<?php
/** ToS.php **/

// Database connection function
require ('../functions/database_connection_function.php');
database_connection();
$conn = mysql_connect("localhost", "pmac627_conquest", "iconquest101") or die(mysql_error());
include ('../functions/side_menu_functions.php');
include ('../functions/days_since_functions.php');
include ('../functions/switchboard_functions.php');

// Labels this page as either a main directory or a sub-directory for the menu
$site_area = 'sub';
$mod_admin = 3;
$route = '../';
$page_title_name = 'Terms and Conditions of Service';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

// Checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_i_Conquest']))
{
	$username = $_COOKIE['ID_i_Conquest'];
	$check = mysql_query("SELECT ID, mod_admin FROM users WHERE username = '$username'")or die(mysql_error());
	if($info = mysql_fetch_array( $check ))
	{
		$ID = $info['ID'];
		$mod_admin = $info['mod_admin'];
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
	
}
else
{
	include ('../header.php');
	side_menu_logout_sub();
}
?>
<td>
<table class='main'>
	<tr>
		<th class='form_head'>Terms and Conditions of Service</th>
	</tr>
	<tr>
		<td class='index_message_spacer'></td>
	</tr>
	<tr>
		<td>
			<table class='index_message_box' cellpadding='0' cellspacing='0'>
				<tr>
					<td>
						<table class='index_message_box_mini_no_author' cellpadding='0' cellspacing='0'>
							<tr>
								<td class='index_box_center'>Terms and Conditions of Service</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class='index_message_shell'>
						<p class='index_message_body'>Your continued use of this web site constitutes your agreement to all such terms, conditions and notices, as they appear in this document. By accessing this website, you agree to be bound by the terms and conditions appearing in this document. If you are found to be in breach of the terms of this website or the International Conquest forums, you will be subject to removal from the game and forums perminently.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class='index_message_spacer'></td>
	</tr>
	<tr>
		<td>
			<table class='index_message_box' cellpadding='0' cellspacing='0'>
				<tr>
					<td>
						<table class='index_message_box_mini_no_author' cellpadding='0' cellspacing='0'>
							<tr>
								<td class='index_box_center'>Content, Exploits, and Account Warnings</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class='index_message_shell'>
						<p class='index_message_body'>International Conquest is a free browser-based nation building game. The nations are not real, the money that you collect is not real, the wars, politics, religions, units, or any other game concepts or objects are not real. What is real are the users of International Conquest. By participating in this website, you agree not to use inappropriate, threatening, harassing, malicious, defamatory, rude, or obscene comments, messages, titles, or histories or any other such offensive or harmful content. Users agree to not engage in extortion of real life possessions of other users in response to in-game situations or vice versa. Impersonating any of the International Conquest staff will result in the player's immediate ban. Moderator Harassment will not be tolerated in any shape or form.<br /><br />
While reasonable attempts will be made to ensure the website remains free of explicit and harmful content, the site administrator and/or moderator(s) are not responsible for what other people upload to the website. You understand that in this case that International Conquest is a not a publisher and is not liable for any damages from such content and information posted by its players.<br /><br />
By using this service you agree not to cheat in any form, including abuse of the warfare system, foreign transaction system, resource trade system, private message system, unit purchase systems, etc. with multiple accounts or bug exploits. You agree not to create more than one user account in this game and understand that by creating or accessing more than one account you will be in violation of these terms and conditions. You understand that multiple user accounts from the same computer network are not allowed, even if the accounts are managed by different individuals unless explicit permission is given by the head administrator himself (admin). Account sitting is not allowed.<br /><br />
If you come across a bug or exploit in the game system or forums, you agree to report it to the appropriate administrator and/or moderator(s) as soon as possible. If you use the exploit you will be removed from the website and forums. You agree to not send fake system reports in-game or on the forums in an attempt to deceive or confuse other users via the private message system. Users caught trying to fill other user's limited aid, trade or warfare slots by sending unsubstantial joke packages and offers will receive an in-game warning or ban.<br /><br />
Account deletions and/or modifications can occur at any time by the International Conquest administrator and/or moderator(s) at their discretion. International Conquest reserves the right to refuse or remove membership to any user at any time for any reason. If your nation is left inactive for 30 or more days, it will be deleted without notice. Activity is updated only during tax collection. If your nation is deleted for inactivity it cannot and will not be restored.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class='index_message_spacer'></td>
	</tr>
	<tr>
		<td>
			<table class='index_message_box' cellpadding='0' cellspacing='0'>
				<tr>
					<td>
						<table class='index_message_box_mini_no_author' cellpadding='0' cellspacing='0'>
							<tr>
								<td class='index_box_center'>Scripts, Bots, and other Game Manipulations</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class='index_message_shell'>
						<p class='index_message_body'>By using this website, you are agreeing not to access International Conquest with any other programs other than web browsers. Other programs such as "Bots" as well as other tools that replace the web interface. The same applies to completely or partially automated scripts or programs that cause increased server loads. You must agree to not, under any circumstance, run automated scripts of any kind against this website. Browser add-ons are generally ok as long as they do not cause increased server loads or present security issues.  Any and all unnecessary increase of server load or bandwidth will not be tolerated.<br /><br />
By using this website, you are agreeing not to attempt to access our servers, protected or coded data. You are agreeing to do nothing that interferes with the ability of other International Conquest users to enjoy the game in accordance with its rules, or that increases our expense or difficulty in maintaining the service. You agree not use this website for any illegal purposes, and you will use it in compliance with all applicable laws and regulations. You agree not to use this website in a way that may cause this website to be interrupted, damaged, rendered less efficient or such that the effectiveness or functionality of this website is in any way impaired.<br /><br />
International Conquest is in beta development and as a result game features, demographics, statistics, calculations, and everything else may be subject to change at any time. We reserve the right to modify or withdraw, temporarily or permanently, this website (or any part of) with or without notice to you and you confirm that we shall not be liable to you or any third party for any modification to or withdrawal of International Conquest. You will not be eligible for any compensation because you cannot use any part of this website or because of code issues, website failure, suspension or withdrawal of all or part of this website.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class='index_message_spacer'></td>
	</tr>
	<tr>
		<td>
			<table class='index_message_box' cellpadding='0' cellspacing='0'>
				<tr>
					<td>
						<table class='index_message_box_mini_no_author' cellpadding='0' cellspacing='0'>
							<tr>
								<td class='index_box_center'>Donations</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class='index_message_shell'>
						<p class='index_message_body'>International Conquest is privately owned and operated. International Conquest is not a charity and it is not a non-profit organization. Donations are used to help support the site including hosting fees, software licensing, maintenance, administration and employee fees, bandwidth fees, and upgrades for the game as well as other expenses as determined by administration. You are not entitled to a record of how your donation is spent. Players are NOT required to donate to play International Conquest.<br /><br />
All donations made to International Conquest are non-refundable, no matter the reason. Once the transfer of ownership of the donation money has taken place, you are no longer entitled to the donated money. The donation bonus is final once it has been applied to a nation. If the bonus is lost in war, foreign aid, other in-game activities, or unforeseen events donation bonuses will not be refunded or reapplied to an account.<br /><br />
Donations do not entitle players to receive special privileges beyond what is described in the donation offer screen. International Conquest is a free-to-play game and your donations are completely voluntary. Special requests for donating users beyond what is offered in the donation offer screen will not be considered in order to maintain the free-to-play nature of International Conquest.<br /><br />
Donations are completely voluntary and any in game benefits are awarded purely at our discretion. Donating towards the game does not create an ownership interest in the game or its contents. Donation bonuses will be applied within 24 hours after your payment clears. If you send a bank payment that goes into pending status you will have to wait until the payment clears to get the donation bonus. The maximum donation bonus applied per month is the $25 donation offer. If you send more than $25 you will only receive the $25 bonus to your nation. If you have any problems with a donation please email us at admin@internationalconquest.net.<br /><br />
If a player should voluntarily leave the game, if a player's nation is deleted by the player him/her self or deleted/banned by administration for ANY REASON (cheating, violating the International Conquest terms and conditions, violating official forum guidelines, reaching 100% warning level in-game or on the official forums, 30 days inactivity, unstated reasons) or if the game is ever shut down or reset NO REFUNDS for previous donations and NO compensation will be provided to the player or to any re-created nation. Charge-backs on donations are grounds for removal from the game.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class='index_message_spacer'></td>
	</tr>
	<tr>
		<td>
			<table class='index_message_box' cellpadding='0' cellspacing='0'>
				<tr>
					<td>
						<table class='index_message_box_mini_no_author' cellpadding='0' cellspacing='0'>
							<tr>
								<td class='index_box_center'>Intellectual Property</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class='index_message_shell'>
						<p class='index_message_body'>All intellectual property in the game, all rights and title in and to this website and service (including without limitation any user accounts, nations, in-game purchases and transactions, computer code, themes, stories, dialogue, catch phrases, concepts, logos, images, methods of operation, moral rights, and any related documentation) are privately owned in whole by Patrick MacMannis, the creator, developer, and administrator of International Conquest.<br /><br />
While some game concepts, techniques, ideas and other aspects may be similar to other online games, the original idea and concept behind International Conquest (originally International Warpath) has been in development for over 10 years as a board game idea. The internet has proven to be a far greater catalyst for the development of said game.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class='index_message_spacer'></td>
	</tr>
	<tr>
		<td>
			<table class='index_message_box' cellpadding='0' cellspacing='0'>
				<tr>
					<td>
						<table class='index_message_box_mini_no_author' cellpadding='0' cellspacing='0'>
							<tr>
								<td class='index_box_center'>Warnings and Monitoring Site Activity</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class='index_message_shell'>
						<p class='index_message_body'>International Conquest makes use of warning systems both in-game and on the official forums. Nations in-game can view their warning level by viewing their extended nation display screen. Nations are given 3 warnings in-game and 5 warnings on the forums before they reach 100% at which time they are to be banned from the game and on the forums. Warnings will be issued when offensive language or content is used, cheating or suspected cheating, war slot filling, spy slot filling, or any other form of violation of these terms and conditions or the official forums terms and conditions. International Conquest reserves the right to suspend/delete/ban a user on the spot, and therefore bypass the in-game or forum warning system, if it is deemed necessary by the moderation/administration staff. Bans from the game and forums are permanent.<br /><br />
International Conquest has the right, but not the obligation, to monitor any activity and content associated with this website. We may investigate any reported violation of these conditions or complaints and take any action that we deem appropriate (which may include, but is not limited to, issuing warnings, suspending, terminating or attaching conditions to your access and/or removing any materials from this website).</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class='index_message_spacer'></td>
	</tr>
	<tr>
		<td>
			<table class='index_message_box' cellpadding='0' cellspacing='0'>
				<tr>
					<td>
						<table class='index_message_box_mini_no_author' cellpadding='0' cellspacing='0'>
							<tr>
								<td class='index_box_center'>Servers, Security and other Technical Difficulties</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class='index_message_shell'>
						<p class='index_message_body'>International Conquest is not liable in any way for server breakdown, faulty programming etc. The occurrence of any disadvantages for a user resulting from server breakdowns, faulty programming etc. does not entitle the user to claim the recovery of his account's status before the occurrence or the recovery of any donation bonuses applied before the occurrence took place.<br /><br />
International Conquest shall not be liable for any delay or failure to perform resulting from causes outside the reasonable control of International Conquest including without limitation any failure to perform hereunder due to unforeseen circumstances or cause beyond our control such as acts of God, war, terrorism, riots, embargoes, acts of civil or military authorities, fire, floods, accidents, strikes, or shortages of transportation facilities, fuel, energy, labor or materials.<br /><br />
You are responsible for maintaining a secure password to the site. You are responsible for the confidentiality of the password and account, and are fully responsible for all activities that occur under your password or account. You agree to make efforts to use a complex password with unique characters and numbers and understand the importance of keeping your International Conquest password different from all other websites that you visit. You agree to exit from your account at the end of each session using the logout link provided. You alone are fully liable for any loss or damage arising from your failure to comply with these security measures.<br /><br />
When you submit information to International Conquest, you grant the site and its administrator a non-exclusive, royalty-free, worldwide, perpetual license to reproduce, distribute, transmit, and publicly display such content. You agree not to attempt to sell or profit from any such content or information via private exchanges without administrative consent. Such private exchanges that are forbidden include nation selling, giving away accounts, exchanging International Conquest items for items on other websites, foreign aid for real life money outside of the official International Conquest donation system. If you are found to be involved in any way in real life private exchanges from International Conquest you will be removed from this website. Players are allowed to perform donation deals with other nations only through the official in-game donation system. By participating in donation deals with other players you agree that International Conquest is not responsible for the transaction or the outcome of the deal.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class='index_message_spacer'></td>
	</tr>
	<tr>
		<td>
			<table class='index_message_box' cellpadding='0' cellspacing='0'>
				<tr>
					<td>
						<table class='index_message_box_mini_no_author' cellpadding='0' cellspacing='0'>
							<tr>
								<td class='index_box_center'>Age, Liability, Disputes and Legal Disputes</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class='index_message_shell'>
						<p class='index_message_body'>By using this site you certify that you are at least thirteen years of age or have your legal guardian’s permission to use this website and/or its forums. Where your country of access imposes laws regulating this web site, you agree to use your best efforts to comply with them. If you participate in International Conquest it is up to you to check the laws applicable in your area. We are not liable for any damages caused by the use of International Conquest or the official International Conquest forums.<br /><br />
USE THE WEB SITE AT YOUR OWN RISK. We will not be liable for any damages for any reason. THIS WEB SITE IS PROVIDED TO YOU "AS IS," WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED.<br /><br />
If there is a dispute between participants on this site, International Conquest is under no obligation to become involved. In the event that you have a dispute with one or more users, you hereby release, International Conquest, their officers, employees, agents and successors in rights from claims, demands and damages (actual and consequential) of every kind or nature, known or unknown, suspected and unsuspected, disclosed and undisclosed, arising out of or in any way related to such disputes and / or our service.<br /><br />
YOU AGREE TO BE FULLY RESPONSIBLE FOR (AND FULLY INDEMNIFY US AGAINST) ALL CLAIMS, LIABILITY, DAMAGES, LOSSES, COSTS AND EXPENSES, INCLUDING LEGAL FEES, SUFFERED BY US AND ARISING OUT OF ANY BREACH OF THE CONDITIONS BY YOU OR ANY OTHER LIABILITIES ARISING OUT OF YOUR USE OF THIS WEBSITE, OR THE USE BY ANY OTHER PERSON ACCESSING THIS WEBSITE USING YOUR PC OR INTERNET ACCESS ACCOUNT.<br /><br />
If you and International Conquest are unable to resolve a dispute through informal negotiations, either you or International Conquest may elect to have the dispute finally and exclusively resolved by binding arbitration. Any election to arbitrate by one party shall be final and binding on the other. YOU UNDERSTAND THAT ABSENT THIS PROVISION, YOU WOULD HAVE THE RIGHT TO SUE IN COURT AND HAVE A JURY TRIAL. The arbitration shall be commenced and conducted under the Commercial Arbitration Rules of the American Arbitration Association ("AAA") and, where appropriate, the AAA’s Supplementary Procedures for Consumer Related Disputes ("AAA Consumer Rules"), both of which are available that the AAA website www.adr.org.<br /><br />
YOU ACKNOWLEDGE THAT WE CANNOT GUARANTEE AND THEREFORE SHALL NOT BE IN ANY WAY RESPONSIBLE FOR THE SECURITY OR PRIVACY OF THIS WEBSITE AND ANY INFORMATION PROVIDED TO OR TAKEN FROM THIS WEBSITE BY YOU. We will not voluntarily share your personal data with anyone and we will do our best to protect it at all times.<br /><br />
If any part of these Conditions shall be deemed unlawful, void or for any reason unenforceable, then that provision shall be deemed to be severable from these terms and conditions and shall not affect the validity and enforceability of any of the remaining provisions of these terms and conditions.<br /><br />
We reserve the right to alter, update, and change these terms and conditions from time to time, and your use of this website (or any part of) following such change shall be deemed to be your acceptance of such change. Users will be notified of an update to these terms and conditions when they login to the game. If you do not agree to any change made to these conditions then you must immediately stop using this website.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</td>
</tr>
</table>
<?php
include ('../footer.php');
?>