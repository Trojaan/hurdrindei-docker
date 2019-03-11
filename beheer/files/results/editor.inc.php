<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if(isset($_POST['submit'])) {

  if($_POST['personID'] > 0 && $_POST['year']) {

    $sql = "UPDATE 
              `[PREFIX]_results` 
            SET 
              `distance` = '".addslashes($_POST['distance'])."',
              `medal` = '".addslashes($_POST['medal'])."'
            WHERE
              `personID` = '".(int)$_POST['personID']."'
            AND
              `year` = ".$_POST['year']."";
  }

  if ($res = Xmysql_query($sql) > 0)
    header ('location: index.php?page=signupList'); 
  else
    echo 'Er ging iets fout';

} else {

	$sql = "SELECT	
              *,
              DATE_FORMAT(P.`datebirth`, '%d-%m-%Y') AS `datebirth`,
              R.`distance`,
              R.`medal`
            FROM
              `[PREFIX]_persons` AS P
            JOIN
              `[PREFIX]_results` AS R
            ON
              P.`personID` = R.`personID`
            WHERE
              R.`year` = ".(int)$_GET['year']."
            AND
              R.`resultID` = ".(int)$_GET['resultID'].";";
  //myPrint($sql);

	$res = Xmysql_query($sql);
	$row = mysql_fetch_assoc($res);

  if($row['gender'] == 'm')
    $row['gender'] = 'Man';
  else if($row['gender'] == 'v')
    $row['gender'] = 'Vrouw';
  else {
    $row['gender'] = 'Onbekend';
  }
	?>

	<h1>Deelname bewerken</h1>

	<form name="content" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
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
      <tr>
				<td>Email:</td>
				<td><?= stripslashes($row['email']) ?></td>
			<tr>
      <!--<tr>
				<td>Afstand:</td>
				<td>
          <select name="distance">
            <option value="1.5" <?=$row['distance'] == '1.5' ? 'selected="selected"' : ''?>>1.5</option>
            <option value="8.0" <?=$row['distance'] == '8.0' ? 'selected="selected"' : ''?>>8.0</option>
            <option value="14.5" <?=$row['distance'] == '14.5' ? 'selected="selected"' : ''?>>14.5</option>
            <option value="21.1" <?=$row['distance'] == '21.1' ? 'selected="selected"' : ''?>>21.1</option>
          </select>
        </td>
			<tr>
      <tr>
				<td>Medaile:</td>
				<td>
          <select name="medal">
            <option value="nee" <?=$row['medal'] == 'nee' ? 'selected="selected"' : ''?>>Nee</option>
            <option value="ja" <?=$row['medal'] == 'ja' ? 'selected="selected"' : ''?>>Ja</option>
          </select>
        </td>
			<tr>-->
      <tr>
        <td></td>
      </tr>
    </table>
		<input type="hidden" name="personID" value="<?= $row['personID'] ?>" />
		<input type="hidden" name="year" value="<?= $_GET['year'] ?>" />
		<input type="submit" name="submit" value="opslaan" class="button" />
	</form>

<?php 
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>