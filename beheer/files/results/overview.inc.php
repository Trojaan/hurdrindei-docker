<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

//$sort = $_GET['sort'] = 'DESC' ? 'ASC' : 'DESC'; 

//print_r($_POST); 

if($_GET['sort'] == 'DESC')
  $sort = 'ASC';
else
  $sort = 'DESC';
  
  
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

 if(isset($_POST['published'])) {

    $sql = "DELETE FROM
                     `[PREFIX]_publishedyears`
                    where
                     `year` = ".$_GET['year']."
                ";

    Xmysql_query($sql);


    $sql = "INSERT INTO
                     `[PREFIX]_publishedyears`
                    SET
                     `year` = ".$_GET['year']."
                ";

    Xmysql_query($sql);
 } else {
        $sql = "DELETE FROM
                     `[PREFIX]_publishedyears`
                    where
                     `year` = ".$_GET['year']."
                ";

    Xmysql_query($sql);
 }

  $where = '';

  if(isset($_POST['searchSubmit']))
  {
    if(isset($_POST['distance']))
    {
      $where2 .= "AND R.`distanceFixed` IN ('" . implode('\',\'', $_POST['distance']) . "')";


      $distance = $_POST['distance'];

    } else {

    $where = "AND (
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
  
  $order = '';
  if(isset($_GET['sort'])) {
    $order = "`timeFixed` ".$_GET['sort'].",";
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
            R.`timeFixed`
          FROM
            `[PREFIX]_results` AS `R`
          JOIN
            `[PREFIX]_persons` AS `P`
          ON
            R.`personID` = P.`personID`
          WHERE
            R.`year` = ".$_GET['year']."
          ".$where."
          ".$where2."
          ORDER BY 
            ".$order."
            `lastName` ASC;";
?>

<h1>Resultaten overzicht</h1>
  <script type="text/javascript">
    function submitFrom(formName)
    {
      document.publishYear.submit();
    }
  </script>
<form method="post" action="<?= $_SERVER['REQUEST_URI'] ?>" name="publishYear">

<div class="searchBar">
  
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
          <div class="searchFilter">
            <input type="checkbox" name="distance[15km]" id="15km" value="1.50" <?= isset($distance['15km']) ? 'checked="checked"'  : ''?>/> <label for="15km">1.5 km</label>
          </div>
          <div class="searchFilter">
            <input type="checkbox" name="distance[8km]" id="8km" value="8.00" class="searchFilter" <?= isset($distance['8km']) ? 'checked="checked"'  : ''?>/> <label for="8km">8 km</label>
          </div>
          <div class="searchFilter">
            <input type="checkbox" name="distance[145km]" id="145km" value="14.50" class="searchFilter" <?= isset($distance['145km']) ? 'checked="checked"'  : ''?>/> <label for="145km">14.5 km</label>
          </div>
          <div class="searchFilter">
            <input type="checkbox" name="distance[211km]" id="211km" value="21.10" class="searchFilter" <?= isset($distance['211km']) ? 'checked="checked"'  : ''?>/> <label for="211km">21.1 km</label>
          </div>
        </td>
      </tr>
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
  <!--</form>-->
</div>

<select class="yearSelect" name="year" onchange="window.location='index.php?page=results&amp;year=' + this.value">
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
		<td>Woonplaats</td>
		<td>Geslacht</td>
    <td><a href="index.php?page=results&sort=<?=$sort?>" style="color: #fff;">Tijd</a></td>
		<td style="width: 55px">&nbsp;</td>
	</tr>
	
<?php	
	$i = 0;	
  $r = 1;
  $res = Xmysql_query($sql);

	if(mysql_num_rows($res) == 0) 
		echo '<tr><td colspan="3">Er kunnen geen resultaten worden ingevoerd voor dit jaar.</td></tr>';

	else {
		
		while($row = mysql_fetch_assoc($res)) {
			$i++;
			if($i == 2) {
				$color = '#f2f2f2';
				$i = 0;
			}
			else 
				$color = '#e7e7e7';

      if($row['timeFixed'] != '00:00:00' && $row['startNumber'] != 0)
        $color = 'green';

?>
			<tr style="cursor: pointer; background-color: <?= $color ?>" onclick="window.location='index.php?page=signupList&action=edit&resultID=<?= (int)$row['resultID'] ?>&amp;year=<?=$_GET['year']?>'">
				<td><?=$r?></td>
        <td style="width: 200px;"><?= stripslashes($row['lastName']) . ', ' . stripslashes($row['firstName']) . ( isset($row['middleName']) ? ' '.stripslashes($row['middleName']) : '' ) ?></td>
        <td><?= stripslashes($row['place'])?></td>
        <td><?= stripslashes($row['gender'])?></td>
        <td><?= ucfirst(stripslashes($row['timeFixed']))?></td>
				<td>
			   <a href="index.php?page=signupList&action=edit&resultID=<?= (int)$row['resultID'] ?>"><img src="gfx/edit_item.png" alt="" />
         <a href="index.php?page=signupList&action=del&resultID=<?= (int)$row['resultID'] ?>"><img src="gfx/trash.png" alt="" />
				</td>
			</tr>
<?php	
      $r++;
		} 
	}

    $sql = "SELECT
                *
              FROM
                `[PREFIX]_publishedyears`
             WHERE
               `year` = ".$_GET['year']."
          ";

    $res = Xmysql_query($sql)
?>

</table>
<!--<form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post" name="publishYear">-->
  <input type="checkbox" value="true" name="published" id="published" style="float: left;" onclick="submitFrom('resultForm')" <?= mysql_num_rows($res) > 0 ? 'checked="checked"' : '' ?>> <label for="published">Toon resultaten van <?= $_GET['year'] ?> op de voorpagina.</label> 
</form>

<?php include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); ?>