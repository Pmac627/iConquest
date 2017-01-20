<?php
// Sanity Check function
function sanity_check($string, $type, $length)
{
	$type = 'is_'.$type;

	if(!$type($string))
	{
		// Is the string the right type?
		return FALSE;
	}
	elseif(strlen($string) > $length)
	{
		// then we check how long the string is
		return FALSE;
	}
	else
	{
		// if all is well, we return TRUE
		return TRUE;
	}
}

function number_check($num, $length)
{
	if($num >= 0 && strlen($num) == $length)
    {
		return TRUE;
    }
	else
	{
		return FALSE;
	}
}

function email_check($email)
{
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}
?>