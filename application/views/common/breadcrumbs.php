<!-- START BREADCRUMB -->
<table cellpadding="0" cellspacing="0" border="0" style="width:100%">
	<tr>
		<td id="breadcrumb">
			<a href="/">Project Home</a>
			<?php
				foreach($breadcrumbs as $bc) {
					echo '&nbsp;&#8250;&nbsp;';
					if ($bc["url"] != "") {
						echo '<a href="'.$bc["url"].'">'.$bc["title"].'</a>';
					} else {
						echo $bc["title"];
					}
				}
			?>
		</td>
	</tr>
</table>
<!-- END BREADCRUMB -->