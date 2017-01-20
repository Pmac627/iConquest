<?php
function database_connection()
{
	// Connects to your Database
	mysql_connect("localhost", "username", "password") or die(mysql_error());
	mysql_select_db("database") or die(mysql_error());
}
?>