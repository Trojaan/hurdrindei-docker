<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');
?>

<h1>Pagina overzicht</h1>
Onderstaand overzicht toont de pagina's die u via het beheer kunt bewerken. Door op het bewerken icoon te klikken <img src="gfx/edit_item.png" alt="" /> komt u op de pagina met de tekst editor.

<div id="breadCrumbs">
	<a href="index.php?page=text">Pagina overzicht</a> /
<?php
foreach ( breadCrumb ( $_GET [ 'parentID' ] ) as $bc )
{
?>
	<a href="index.php?page=text&amp;parentID=<?= $bc [ 'pageID' ] ?>"><?= stripslashes ( $bc [ 'title' ] ); ?> /</a>
<?php
}
?>
</div>

<h2>Pagina overzicht</h2>
<table id="tableOverview">
	
	<?php	
	$i = 0;	
	
	if($_GET['parentID'] > 0) {
		$sql = "SELECT	
					`pageID`,
					`parentID`,
					`title`
				FROM
					`[PREFIX]_content`
				WHERE
					`parentID` = '".(int)$_GET['parentID']."'
        AND
          `is_text` = '1'
				ORDER BY `pageID` ;";
	}
	else {

		$sql = "SELECT	
					`pageID`,
					`parentID`,
					`title`
				FROM
					`[PREFIX]_content`
				WHERE
					`parentID` = '0'
        AND
          `is_text` = '1'
				ORDER BY `pageID` ;";
	}
	
	$res = Xmysql_query($sql);
	while($row = mysql_fetch_assoc($res)) {
	
	$i++;
	if($i == 2) {
		$color = '#f2f2f2';
		$i = 0;
	}
	else 
		$color = '#e7e7e7';
	
	
	$sqlParent = "SELECT	
					`parentID`
				FROM
					`[PREFIX]_content`
				WHERE 
					`parentID` = '".(int)$row['pageID']."' ;";

	$resParent = Xmysql_query($sqlParent);
	?>

	<tr style="background-color: <?= $color ?>">
		<td style="width: 580px;">
			<?= stripslashes($row['title']); ?>
		<td>
			<a href="index.php?page=text&action=edit&pageID=<?= $row['pageID'] ?>"><img src="gfx/edit_item.png" alt="" />
      <!--<a href="index.php?page=text&action=del&pageID=<?= $row['pageID'] ?>"><img src="gfx/trash.png" alt="" />-->
		</td>
	</tr>

<?php } ?>

</table>
<?php
if($_GET['parentID'] > 0) {
 ?>
  <a href="?page=text&amp;action=new&amp;parentID=<?= $_GET['parentID']; ?>" class="link">Nieuwe pagina toevoegen</a>
 <?php
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>