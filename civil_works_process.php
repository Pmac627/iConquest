<?php
/** civil_works.php **/

// Database connection function
require ('functions/database_connection_function.php');
database_connection();
include ('functions/form_input_check_functions.php');

// If civil works purchase form is submitted
		if (isset($_POST['submit']))
		{
			$_POST['URL_cw_wanted'] = strip_tags($_POST['URL_cw_wanted']);
			$_POST['cw_price'] = strip_tags($_POST['cw_price']);
			$_POST['cw_quantity'] = strip_tags($_POST['cw_quantity']);
			$_POST['ID'] = strip_tags($_POST['ID']);
			$_POST['treasury'] = strip_tags($_POST['treasury']);

			if(isset($_POST['URL_cw_wanted'], $_POST['cw_price'], $_POST['cw_quantity'], $_POST['ID'], $_POST['treasury']))
			{
				if(sanity_check($_POST['URL_cw_wanted'], 'string', 17) != FALSE && sanity_check($_POST['cw_price'], 'numeric', 6) != FALSE && sanity_check($_POST['cw_quantity'], 'numeric', 1) != FALSE && sanity_check($_POST['ID'], 'numeric', 7) != FALSE && sanity_check($_POST['treasury'], 'string', 15) != FALSE)
				{
					$URL_cw_wanted = mysql_real_escape_string($_POST['URL_cw_wanted']);
					$cw_price = mysql_real_escape_string($_POST['cw_price']);
					$cw_quantity = mysql_real_escape_string($_POST['cw_quantity']);
					$ID = mysql_real_escape_string($_POST['ID']);
					$treasury = mysql_real_escape_string($_POST['treasury']);
				}
				else
				{
					// Redirect them to the error page
					header("Location: error_page.php?error=20");
				}
			}
			else
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=1");
			}

			// Add or subtract new capital from old
			$new_cw_quantity = ($cw_quantity + 1);

			// Subtract new treasury from old
			$newtreasury = ($treasury - $cw_price);

			// Check to see if the requested purchase will bankrupt nation
			if($newtreasury < 0)
			{
				// Redirect them to the error page
				header("Location: error_page.php?error=21");
			}

			// Update the treasury total to the new one!
			$insert1 = "UPDATE nation_variables SET treasury='" . $newtreasury . "' WHERE ID='" . $ID . "'";
			$add_member1 = mysql_query($insert1);

			// Update the civil works total to the new one!
			$insert2 = "UPDATE civil_works SET " . $URL_cw_wanted . "='" . $new_cw_quantity . "' WHERE ID='" . $ID . "'";
			$add_member2 = mysql_query($insert2);

			// Then redirect them to the nation
			header("Location: civil_works.php");
		}
?>