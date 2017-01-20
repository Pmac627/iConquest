<?php
/** sub-dir index.php **/
$page_title_name = '404 Error Page';
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

include ('header.php');
?>
<table align='center' border='0'>
	<tr valign='top'>
		<td>
			<tr>
				<td>
					<table align='center' valign='middle'>
						<h1>404 Error!</h1>
						I'm sorry, but that page does not exist!<br />
						Please visit the iC <a href='/game/index.php'>homepage</a>.
					</table>
				</td>
			</tr>
		</td>
	</tr>
</table>
<?php
include ('footer.php');
?>