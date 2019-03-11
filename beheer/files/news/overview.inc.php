<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');
?>

<h1>Nieuws overzicht</h1>
Onderstaand overzicht toont de huidige nieuwsberichten. Door op het bewerken icoon te klikken <img src="gfx/text_edit.png" alt="" /> komt u op de pagina met de tekst editor.


<table id="tableOverview">
	<tr class="overviewTop">
		<td>Nieuwsberichten</td>
		<td colspan="2">Datum</td>
	</tr>
	
	<?php	
	$i = 0;	
	$sql = "SELECT	
				`newsID`,
				`title`,
				`date`
			FROM
				`[PREFIX]_news`
			ORDER BY `date` DESC;";

	$res = Xmysql_query($sql);

	if(mysql_num_rows($res) == 0) 
		echo '<tr><td colspan="3">Er staan geen nieuwsberichten in de database.</td></tr>';
	
	else {
		
		while($row = mysql_fetch_assoc($res)) {
		
			$i++;
			if($i == 2) {
				$color = '#f2f2f2';
				$i = 0;
			}
			else 
				$color = '#e7e7e7';
			?>

			<tr style="background-color: <?= $color ?>">
				<td style="width: 400px;"><?= $row['title'] ?></td>
				<td style="width: 140px;"><?= $row['date'] ?></td>
				<td>
					<a href="index.php?page=news&action=edit&newsID=<?= $row['newsID'] ?>"><img src="gfx/text_edit.png" alt="" /></a>
					<a href="index.php?page=news&action=del&newsID=<?= $row['newsID'] ?>"><img src="gfx/delete.png" alt="" /></a>
				</td>
			</tr>
<?php	
		} 
	}
?>

</table>

<?php include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); ?>