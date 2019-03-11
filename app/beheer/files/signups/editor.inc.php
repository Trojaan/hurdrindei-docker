<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if(isset($_POST['submit'])) {

  $date = explode('-',$_POST['datebirth']);

  $showDate = $_POST['datebirth'];

  $_POST['datebirth'] = date('Y-m-d H:i:s',mktime(0,0,0,$date['1'],$date['0'],$date['2']));

  if($_POST['signUpID'] > 0) {

    $sql = "UPDATE 
              `[PREFIX]_signups` 
            SET 
              `firstName` = '".addslashes($_POST['firstName'])."',
              `middleName` = '".addslashes($_POST['middleName'])."',
              `lastName` = '".addslashes($_POST['lastName'])."',
              `postalCode` = '".addslashes($_POST['postalCode'])."',
              `street` = '".addslashes($_POST['street'])."',
              `houseNr` = '".addslashes($_POST['houseNr'])."',
              `place` = '".addslashes($_POST['place'])."',
              `gender` = '".addslashes($_POST['gender'])."',
              `datebirth` = '".addslashes($_POST['datebirth'])."',
              `email` = '".addslashes($_POST['email'])."',
              `distance` = '".addslashes($_POST['distance'])."',
              `medal` = '".addslashes($_POST['medal'])."'
            WHERE
              `signUpID` = '".(int)$_POST['signUpID']."' ";
  } else {
    $sql = "INSERT 
              `[PREFIX]_signups` 
            SET 
              `firstName` = '".addslashes($_POST['firstName'])."',
              `middleName` = '".addslashes($_POST['middleName'])."',
              `lastName` = '".addslashes($_POST['lastName'])."',
              `postalCode` = '".addslashes($_POST['postalCode'])."',
              `street` = '".addslashes($_POST['street'])."',
              `houseNr` = '".addslashes($_POST['houseNr'])."',
              `place` = '".addslashes($_POST['place'])."',
              `gender` = '".addslashes($_POST['gender'])."',
              `datebirth` = '".addslashes($_POST['datebirth'])."',
              `email` = '".addslashes($_POST['email'])."',
              `distance` = '".addslashes($_POST['distance'])."',
              `medal` = '".addslashes($_POST['medal'])."'";
  }

  if ($res = Xmysql_query($sql) > 0)
    header ('location: ?page=signups'); 
  else
    echo 'Er ging iets fout';

} else {

	$sql = "SELECT	
              *,
              DATE_FORMAT(`datebirth`, '%d-%m-%Y') AS `datebirth`
            FROM
              `[PREFIX]_signups`
            WHERE
              `signUpID` = '".(int)$_GET['signUpID']."' ;";

	$res = Xmysql_query($sql);
	$row = mysql_fetch_assoc($res);
	?>

	<h1>Aanmelding bewerken</h1>

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
				<td><input type="text" name="postalCode" value="<?= stripslashes($row['postalCode']) ?>" /></td>
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
		<input type="hidden" name="signUpID" value="<?= $row['signUpID'] ?>" />
		<input type="submit" name="submit" value="opslaan" class="button" />
	</form>

<?php 
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>