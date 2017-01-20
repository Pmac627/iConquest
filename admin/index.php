<?php
/** sub-dir index.php **/
$meta_restrict = '<meta name="robots" content="noindex, nofollow" />';

include ('../header.php');
?>
<table align='center' border='0'>
	<tr valign='top'>
		<td>
			<tr>
				<td>
					<table align='center' valign='middle'>
						<h1>ACCESS DENIED!</h1>
					</table>
				</td>
			</tr>
		</td>
	</tr>
</table>
<?php
include ('../footer.php');

echo "<META HTTP-EQUIV='Refresh' Content='0; URL=../login.php'>";
?>