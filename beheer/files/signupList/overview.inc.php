<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

  $where = '';

  $sql = "SELECT
            `year`
          FROM
            `[PREFIX]_results`
          GROUP BY
            `year`
          ORDER BY `year` DESC;";
  $res = Xmysql_query($sql);

  while($row = mysql_fetch_assoc($res))
  {

    $years [] = $row['year'];

  }
  $_GET['year'] = isset($_GET['year']) ? $_GET['year'] : current($years);

  if(isset($_POST['searchSubmit']))
  {

    if(isset($_POST['startNumber']))
    {
      $where = "WHERE 
                   R.`startNumber` = ".(int)$_POST['q']."
                ";
    } else {

      $where = "WHERE (
                       P.`firstName` LIKE '%".$_POST['q']."%'
                    OR
                       P.`middleName` LIKE '%".$_POST['q']."%'
                    OR
                       P.`lastName` LIKE '%".$_POST['q']."%'
                    OR
                       P.`street` LIKE '%".$_POST['q']."%'
                    OR
                       P.`houseNr` LIKE '%".$_POST['q']."%'
                    OR
                       P.`postalcode` LIKE '%".$_POST['q']."%'
                    OR
                       P.`place` LIKE '%".$_POST['q']."%'
                    )
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
              R.`distance`,
              R.`medal`,
              R.`resultID`,
              R.`startNumber`
      			FROM
              `[PREFIX]_results` AS `R`
            JOIN
      				`[PREFIX]_persons` AS `P`
            ON
              R.`personID` = P.`personID`
            WHERE
              R.`year` = ".$_GET['year']."
            AND
              R.`timeFixed` = '00:00:00'
            ".$where."
            ".$where2."
      			ORDER BY 
              R.`startNumber`,
              P.`lastName` ASC;";

	$sql = "SELECT	
      				P.`personID`,
      				P.`firstName`,
      				P.`middleName`,
      				P.`lastName`,
      				CONCAT(P.`street`, ' ', P.`houseNr`) AS `address`,
      				P.`postalcode`,
      				P.`place`,
              P.`gender`,
      				DATE_FORMAT(P.`datebirth`, '%d-%m-%Y') AS `datebirth`
      			FROM
      				`[PREFIX]_persons` AS P
            ".$where."
      			ORDER BY 
              P.`lastName` ASC;";
?>

<h1>Resultaat toevoegen</h1>

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
      <!--<tr>
        <td colspan="2">
          <div class="searchFilter">
            <input type="checkbox" name="distance[15km]" id="15km" value="15km" <?= isset($distance['15km']) ? 'checked="checked"'  : ''?>/> <label for="15km">1.5 km</label>
          </div>
          <div class="searchFilter">
            <input type="checkbox" name="distance[8km]" id="8km" value="8km" class="searchFilter" <?= isset($distance['8km']) ? 'checked="checked"'  : ''?>/> <label for="8km">8 km</label>
          </div>
          <div class="searchFilter">
            <input type="checkbox" name="distance[145km]" id="145km" value="145km" class="searchFilter" <?= isset($distance['145km']) ? 'checked="checked"'  : ''?>/> <label for="145km">14.5 km</label>
          </div>
          <div class="searchFilter">
            <input type="checkbox" name="distance[211km]" id="211km" value="211km" class="searchFilter" <?= isset($distance['211km']) ? 'checked="checked"'  : ''?>/> <label for="211km">21.1 km</label>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div class="searchFilter">
            <input type="checkbox" name="startNumber" id="startNumber" value="1" <?= isset($_POST['startNumber']) ? 'checked="checked"'  : ''?>/> <label for="startNumber">Zoeken op startnummer</label>
          </div>
        </td>
      </tr>-->
    </table>
<?php
  if(isset($_POST['searchSubmit']) && strlen($_POST['q']) > 0) {
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
?>

    <div style="clear: both;"></div>
  </form>
</div>


<select class="yearSelect" name="year" onchange="window.location='index.php?page=signupList&amp;year=' + this.value">
<?php
  foreach($years as $year)
  {
?>
  <option value="<?=$year?>" <?= $_GET['year'] == $year ? 'selected="selected"' : '' ?>><?=$year?></option>  
<?php
  }
?>
</select>

<table id="tableOverview">
	<tr class="overviewTop">
    <td></td>
		<td>Naam</td>
		<td>Postcode</td>
		<td>Plaats</td>
		<td>Geboortedatum</td>
    <!--<td>Afstand</td>
    <td>Medaille</td>
    <td>StartNr</td>
		<td style="width: 55px">&nbsp;</td>-->
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

?>
			<tr style="cursor: pointer; background-color: <?= $color ?>" onclick="window.location='index.php?page=signupList&amp;action=resultList&amp;personID=<?= (int)$row['personID'] ?>'">
				<td><?=$r?></td>
        <td style="width: 250px;"><?= stripslashes($row['lastName']) . ', ' . stripslashes($row['firstName']) . ( isset($row['middleName']) ? ' '.stripslashes($row['middleName']) : '' ) ?></td>
				<td style="width: 140px;"><?= stripslashes($row['postalcode']) ?></td>
				<td><?= stripslashes($row['place']) ?></td>
				<td><?= stripslashes($row['datebirth'])?></td>
        <!--<td><?= stripslashes($row['distance'])?></td>
        <td><?= ucfirst(stripslashes($row['medal']))?></td>
        <td><?= stripslashes($row['startNumber'])?></td>
				<td>
			   <a href="index.php?page=persons&action=edit&personID=<?= (int)$row['personID'] ?>"><img src="gfx/edit_item.png" alt="" />
         <a href="index.php?page=signupList&action=del&resultID=<?= (int)$row['resultID'] ?>"><img src="gfx/trash.png" alt="" />
				</td>-->
			</tr>
<?php	
      $r++;
		} 
	}
?>

</table>

<?php include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); ?>