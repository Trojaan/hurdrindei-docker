<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');



  $where = '';

  if(isset($_POST['searchSubmit']))
  {
    if($_POST['times'] && $_POST['times'] != 'all') {

      $where = "WHERE
                   (SELECT count(R.`personID`)
                      FROM `[PREFIX]_results` AS R
                     WHERE R.`personID` = P.`personID`) = ". $_POST['times'] ."
                   ";

    } else {

      $where = "WHERE
                   `firstName` LIKE '%".$_POST['q']."%'
                OR
                   `middleName` LIKE '%".$_POST['q']."%'
                OR
                   `lastName` LIKE '%".$_POST['q']."%'
                OR
                   `street` LIKE '%".$_POST['q']."%'
                OR
                   `houseNr` LIKE '%".$_POST['q']."%'
                OR
                   `postalcode` LIKE '%".$_POST['q']."%'
                OR
                   `place` LIKE '%".$_POST['q']."%'
                   ";

    }
  }


	$sql = "SELECT	
      				P.`personID`,
      				P.`firstName`,
      				P.`middleName`,
      				P.`lastName`,
      				CONCAT(P.`street`, ' ', P.`houseNr`) AS `address`,
      				P.`postalcode`,
      				P.`place`,
              P.`gender`,
      				DATE_FORMAT(P.`datebirth`, '%d-%m-%Y') AS `datebirth`,
              (SELECT count(R.`personID`)
                FROM `[PREFIX]_results` AS R
               WHERE R.`personID` = P.`personID`) AS `timesPlayed`
      			FROM
      				`[PREFIX]_persons` AS P
            ".$where."
      			ORDER BY `lastName` ASC;";

                  //print($sql);

	$res = Xmysql_query($sql);

?>

<h1>Personen overzicht</h1>

<div class="searchBar">
  <form method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
    <h2>Zoeken</h2>
    <table class="innerSearch">
      <tr>
        <td>
          <h2><label for="search">Trefwoord</label></h2>
        </td>
        <td>
          <input type="text" name="q" id="search" value="" class="text"/>
        </td>
        <td style="vertical-align: center;">
          <input type="submit" name="searchSubmit" value="Zoeken" class="button"/>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          Meegedaan: <input type="radio" name="times" value="4" id="4times" <?= $_POST['times'] == '4' ? 'checked="checked"' : '' ?> /> <label for="4times">4x</label> <input type="radio" name="times" value="9" id="9times" <?= $_POST['times'] == '9' ? 'checked="checked"' : '' ?>/> <label for="9times">9x</label> <input type="radio" name="times" value="all" id="all" <?= $_POST['times'] == 'all' ? 'checked="checked"' : '' ?>/> <label for="all">Alles</label>
        </td>
      </tr>
    </table>
<?php
  if(isset($_POST['searchSubmit'])) {

    if(isset($_POST['times'])) {
?>
    <table class="innerSearch">
      <tr>
        <td>
          U heeft gefilterd op: <?= $_POST['times'] ?>x
        </td>
      </tr>
    </table>
<?php
    } else {
?>
    <table class="innerSearch">
      <tr>
        <td>
          U zocht op: <strong>"<?= $_POST['q'] ?>"</strong>
        </td>
      </tr>
    </table>
<?php
    }
  }
?>

    <div style="clear: both;"></div>
  </form>
</div>

<table id="tableOverview">
  <colgroup style="width: 5px;"></colgroup>
  <colgroup style="width: 130px;"></colgroup>
  <colgroup style="width: 130px;"></colgroup>
  <colgroup style="width: 90px;"></colgroup>
  <colgroup style="width: 100px;"></colgroup>
  <colgroup style="width: 100px;"></colgroup>
  <colgroup style="width: 50px;"></colgroup>
  <colgroup style="width: 10px;"></colgroup>
  <tr class="overviewTop">
    <td></td>
		<td>Naam</td>
		<td>Adres</td>
		<td>Postcode</td>
		<td>Plaats</td>
		<td style="width: 50px;">Geboortedatum</td>
    <td>M/V</td>
		<td style="width: 30px">Meeged.</td>
		<td style="width: 55px">&nbsp;</td>
	</tr>
<?php	
	$i = 0;	
  $k = 1;

	if(mysql_num_rows($res) == 0) 
		echo '<tr><td colspan="3">Er staan geen personen in de database.</td></tr>';

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
			<tr style="background-color: <?= $color ?>; cursor: pointer;" onclick="window.location='index.php?page=persons&amp;action=edit&amp;personID=<?= (int)$row['personID'] ?>'">
        <td><?=$k?></td>
				<td style="width: 100px;"><?= stripslashes($row['lastName']) . ', ' . stripslashes($row['firstName']) . ( isset($row['middleName']) ? ' '.stripslashes($row['middleName']) : '' ) ?></td>
				<td style="width: 200px;"><?= stripslashes($row['address']) ?></td>
				<td style="width: 100px;"><?= stripslashes($row['postalcode']) ?></td>
				<td><?= stripslashes($row['place']) ?></td>
				<td><?= $row['datebirth'] != '00-00-0000' ? stripslashes($row['datebirth']) : ''?></td>
        <td><?= stripslashes($row['gender'])?></td>
        <td><?= stripslashes($row['timesPlayed'])?></td>
				<td class="printHidden">
			   <a href="index.php?page=persons&amp;action=edit&amp;personID=<?= (int)$row['personID'] ?>"><img src="gfx/edit_item.png" alt="" /></a>
         <a href="index.php?page=persons&amp;action=del&amp;personID=<?= (int)$row['personID'] ?>"><img src="gfx/trash.png" alt="" /></a>
				</td>
			</tr>
<?php	
      $k++;
		} 
	}
?>

</table>

<?php include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); ?>