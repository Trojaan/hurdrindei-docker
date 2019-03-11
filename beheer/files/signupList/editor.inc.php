<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if(isset($_POST['submit'])) {

  if($_POST['resultID'] > 0) {

    $_POST['timeHours'] = isset($_POST['timeHours']) ? $_POST['timeHours'] : '00';
    $_POST['timeMinutes'] = isset($_POST['timeMinutes']) ? $_POST['timeMinutes'] : '00';
    $_POST['timeSeconds'] = isset($_POST['timeSeconds']) ? $_POST['timeSeconds'] : '00';

    $sql = "UPDATE 
              `[PREFIX]_results` 
            SET 
              `distanceFixed` = '".addslashes($_POST['distance'])."',
              `medal` = '".addslashes($_POST['medal'])."',
              `startNumber` = ".(int)$_POST['startNumber'].",
              `timeFixed` = '".$_POST['timeHours'].':'.$_POST['timeMinutes'].':'.$_POST['timeSeconds']."',
              `year` = ".(int)$_POST['year']."
            WHERE
              `resultID` = '".(int)$_GET['resultID']."'";
  } else {
    $sql = "INSERT INTO 
              `[PREFIX]_results` 
            SET
              `personID` = ".(int)$_POST['personID'].",
              `distanceFixed` = '".addslashes($_POST['distance'])."',
              `medal` = '".addslashes($_POST['medal'])."',
              `startNumber` = ".(int)$_POST['startNumber'].",
              `timeFixed` = '".$_POST['timeHours'].':'.$_POST['timeMinutes'].':'.$_POST['timeSeconds']."',
              `year` = ".(int)$_POST['year']."";
  }
  if ($res = Xmysql_query($sql) > 0) {
    header ('location: index.php?page=signupList');
  }else
    echo 'Er ging iets fout';

} else {

  if(isset($_GET['resultID']) && $_GET['resultID'] > 0)
  {
    $sql = "SELECT
                R.`distanceFixed` AS `distance`,
                R.`startNumber`,
                R.`medal`,
                R.`year`,
                DATE_FORMAT(R.`timeFixed`, '%H') AS `timeHours`,
                DATE_FORMAT(R.`timeFixed`, '%i') AS `timeMinutes`,
                DATE_FORMAT(R.`timeFixed`, '%s') AS `timeSeconds`
              FROM
                `[PREFIX]_results` AS R
              WHERE
                R.`resultID` = ".(int)$_GET['resultID'].";";

    $res = Xmysql_query($sql);
    $row = mysql_fetch_assoc($res);
  }

    $sql = "SELECT	
                *,
                DATE_FORMAT(`datebirth`, '%d-%m-%Y') AS `datebirth`
              FROM
                `[PREFIX]_persons`
              WHERE
                `personID` = ".(int)$_GET['personID'].";";
    
    $res = Xmysql_query($sql);
    $person = mysql_fetch_assoc($res);

  if($person['gender'] == 'm')
    $person['gender'] = 'Man';
  else if($person['gender'] == 'v')
    $person['gender'] = 'Vrouw';
  else {
    $person['gender'] = 'Onbekend';
  }

  $selYear = isset($row['year']) ? $row['year'] : date('Y');
	?>
	<h1>Deelname bewerken</h1>

	<form name="content" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
		<table id="topInput">
			<tr>
				<td>Naam:</td>
				<td><?= stripslashes($person['firstName']) . (isset($person['middleName']) ? ' '.stripslashes($person['middleName']) : '') . ' ' . stripslashes($person['lastName'])?></td>
			<tr>
      <tr>
				<td>Adres:</td>
				<td><?= stripslashes($person['street']) . ' ' . stripslashes($person['houseNr']) ?></td>
			<tr>
      <tr>
				<td>Postcode / Plaats:</td>
				<td><?= stripslashes($person['postalcode']) . ' ' . stripslashes($person['place']) ?></td>
			<tr>
      <tr>
				<td>Geslacht:</td>
				<td><?=$person['gender']?></td>
			<tr>
      <tr>
				<td>Geboortedatum:</td>
				<td><?= stripslashes($person['datebirth']) ?></td>
			<tr>
      <tr>
				<td>Email:</td>
				<td><?= stripslashes($person['email']) ?></td>
			<tr>
      <tr>
				<td>Jaar:</td>
				<td>
          <select name="year">
<?php
              for($i = 1987; $i < (date('Y') + 2); $i++ ) 
              {
?>
            <option value="<?=$i?>" <?=$selYear == $i ? 'selected="selected"' : ''?>><?=$i?></option>
<?php
              }
?>
          </select>
        </td>
			<tr>
      <tr>
				<td>Afstand:</td>
				<td>
          <select name="distance">
            <option value="1.50" <?=$row['distance'] == '1.5' ? 'selected="selected"' : ''?>>1.5</option>
            <option value="8.00" <?=$row['distance'] == '8.0' ? 'selected="selected"' : ''?>>8.0</option>
            <option value="14.50" <?=$row['distance'] == '14.5' ? 'selected="selected"' : ''?>>14.5</option>
            <option value="21.10" <?=$row['distance'] == '21.1' ? 'selected="selected"' : ''?>>21.1</option>
          </select>
        </td>
			<tr>
      <tr>
				<td>Medaille:</td>
				<td>
          <select name="medal">
            <option value="nee" <?=$row['medal'] == 'nee' ? 'selected="selected"' : ''?>>Nee</option>
            <option value="ja" <?=$row['medal'] == 'ja' ? 'selected="selected"' : ''?>>Ja</option>
          </select>
        </td>
			<tr>
      <tr>
				<td>Startnummer:</td>
				<td>
          <input type="text" name="startNumber" value="<?=$row['startNumber']?>" style="width: 90px;"/> 
        </td>
			<tr>
      <tr>
				<td>Tijd:</td>
				<td>
          <input type="text" class="time" name="timeHours" id="timeHours" onkeypress="movePointer(this,'timeMinutes')" maxlength="2" value="<?=$row['timeHours']?>" autofocus /> :
           <input type="text" class="time" name="timeMinutes" id="timeMinutes" onkeypress="movePointer(this,'timeSeconds')" maxlength="2" value="<?=$row['timeMinutes']?>"/> :
            <input type="text" class="time" name="timeSeconds" id="timeSeconds" maxlength="2" value="<?=$row['timeSeconds']?>"/>
        </td>
			<tr>
    </table>
		<input type="hidden" name="personID" value="<?= (int)$_GET['personID'] ?>" />
    <input type="hidden" name="resultID" value="<?= (int)$_GET['resultID'] ?>" />
    <input type="submit" name="submit" value="opslaan" class="button" />
	</form>

<?php 
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>