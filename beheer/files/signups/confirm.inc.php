<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if(isset($_POST['process']) && count($_POST['personMatch']) > 0) {
  
  $matches = $_POST['personMatch'];

  foreach($matches as $signupID => $personID)
  {
    $signup = getSingupInfo($signupID);

    if($signup['distance'] == '1.5km')
      $signup['distance'] = '1.50';
    else if($signup['distance'] == '8km')
      $signup['distance'] = '8.00';
    else if($signup['distance'] == '14.5km')
      $signup['distance'] = '14.50';
    else if($signup['distance'] == '21.1km')
      $signup['distance'] = '21.10';

    if($signup['medal'] == 'ja')
      $signup['medal'] = 'j';
    else if ($signup['medal'] == 'nee')
      $signup['medal'] = 'n';

    $signup['year'] = $signup['year'] == '0000' ? '2008' : $signup['year'];


    $sql = "INSERT INTO
                     `[PREFIX]_results`
                    SET
                     `personID` = ".(int)$personID.",
                     `distanceFixed` = '".$signup['distance']."',
                     `medal` = '".$signup['medal']."',
                     `year` = ".(int)$signup['year']."
           ";

    

   if(Xmysql_query($sql))
     deleteSignup($signupID);

  }

  echo 'Geselecteerde personen succesvol geexporteerd!';

}

?>
<p>Door onderstaande matches te bevestingen wordt er voor elk geselecteerde match een resultaat toegevoegd voor het jaar <?php echo date("Y") ?>.</p>

<h1>Verwerkte gematchte aanmeldingen bevestigen</h1>
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
	$i = 0;	
  $r = 1;
	$sql = "SELECT	
      				`signUpId`,
              `parentID`,
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
              `parentID` != ''
      			ORDER BY `lastName` ASC;";

	$res = Xmysql_query($sql);

	if(mysql_num_rows($res) == 0) 
		echo '<tr><td colspan="3">Er staan geen geexporteerde aanmeldingen in de database.</td></tr>';
	
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

  if ($row['parentID'] > 0) {

      $personInfo = getPersonInfo($row['parentID']);

       if(count($personInfo) > 0)
       {
?>
      <tbody>
      <tr>
        <td colspan="8" style="border-top: 1px solid #385c72;"><strong>Gematchte persoon:</strong></td>
      </tr>
      <tr>
        <td><input type="checkbox" value="<?=$row['parentID']?>" name="personMatch[<?=$row['signUpId']?>]" id="personMatch_<?=$row['signUpId']?>_<?=$i?>"/></td>
        <td><label for="personMatch_<?=$row['signUpId']?>_<?=$i?>"><?=stripslashes($personInfo['firstName']) . ( isset($personInfo['middleName']) ? ' ' . stripslashes($personInfo['middleName']) : '' ) . ' ' . stripslashes($personInfo['lastName'])?></label></td>
        <td><?= stripslashes($personInfo['address']) ?></td>
        <td colspan="3"><?= stripslashes($personInfo['place']) ?></td>
        <td><?= $personInfo['dateBirth'] != '00-00-0000' ? stripslashes($personInfo['dateBirth']) : '' ?></td>
				<td>
			   <a href="index.php?page=persons&action=edit&personID=<?= $personInfo['personID'] ?>" target="_blank"><img src="gfx/edit_item.png" alt="" />
				</td>
      </tr>
      </tbody>
<?php
        }
      }
      $r++;
		}
	}
?>
</table>
  <input type="submit" name="process" value="Geselecteerde resulaten bevestigen"/>
</form>
<?php include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); ?>