<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if(isset($_POST['process'])) {
  
  $matches = $_POST['personMatch'];

  foreach($matches as $signupID => $personID)
  {
    if(is_numeric($personID)) {
      $sql = "UPDATE
                  `[PREFIX]_signups`
                 SET
                  `parentID` = ".(int)$personID."
               WHERE
                  `signUpID` = ".(int)$signupID."
             ";
   $res = Xmysql_query($sql);
     //myPrint($sql);
     
     $sql = "UPDATE
                  `[PREFIX]_persons`
                 SET
                  `email` = '".getEmail($signupID)."',
                  `dateBirth` = '".getBirthday($signupID)."'
               WHERE
                  `personID` = ".(int)$personID."
             ";
       
     $res = Xmysql_query($sql);
     //myPrint($sql);
    } else {

      $signup = getSingupInfo($signupID);

      $sql = "INSERT 
                `[PREFIX]_persons` 
              SET 
                `firstName` = '".addslashes($signup['firstName'])."',
                `middleName` = '".addslashes($signup['middleName'])."',
                `lastName` = '".addslashes($signup['lastName'])."',
                `postalcode` = '".addslashes($signup['postalCode'])."',
                `street` = '".addslashes($signup['street'])."',
                `houseNr` = '".addslashes($signup['houseNr'])."',
                `place` = '".addslashes($signup['place'])."',
                `gender` = '".$signup['gender']."',
                `datebirth` = '".addslashes($signup['datebirth'])."',
                `email` = '".addslashes($signup['email'])."'";
      //myPrint($sql);
      $res = Xmysql_query($sql);

      $personID = mysql_insert_id();

      $sql = "UPDATE
                  `[PREFIX]_signups`
                 SET
                  `parentID` = ".(int)$personID."
               WHERE
                  `signUpID` = ".(int)$signupID."
             ";
      $res = Xmysql_query($sql);
      //myPrint($sql);

    }

  }

}
?>

<p>Hieronder kun je de binnengekomen aanmeldingen matchen met bestaande personen in de database</p>

<h1>Aanmeldingen overzicht</h1>
<form method="post" action="<?= $_SERVER['REQUEST_URI'] ?>" name="signupOverviewForm">
<table id="tableOverview">
	<tr class="overviewTop">
    <td></td>
		<td>Naam</td>
		<td>Adres</td>
		<td>Plaats</td>
		<td>Afstand</td>
		<td>Medaille</td>
		<td>Geboortedatum</td>
		<td style="width: 45px">&nbsp;</td>
	</tr>
