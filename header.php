<?php /** header.php **/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $page_title_name; ?> - International Conquest</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $route; ?>stylesheets/styles.css" />
	<link rel="shortcut icon" href="<?php echo $route; ?>favicon.ico" />
	<meta http-equiv='content-type' content='text/html; charset=UTF-8' />
	<meta name="description" content="iC - International Conquest is a browser-based nation simulation game" />
	<meta name="keywords" content="iC, International Conquest, Nation, Broswer-based game, Browser based game, nation building, country building" />
	<?php echo $meta_restrict; ?>
</head>
<body>
<div class='whole'>
	<div class='header'>
		<table class='header'>
			<tr>
				<td class='header_logo'><a href='index.php' title='International Conquest'><img src='/images/header2.PNG' alt='iC Logo' title='International Conquest' width='500' height='100' id='link_img' /></a></td>
				<td class='header_info'><p>International Conquest -- 
					<?php   echo $ic_version_marker; ?>
					<br />
					<?php   $raw_header_time = gmdate('U');
							$formatted_header_time = date('n/j/Y - g:i a', $raw_header_time);
							echo $formatted_header_time; ?>
					</p>
				</td>
			</tr>
		</table>
	</div>