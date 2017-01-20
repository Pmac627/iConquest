<?php
function error_fubar($error_ID)
{
	switch ($error_ID)
	{
		case "1":
		$error_message = "Some fields were missing.";
		break;
		case "2":
		$error_message = "There is a problem with the aid transaction data.";
		break;
		case "3":
		$error_message = "Sorry, you can only send as much as $500,000.00 in monetary aid!";
		break;
		case "4":
		$error_message = "Sorry, you can only send as much as 10.00 in tech aid!";
		break;
		case "5":
		$error_message = "Sorry, you can only send as much as 0.5 in capital aid!";
		break;
		case "6":
		$error_message = "Sorry, you can only send as much as 250 infantry!";
		break;
		case "7":
		$error_message = "You cant aid yourself!";
		break;
		case "8":
		$error_message = "You cant have more than one aid transaction between the two of you!";
		break;
		case "9":
		$error_message = "You have too many aid contracts and/or offers right now.";
		break;
		case "10":
		$error_message = "They have too many aid contracts and/or offers right now.";
		break;
		case "11":
		$error_message = "There is a problem with the capital transaction data.";
		break;
		case "12":
		$error_message = "You have exceeded the limit of 1 capital bought per transaction.";
		break;
		case "13":
		$error_message = "You have not entered an amount.";
		break;
		case "14":
		$error_message = "You do not have enough money in your nation's treasury to purchase any capital at this time.";
		break;
		case "15":
		$error_message = "You can only purchase capital in half units (0.5) and cannot exceed 1 full unit.";
		break;
		case "16":
		$error_message = "Your input was invalid.";
		break;
		case "17":
		$error_message = "Your old password did not match the one in our records.";
		break;
		case "18":
		$error_message = "Your new passwords did not match.";
		break;
		case "19":
		$error_message = "There is a problem with the history data.";
		break;
		case "20":
		$error_message = "There is a problem with the civil works purchase transaction data.";
		break;
		case "21":
		$error_message = "You do not have enough money in your nation's treasury to purchase any civil works at this time.";
		break;
		case "22":
		$error_message = "You have not selected a civil work to purchase";
		break;
		case "23":
		$error_message = "You cannot purchase that civil work.";
		break;
		case "24":
		$error_message = "There is a problem with the region data.";
		break;
		case "25":
		$error_message = "There is a problem with the title data.";
		break;
		case "26":
		$error_message = "There is a problem with the capitol data.";
		break;
		case "27":
		$error_message = "That user does not exist in our database.";
		break;
		case "28":
		$error_message = "Incorrect password.";
		break;
		case "29":
		$error_message = "There is a problem with the infrastructure transaction data.";
		break;
		case "30":
		$error_message = "You have exceeded the limit of 20 infrastructure bought per transaction.";
		break;
		case "31":
		$error_message = "You do not have enough money in your nation's treasury to purchase any infrastructure at this time.";
		break;
		case "32":
		$error_message = "There is a problem with the land transaction data.";
		break;
		case "33":
		$error_message = "You have exceeded the limit of 5 land bought per transaction.";
		break;
		case "34":
		$error_message = "You do not have enough money in your nation's treasury to purchase any land at this time.";
		break;
		case "35":
		$error_message = "That user does not exist in our database.";
		break;
		case "36":
		$error_message = "You did not fill in a required field.";
		break;
		case "37":
		$error_message = "That nation does not exist!";
		break;
		case "38":
		$error_message = "There is a problem with the nation settings data.";
		break;
		case "39":
		$error_message = "That nation name is already in use.";
		break;
		case "40":
		$error_message = "You did not select a resource set!";
		break;
		case "41":
		$error_message = "You already have a nation!";
		break;
		case "42":
		$error_message = "You already have a nation, but you are currently banned.";
		break;
		case "43":
		$error_message = "Your nation was deleted.";
		break;
		case "44":
		$error_message = "There is no such message.";
		break;
		case "45":
		$error_message = "You cannot view this message. It isn't yours!";
		break;
		case "46":
		$error_message = "You cannot view this message. You didn't send it!";
		break;
		case "47":
		$error_message = "That ruler does not exist.";
		break;
		case "48":
		$error_message = "You cannot send a message to yourself!";
		break;
		case "49":
		$error_message = "There is a problem with the armor purchase data.";
		break;
		case "50":
		$error_message = "You do not have enough money in your nation's treasury to purchase that many armor units at this time.";
		break;
		case "51":
		$error_message = "There is a problem with the infantry purchase data.";
		break;
		case "52":
		$error_message = "You do not have enough money in your nation's treasury to purchase that many infantry at this time.";
		break;
		case "53":
		$error_message = "That username already exists.";
		break;
		case "54":
		$error_message = "The passwords you entered did not match.";
		break;
		case "55":
		$error_message = "There is a problem with the technology transaction data.";
		break;
		case "56":
		$error_message = "You have exceeded the limit of 10 technology bought per transaction.";
		break;
		case "57":
		$error_message = "You do not have enough money in your nation's treasury to purchase any technology at this time.";
		break;
		case "58":
		$error_message = "What trade are you trying to accept?";
		break;
		case "59":
		$error_message = "There is a problem with the trade transaction data.";
		break;
		case "60":
		$error_message = "Trying to accept a trade <strong>YOU</strong> sent is cheating and has been logged!<br />Expect a PM shortly detailing the possiblity of a temp ban.";
		break;
		case "61":
		$error_message = "You can't accept a trade for someone else!";
		break;
		case "62":
		$error_message = "What trade are you trying to cancel?";
		break;
		case "63":
		$error_message = "What trade are you trying to offer?";
		break;
		case "64":
		$error_message = "You cannot trade with yourself!";
		break;
		case "65":
		$error_message = "You cant have more than one trade transaction between the two of you!";
		break;
		case "66":
		$error_message = "You have too many trade contracts and/or offers right now.";
		break;
		case "67":
		$error_message = "They have too many trade contracts and/or offers right now.";
		break;
		case "68":
		$error_message = "There is a problem with the war peace accept data.";
		break;
		case "69":
		$error_message = "You cant accept peace for yourself!";
		break;
		case "70":
		$error_message = "What trade are you trying to accept?";
		break;
		case "71":
		$error_message = "There is a problem with the war declaration data.";
		break;
		case "72":
		$error_message = "You cant declare on yourself!";
		break;
		case "73":
		$error_message = "You cant have more than one war between the two of you!";
		break;
		case "74":
		$error_message = "You have too many aggressive wars right now.  Your troops need a break.";
		break;
		case "75":
		$error_message = "There is a problem with the ground unit deploy data.";
		break;
		case "76":
		$error_message = "You cannot deploy that many infantry!";
		break;
		case "77":
		$error_message = "You cannot deploy that much armor!";
		break;
		case "78":
		$error_message = "You cannot leave less than 0 infantry of any type at home!";
		break;
		case "79":
		$error_message = "There is no war.";
		break;
		case "80":
		$error_message = "The war is over.";
		break;
		case "81":
		$error_message = "The war has ended.";
		break;
		case "82":
		$error_message = "The war has entered a state of defacto ceasefire.";
		break;
		case "83":
		$error_message = "The war has ended in a state of defacto peace.";
		break;
		case "84":
		$error_message = "There is a problem with the ground warfare data.";
		break;
		case "85":
		$error_message = "You arent involved or you tried to pass a fake ID!";
		break;
		case "86":
		$error_message = "You do not have enough deployed units to make that move!";
		break;
		case "87":
		$error_message = "You do not have enough fresh units for that operation!";
		break;
		case "88":
		$error_message = "What trade are you trying to accept?";
		break;
		case "89":
		$error_message = "There is a problem with the war peace accept data.";
		break;
		case "90":
		$error_message = "You cant offer peace to yourself!";
		break;
		case "91":
		$error_message = "You cannot offer peace for this war.";
		break;
	}

	return $error_message;
}
?>