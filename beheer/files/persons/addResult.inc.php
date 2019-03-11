<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if(isset($_POST['submit'])) {

  $data = $_POST['signup'];

  if($data['personID'] > 0) {

    $data['timeHours'] = isset($data['timeHours']) ? $data['timeHours'] : '00';
    $data['timeMinutes'] = isset($data['timeMinutes']) ? $data['timeMinutes'] : '00';
    $data['timeSeconds'] = isset($data['timeSeconds']) ? $data
      ['timeSeconds'] : '00';

    $sql = "INSERT
              `[PREFIX]_results` 
            SET 
              `personID` = ".(int)$data['personID'].",
              `distance` = '".addslashes($data['distance'])."',
              `medal` = '".addslashes($data['medal'])."',
              `startNumber` = ".(int)$data['startNumber'].",
              `timeFixed` = '".$data['timeHours'].':'.$data['timeMinutes'].':'.$data['timeSeconds']."',
              `year` = YEAR(NOW())";
  }


  if ($res = Xmysql_query($sql) > 0)
    header ('location: ?page=persons'); 
  else
    echo 'Er ging iets fout';

}


  $sql = "SELECT	
              *,
              DATE_FORMAT(P.`datebirth`, '%d-%m-%Y') AS `datebirth`
            FROM
              `[PREFIX]_persons` AS P
            WHERE
              `personID` = ".(int)$_GET['personID'].";";


  $res = Xmysql_query($sql);
	$row = mysql_fetch_assoc($res);

  myPrint($row['gender']);

  if($row['gender'] == 'm')
    $row['gender'] = 'Man';
  else if($row['gender'] == 'v')
    $row['gender'] = 'Vrouw';
  else {
    $row['gender'] = 'Onbekend';
  }
	?>

	<h1>Resultaat toevoegen voor:</h1>

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
      <tr>
				<td>Afstand:</td>
				<td>
          <select name="signup[distance]">
            <option value="1.5" <?=$data['distance'] == '1.5' ? 'selected="selected"' : ''?>>1.5</option>
            <option value="8.0" <?=$data['distance'] == '8.0' ? 'selected="selected"' : ''?>>8.0</option>
            <option value="14.5" <?=$data['distance'] == '14.5' ? 'selected="selected"' : ''?>>14.5</option>
            <option value="21.1" <?=$data['distance'] == '21.1' ? 'selected="selected"' : ''?>>21.1</option>
          </select>
        </td>
			<tr>
      <tr>
				<td>Medaile:</td>
				<td>
          <select name="signup[medal]">
            <option value="nee" <?=$data['medal'] == 'nee' ? 'selected="selected"' : ''?>>Nee</option>
            <option value="ja" <?=$data['medal'] == 'ja' ? 'selected="selected"' : ''?>>Ja</option>
          </select>
        </td>
			<tr>
      <tr>
				<td>Startnummer:</td>
				<td>
          <input type="text" name="signup[startNumber]" value="<?=$data['startNumber']?>" style="width: 90px;"/> 
        </td>
			<tr>
      <tr>
				<td>Tijd:</td>
				<td>
          <input type="text" class="time" name="signup[timeHours]" id="timeHours" onkeyup="movePointer(this,'timeMinutes')" maxlength="2" value="<?=$data['timeHours']?>"/> : <input type="text" class="time" name="signup[timeMinutes]" id="timeMinutes" onkeyup="movePointer(this,'timeSeconds')" maxlength="2" value="<?=$data['timeMinutes']?>"/> : <input type="text" class="time" name="signup[timeSeconds]" id="timeSeconds" maxlength="2" value="<?=$data['timeSeconds']?>"/>
        </td>
			<tr>
    </table>
		<input type="hidden" name="signup[personID]" value="<?= $_GET['personID'] ?>" />
		<input type="hidden" name="signup[year]" value="<?= $_GET['year'] ?>" />
		<input type="submit" name="submit" value="opslaan" class="button" />
	</form>

<?php 

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>