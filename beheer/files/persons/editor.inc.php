<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if(isset($_POST['submit'])) {

  $date = explode('-',$_POST['datebirth']);

  $dateBirth = (strlen($_POST['datebirth']) > 0 ? date('Y-m-d H:i:s',mktime(0,0,0,$date['1'],$date['0'],$date['2'])) : '0000-00-00 00:00:00');

  if($_POST['personID'] > 0) {

    $sql = "UPDATE 
              `[PREFIX]_persons` 
            SET 
              `firstName` = '".addslashes($_POST['firstName'])."',
              `middleName` = '".addslashes($_POST['middleName'])."',
              `lastName` = '".addslashes($_POST['lastName'])."',
              `postalcode` = '".addslashes($_POST['postalcode'])."',
              `street` = '".addslashes($_POST['street'])."',
              `houseNr` = '".addslashes($_POST['houseNr'])."',
              `place` = '".addslashes($_POST['place'])."',
              `gender` = '".$_POST['gender']."',
              `datebirth` = '" . $dateBirth ."',
              `email` = '".addslashes($_POST['email'])."'
            WHERE
              `personID` = '".(int)$_POST['personID']."' ";
  } else {
    $sql = "INSERT 
              `[PREFIX]_persons` 
            SET 
              `firstName` = '".addslashes($_POST['firstName'])."',
              `middleName` = '".addslashes($_POST['middleName'])."',
              `lastName` = '".addslashes($_POST['lastName'])."',
              `postalcode` = '".addslashes($_POST['postalcode'])."',
              `street` = '".addslashes($_POST['street'])."',
              `houseNr` = '".addslashes($_POST['houseNr'])."',
              `place` = '".addslashes($_POST['place'])."',
              `gender` = '".$_POST['gender']."',
              `datebirth` = '". $dateBirth ."',
              `email` = '".addslashes($_POST['email'])."'";
  }

  if ($res = Xmysql_query($sql) > 0) {
    $insertID = mysql_insert_id();
    if($_GET['act'] == 'result') {
      echo 'Persoon succesvol toegevoegd, wilt u een resultaat toevoegen voor deze persoon? </br>';
      echo '<a href="index.php?page=signupList&action=edit&personID='.(int)$insertID.'&resultID=0">Ja</a> <a href="index.php?page=persons">Nee</a>';
    } else {
      header ('location: index.php?page=persons');
    }
  } else
    echo 'Er ging iets fout';

} else {

	$sql = "SELECT	
              *,
              DATE_FORMAT(`datebirth`, '%d-%m-%Y') AS `datebirth`
            FROM
              `[PREFIX]_persons`
            WHERE
              `personID` = '".(int)$_GET['personID']."' ;";

	$res = Xmysql_query($sql);
	$row = mysql_fetch_assoc($res);
	?>

	<h1>Persoon bewerken</h1>

	<form name="content" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
		<table id="topInput">
			<tr>
				<td>Voornaam:</td>
				<td><input type="text" name="firstName" value="<?= stripslashes($row['firstName']) ?>" /></td>
			<tr>
      <tr>
				<td>Tussenvoegsel:</td>
				<td><input type="text" name="middleName" value="<?= stripslashes($row['middleName']) ?>" /></td>
			<tr>
      <tr>
				<td>Achternaam:</td>
				<td><input type="text" name="lastName" value="<?= stripslashes($row['lastName']) ?>" /></td>
			<tr>
      <tr>
				<td>Postcode:</td>
				<td><input type="text" name="postalcode" value="<?= stripslashes($row['postalcode']) ?>" /></td>
			<tr>
      <tr>
				<td>Straat:</td>
				<td><input type="text" name="street" value="<?= stripslashes($row['street']) ?>" /></td>
			<tr>
      <tr>
				<td>Huisnummer:</td>
				<td><input type="text" name="houseNr" value="<?= stripslashes($row['houseNr']) ?>" /></td>
			<tr>
      <tr>
				<td>Plaats:</td>
				<td><input type="text" name="place" value="<?= stripslashes($row['place']) ?>" /></td>
			<tr>
      <tr>
				<td>Geslacht:</td>
				<td>
          <select name="gender">
            <option value="m" <?=$row['gender'] == 'm' ? 'selected="selected"' : ''?>>Man</option>
            <option value="v" <?=$row['gender'] == 'v' ? 'selected="selected"' : ''?>>Vrouw</option>
          </select>
        </td>
			<tr>
      <tr>
				<td>Geboortedatum:</td>
				<td><input type="text" name="datebirth" value="<?= stripslashes($row['datebirth']) ?>" /></td>
			<tr>
      <tr>
				<td>Email:</td>
				<td><input type="text" name="email" value="<?= stripslashes($row['email']) ?>" /></td>
			<tr>
    </table>
		<input type="hidden" name="personID" value="<?= $row['personID'] ?>" />
		<input type="submit" name="submit" value="opslaan" class="button" />
	</form>

<?php 
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>