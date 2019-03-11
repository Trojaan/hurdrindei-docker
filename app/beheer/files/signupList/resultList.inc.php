<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

  $where = '';

  //$_GET['year'] = isset($_GET['year']) ? $_GET['year'] : current($years);

	$sql = "SELECT	
              *
            FROM
              `[PREFIX]_persons`
            WHERE
              `personID` = ".(int)$_GET['personID'].";";

  $res = Xmysql_query($sql);

  $row = mysql_fetch_assoc($res);
?>

<h1>Resultaat overzicht: </h1>

  <table id="topInput">
    <tr>
      <td>Naam:</td>
      <td><?= stripslashes($row['firstName']) . (isset($_POST['middleName']) ? ' '.stripslashes($_POST['middleName']) : '') . ' ' . stripslashes($row['lastName'])?></td>
    <tr>
    <tr>
      <td>Adres:</td>
      <td><?= stripslashes($row['street']) . ' ' . stripslashes($row['houseNr']) ?></td>
    <tr>
    <tr>
      <td>Postcode / Plaats:</td>
      <td><?= stripslashes($row['postalcode']) . ' ' . stripslashes($row['place']) ?></td>
    <tr>
    <tr>
      <td>Geslacht:</td>
      <td><?=$row['gender']?></td>
    <tr>
    <tr>
      <td>Geboortedatum:</td>
      <td><?= stripslashes($row['datebirth']) ?></td>
    <tr>
<?php
if($row['email'] != '') {
?>
    <tr>
      <td>Email:</td>
      <td><?= stripslashes($row['email']) ?></td>
    <tr>
<?php
  }
?>
  </table>

<?php
  
  $sql = "SELECT
              *
            FROM
              `[PREFIX]_results`
           WHERE
              `personID` = ".(int)$_GET['personID']."
           ORDER BY
              `year` DESC
         ";

?>
<table id="tableOverview">
	<tr class="overviewTop">
    <td></td>
    <td>Afstand</td>
    <td>Medaille</td>
    <td>StartNr</td>
    <td>Tijd</td>
    <td>Jaar</td>
		<td style="width: 55px">&nbsp;</td>
	</tr>
	
<?php	
	$i = 0;
  $r = 1;

	$res = Xmysql_query($sql);

	if(mysql_num_rows($res) == 0) {

    if(isset($_POST['searchSubmit']))
      echo '<tr><td colspan="4">Geen resultaten gevonden, wilt u een nieuw persoon toevoegen? <a href="index.php?page=persons&action=edit&personID=0&act=result">Ja</a> <a href="index.php?page=signupList">Nee</a></td></tr>';
    else
		  echo '<tr><td colspan="3">Er staan geen personen in de database.</td></tr>';

	}else {
		
		while($row = mysql_fetch_assoc($res)) {
			$i++;
			if($i == 2) {
				$color = '#f2f2f2';
				$i = 0;
			}
			else 
				$color = '#e7e7e7';

      if($row['medal'] == '')
        $row['medal'] = 'Onbekend';

?>
			<tr style="cursor: pointer; background-color: <?= $color ?>" onclick="window.location='index.php?page=signupList&amp;action=edit&amp;personID=<?= (int)$row['personID'] ?>&amp;year=<?=$_GET['year']?>'">
				<td><?=$r?></td>
        <td><?= stripslashes($row['distanceFixed'])?></td>
        <td><?= ucfirst(stripslashes($row['medal']))?></td>
        <td><?= stripslashes($row['startNumber'])?></td>
        <td><?= stripslashes($row['timeFixed'])?></td>
        <td><?= stripslashes($row['year'])?></td>
				<td>
			   <a href="index.php?page=signupList&amp;action=edit&amp;personID=<?= (int)$row['personID'] ?>&amp;resultID=<?=(int)$row['resultID']?>"><img src="gfx/edit_item.png" alt="" />
         <a href="index.php?page=signupList&amp;action=del&amp;resultID=<?= (int)$row['resultID'] ?>"><img src="gfx/trash.png" alt="" />
				</td>
			</tr>
<?php	
      $r++;
		} 
	}
?>

</table>

<a href="index.php?page=signupList&amp;action=edit&amp;personID=<?=(int)$_GET['personID']?>">Resultaat toevoegen</a>

<?php include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); ?>