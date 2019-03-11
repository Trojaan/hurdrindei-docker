<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/class.CreateCSV.php' );
include_once ('include/header.php');

if($_GET['act'] == 'export' && isset($_GET['f']) && isset($_GET['t'])) {

    $sql = "SELECT
                IF(
                  (SELECT COUNT(*) FROM `hurd_persons` WHERE `street` = P.`street` AND `houseNr` = P.`houseNr`) > 1,
                  CONCAT('Fam. ',
                         IF(
                          P.`middleName` != '',
                          CONCAT(
                              P.`middleName`,' '
                          ),
                          ''
                      ),
                         P.`lastName`),
                  CONCAT(
                      P.`firstName`,' ',
                      IF(
                          P.`middleName` != '',
                          CONCAT(
                              P.`middleName`,' '
                          ),
                          ''
                      ),
                      P.`lastName`
                    )
                  ) AS `Naam`,
                P.`street` AS `Straat`,
                P.`houseNr` AS `Huisnummer`,
                P.`postalcode` AS `Postcode`,
                P.`place` AS `Plaats`,
                P.`email` AS `Email`
              FROM
                `hurd_results` AS R
              JOIN
                `hurd_persons` AS P
              ON
                R.`personID` = P.`personID`
              WHERE
                R.`year` >= ".(int)$_GET['f']."
              AND
                R.`year` <= ".(int)$_GET['t']."
              GROUP BY
                P.`postalcode`,
                P.`houseNr`,
                P.`place`
              ORDER BY 
                P.`lastName` ASC
              ";

  $file = 'deelnemerlijst'.(int)$_GET['f'].'-'.(int)$_GET['t'].'.csv';
  $fileHandle = fopen($file, 'w') or die ("can't open file.");
  $csv = CreateCSV::create($sql, true, false);

  if(fwrite($fileHandle, $csv)) {

    echo "Deelnemerslijst succesvol geexporteerd. </br>Download de lijst <a href=\"http://kubaarderhurdrindei.nl/beheer/$file\">hier</a>";

  }

  fclose($fileHandle);

}

?>
<h1>Exporteren adreslijst</h1>
<div class="searchBar">
  <form method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
    <h2>Periode meegedaan</h2>
    <table class="innerSearch">
      <tr>
        <td>
          <label for="startYear">Van</label>
        </td>
        <td>
          <input type="text" name="startYear" id="startYear" value="<?=$_POST['startYear']?>" class="text" style="width: 25px"/><label for="search"> Tot en met</label><input type="text" name="stopYear" id="stopYear" value="<?=$_POST['stopYear']?>" class="text" style="width: 25px"/>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="vertical-align: center;">
          <input type="submit" name="searchSubmit" value="Weergeven" class="button"/>
        </td>
      </tr>
    </table>
    <div style="clear: both;"></div>
  </form>
</div>
<?php
  if(isset($_POST['searchSubmit']) && strlen($_POST['startYear']) > 0 && strlen($_POST['stopYear']) > 0) {

    $sql = "SELECT
                IF(
                  (SELECT COUNT(*) FROM `hurd_persons` WHERE `street` = P.`street` AND `houseNr` = P.`houseNr`) > 1,
                  CONCAT('Fam. ',
                         IF(
                          P.`middleName` != '',
                          CONCAT(
                              P.`middleName`,' '
                          ),
                          ''
                      ),
                         P.`lastName`),
                  CONCAT(
                      P.`firstName`,' ',
                      IF(
                          P.`middleName` != '',
                          CONCAT(
                              P.`middleName`,' '
                          ),
                          ''
                      ),
                      P.`lastName`
                    )
                  ) AS `name`,
                P.`firstName`,
                P.`middleName`,
                P.`lastName`,
                CONCAT(
                       P.`street`,' ', P.`houseNr`
                          ) AS `address`,
                P.`houseNr`,
                P.`postalcode`,
                P.`place`,
                P.`email`
              FROM
                `hurd_results` AS R
              JOIN
                `hurd_persons` AS P
              ON
                R.`personID` = P.`personID`
              WHERE
                R.`year` >= ".(int)$_POST['startYear']."
              AND
                R.`year` <= ".(int)$_POST['stopYear']."
              GROUP BY
                P.`postalcode`,
                P.`houseNr`,
                P.`place`
              ORDER BY 
                P.`lastName` ASC
              ";

	$res = Xmysql_query($sql);
?>
<table id="tableOverview">
  <colgroup style="width: 5px;"></colgroup>
  <colgroup style="width: 130px;"></colgroup>
  <colgroup style="width: 130px;"></colgroup>
  <colgroup style="width: 90px;"></colgroup>
  <colgroup style="width: 100px;"></colgroup>
  <colgroup style="width: 100px;"></colgroup>
  <colgroup style="width: 10px;"></colgroup>
  <tr class="overviewTop">
    <td></td>
		<td>Naam</td>
		<td>Adres</td>
		<td>Postcode</td>
		<td>Plaats</td>
		<td style="width: 50px;">Geboortedatum</td>
		<td>Email</td>
    <td>M/V</td>
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
			<tr style="background-color: <?= $color ?>;">
        <td><?=$k?></td>
				<td style="width: 100px;"><?= stripslashes($row['name']) ?></td>
				<td style="width: 200px;"><?= stripslashes($row['address']) ?></td>
				<td style="width: 100px;"><?= stripslashes($row['postalcode']) ?></td>
				<td><?= stripslashes($row['place']) ?></td>
				<td><?= stripslashes($row['datebirth'])?></td>
				<td><?= stripslashes($row['email'])?></td>
        <td><?= stripslashes($row['gender'])?></td>
				<td>
			   <a href="index.php?page=persons&amp;action=edit&amp;personID=<?= (int)$row['personID'] ?>"><img src="gfx/edit_item.png" alt="" /></a>
         <a href="index.php?page=persons&amp;action=del&amp;personID=<?= (int)$row['personID'] ?>"><img src="gfx/trash.png" alt="" /></a>
				</td>
			</tr>
<?php	
      $k++;
		} 
	}
?>
<tr>
  <td><input type="button" value="export" onclick="window.location='index.php?page=persons&amp;action=export&amp;act=export&amp;f=<?=$_POST['startYear']?>&amp;t=<?=$_POST['stopYear']?>'"/></td>
</tr>
<?php
}
?>

</table>

<?php include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); ?>