<?php
    $hasResults = false;

	$i = 0;	
  $r = 1;
	$sql = "SELECT	
      				`signUpId`,
      				`firstName`,
      				`middleName`,
      				`lastName`,
              `street`,
              `houseNr`,
      				CONCAT(`street`, ' ', `houseNr`) AS `address`,
      				`houseNr`,
      				`postalcode` AS `postalCode`,
      				`place`,
              `medal`,
      				`distance`,
      				DATE_FORMAT(`dateBirth`, '%d-%m-%Y') AS `dateBirth`
      			FROM
      				`[PREFIX]_signups`
            WHERE
              `parentID` = ''
      			ORDER BY `lastName` ASC;";

  $res = Xmysql_query($sql);
  
  $hasResults = mysql_num_rows($res) > 0; 

	if(mysql_num_rows($res) == 0) 
		echo '<tr><td colspan="3">Er staan geen aanmeldingen in de database.</td></tr>';
	
	else {
		
		while($row = mysql_fetch_assoc($res)) {
      $totalMatches = count($_POST['match']);

			$i++;
			if($i == 2) {
				$color = '#f2f2f2';
				$i = 0;
			}
			else 
				$color = '#e7e7e7';
?>
			<tr style="background-color: <?= $color ?>">
        <td><?=$r?></td>
				<td style="width: 200px;"><?= stripslashes($row['firstName']) . ( isset($row['middleName']) ? ' '.stripslashes($row['middleName']) : '' ) . ' ' . stripslashes($row['lastName']) ?></td>
				<td style="width: 200px;"><?= stripslashes($row['address']) ?></td>
				<td><?= stripslashes($row['place']) ?></td>
				<td><?= stripslashes($row['distance']) ?></td>
				<td><?= stripslashes($row['medal']) ?></td>
				<td><?= $row['dateBirth'] != '00-00-0000' ? stripslashes($row['dateBirth']) : '' ?></td>
				<td>
			   <a href="index.php?page=signups&action=edit&signUpID=<?= $row['signUpId'] ?>"><img src="gfx/edit_item.png" alt="" />
         <a href="index.php?page=signups&action=del&signUpID=<?= $row['signUpId'] ?>"><img src="gfx/trash.png" alt="" />
				</td>
			</tr>
<?php	
     
  if (isset($_POST['export'])) {

      $where = '';

      foreach($_POST['match'] as $key => $cellName) 
      {
        if($cellName == 'datebirth' && $row[$cellName] == '0000-00-00 00:00:00' )
          continue;

        $where .= " `".$cellName."` = '".addslashes($row[$cellName])."' ";

        if($key != ($totalMatches -1))
          $where .= " AND ";
      }

      $sql2 = "SELECT
                 `personID`
                FROM
                 `[PREFIX]_persons`
               WHERE
               ".$where."
               GROUP BY
                 `personID`
               ";

               //print_r($_POST);

       $res2 = Xmysql_query($sql2);
       

       if(mysql_num_rows($res2) > 0)
       {
?>
      <tbody>
      <tr>
        <td colspan="8" style="border-top: 1px solid #385c72;"><strong>Gevonden personen:</strong></td>
      </tr>
<?php
          $i = 1;

          while($result = mysql_fetch_assoc($res2))
          {
            $i++;
            $personInfo = getPersonInfo($result['personID']);
?>
      <tr>
        <td><input type="radio" value="<?=$result['personID']?>" name="personMatch[<?=$row['signUpId']?>]" id="personMatch_<?=$row['signUpId']?>_<?=$i?>"/></td>
        <td><label for="personMatch_<?=$row['signUpId']?>_<?=$i?>"><?=stripslashes($personInfo['firstName']) . ( isset($personInfo['middleName']) ? ' ' . stripslashes($personInfo['middleName']) : '' ) . ' ' . stripslashes($personInfo['lastName'])?></label></td>
        <td><?= stripslashes($personInfo['address']) ?></td>
        <td colspan="3"><?= stripslashes($personInfo['place']) ?></td>
        <td><?= $personInfo['dateBirth'] != '00-00-0000' ? stripslashes($personInfo['dateBirth']) : '' ?></td>
				<td>
			   <a href="index.php?page=persons&action=edit&personID=<?= $personInfo['personID'] ?>" target="_blank"><img src="gfx/edit_item.png" alt="" />
				</td>
      </tr>
<?php
          }
        }

      if(!$hasResults) {
?>
      <tbody>
      <tr>
        <td colspan="2"><strong>Geen gevonden personen</strong></td>
      </tr>
<?php
      }
?>
      <tr>
        <td style="border-bottom: 1px solid #385c72;"><input type="radio" value="new" name="personMatch[<?=$row['signUpId']?>]" id="personMatch_<?=$row['signUpId']?>_new"/></td>
        <td colspan="8" style="border-bottom: 1px solid #385c72;"><label for="personMatch_<?=$row['signUpId']?>_new">Nieuw toevoegen</label></td>
      </tr>
      </tbody>
<?php
      }
      $r++;
		}
	}
?>
</table>
  <table style="width: 100%; margin-bottom: 5px; display: none;">
    <tr>
      <td colspan="2">
        Match aanmeldingen op:
      </td>
    </tr>
    <tr>
      <td>
        <label for="firstName">Voornaam</label><input type="checkbox" name="match[]" value="firstName" id="firstName" />
      </td>
      <td>
        <label for="middleName">Voorvoegsel</label><input type="checkbox" name="match[]" value="middleName" id="middleName"/>
      </td>
      <td>
        <label for="lastName">Achternaam</label><input type="checkbox" name="match[]" value="lastName" id="lastName" checked="checked"/>
      </td>
      <td>
        <label for="datebirth">Geboortedatum</label><input type="checkbox" name="match[]" value="datebirth" id="datebirth"/>
      </td>
      <td>
        <label for="postalCode">Postcode</label><input type="checkbox" name="match[]" value="postalCode" id="postalCode"/>
      </td>
      <td>
        <label for="houseNr">Huisnummer</label><input type="checkbox" name="match[]" value="houseNr" id="houseNr"/>
      </td>
      <td>
        <label for="street">Straat</label><input type="checkbox" name="match[]" value="street" id="street"/>
      </td>
      <td>
        <label for="place">Plaats</label><input type="checkbox" name="match[]" value="place" id="place"/>
      </td>
    </tr>
  </table>
<?php
if (isset($_POST['export'])) {
?>
  <input type="submit" name="process" value="Matches verwerken >" />
<?php
} 
?>
  <input type="submit" name="export" value="Start matchen aanmeldingen <?=isset($_POST['export']) ? 'opnieuw ' : ''?>>"  onclick="return validateMatchCheckBoxes()"/>
<?php
//}
if(!$hasResults) { 
?>
  <input type="button" name="export" value="Bevestig verwerkte matches" onclick="window.location='index.php?page=signups&amp;action=confirm'"/>
<?php } ?>
</form>
<?php include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); ?>