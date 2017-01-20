<?php
function multi_check($IP_block, $IP_total, $multiple_nations, $mod_admin)
{
	if($ID_block == 1 && $IP_total > 1)
	{
		die("You cannot have multiple nations. Delete your former nation to create a new one.");
	}
	elseif($ID_block < 0 && $multiple_nations == 1)
	{
		die("Multiple nations are currently not allowed. Delete your former nation to create a new one.");
	}
	elseif($ID_block == 0 && $multiple_nations == 2 && $mod_admin != 2)
	{
		die("Multiple Nations are not allowed at this time. Delete your former nation to create a new one.");
	}
	else
	{
	}
}

function new_nations($new_nations)
{
	if($new_nations == 1)
	{
		die("No new nations are allowed at this time.");
	}
	else
	{
	}
}

function site_online($site_online, $mod_admin)
{
	if($site_online == 1)
	{
		die("Sorry, but the site is currently offline. Please try again later.");
	}
	elseif($site_online == 2 && $mod_admin != 2)
	{
		die("Sorry, but the site is currently under admin inspection. Please try again later.");
	}
	else
	{
	}
}

function site_online_forgot($site_online)
{
	if($site_online != 0)
	{
		die("Sorry, but the site is currently offline. Please try again later.");
	}
	else
	{
	}
}
?>