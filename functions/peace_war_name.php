<?php
function peace_war_name($peace_war)
{
// Put the $peace_war to an english name
	if($peace_war == 1)
	{
		$peace_war = "Peace";
	}
	else
	{
		$peace_war = "War";
	}

	return $peace_war;
}

function peace_war_image($peace_war)
{
// Put the $peace_war to an image
	if($peace_war == 1)
	{
		$peace_war = "<img src='images/icons/peace.png' alt='Peace' title='Peace -- I prefer living subjects.' />";
	}
	else
	{
		$peace_war = "<img src='images/icons/war.png' alt='War' title='War -- Bring it on, world!' />";
	}

	return $peace_war;
}
?>