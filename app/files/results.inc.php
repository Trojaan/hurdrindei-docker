<?php
include_once ( 'layout/header.inc.php' );

//$sort = $_GET['sort'] = 'DESC' ? 'ASC' : 'DESC'; 

if($_GET['sort'] == 'DESC')
  $sort = 'ASC';
else
  $sort = 'DESC';  
  
	$sql = "SELECT
              R.`year`
            FROM
              `[PREFIX]_results` AS R
            JOIN
              `[PREFIX]_publishedyears` AS PY
            ON
              R.`year` = PY.`year`
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
     
     if($_POST['published']) {
         
        $sql = "INSERT INTO
                         `[PREFIX]_publishedyears`
                        SET
                         `year` = ".$_GET['year']."
                    ";
                    
     } else {
         
         
        $sql = "DELETE FROM
                         `[PREFIX]_publishedyears`
                        WHERE
                         `year` = ".$_GET['year']."
                    ";
        
         
     }
    
    Xmysql_query($sql);
 }
  $where = '';

  if(isset($_POST['searchSubmit']))
  {
    if(isset($_POST['distance']))
    {
      $where2 .= "AND R.`distance` IN ('".implode('\',\'',$_POST['distance'])."')";


      $distance = $_POST['distance'];

    }

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
                  OR
                     R.`startNumber` = ".(int)$_POST['q']."
                  )
              ";
  }
  
  $order = '';
  if(isset($_GET['sort'])) {
    $order = "`timeFixed` ".$_GET['sort'].",";
  }

  $_GET['distance'] = isset($_GET['distance']) ? $_GET['distance'] : '8km';

  if($_GET['distance'] == '15km') {
    $dist = '1.50';
    $label = '1,5 KM';
  }else if ($_GET['distance'] == '8km') {
    $dist = '8.00';
    $label = '8 KM';
  }else if ($_GET['distance'] == '145km') {
    $dist = '14.50';
    $label = '14,5 KM';
  }else if ($_GET['distance'] == '211km') {
    $dist = '21.10';
    $label = '21,1 KM';
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
          AND
            R.`distanceFixed` = '".$dist."'
          AND
            R.`timeFixed` != '00:00:00' 
          ".$where."
          ".$where2."
          ORDER BY 
            ".$order."
            `timeFixed` ASC;";

 $distances = array('15km' => '1.5km','8km' => '8km','145km' => '14.5km', '211km' => '21.1km');

?>

<h2 style="margin-bottom: 15px;">Uitslagen <?=$_GET['year']?> - <?= $label?></h2>

<div style="margin-bottom: 10px;">
Selecteer jaar:
<select class="yearSelect" style="margin-right: 20px;" name="year" onchange="window.location='/8/<?=$_GET['title']?>.html/' + this.value <?=isset($_GET['distance']) ? ' + \'/'.$_GET['distance'].'/\'' : ''?>">
<?php
  foreach($years as $year)
  {
?>
  <option value="<?=$year?>" <?= $_GET['year'] == $year ? 'selected="selected"' : '' ?>><?=$year?></option>  
<?php
  }
?>
</select>

Selecteer Afstand
<select class="distanceSelect" name="year" onchange="window.location='/8/<?=$_GET['title']?>.html<?=isset($_GET['year']) ? '/'.$_GET['year'] : ''?>/' + this.value + '/'">
<?php
  foreach($distances as $key => $distance)
  {
?>
  <option value="<?=$key?>" <?= $_GET['distance'] == $key ? 'selected="selected"' : '' ?>><?=$distance?></option>  
<?php
  }
?>
</select>
</div>
<?php if(!in_array($_GET['year'], $years)) {?>
    Dit jaar is nog niet gepubliceerd.
<?php } else { ?>
<table id="tableOverview">
	<tr class="overviewTop">
    <th></th>
		<th>Naam</th>
		<th>Woonplaats</th>
		<th>Geslacht</th>
    <th>Tijd</th>
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
			<tr style="background-color: <?= $color ?>">
				<td><?=$r?></td>
        <td style="width: 200px;"><?= stripslashes($row['lastName']) . ', ' . stripslashes($row['firstName']) . ( isset($row['middleName']) ? ' '.stripslashes($row['middleName']) : '' ) ?></td>
        <td><?= stripslashes($row['place'])?></td>
        <td><?= stripslashes($row['gender'])?></td>
        <td><?= ucfirst(stripslashes($row['timeFixed']))?></td>
			</tr>
<?php	
      $r++;
		} 
	}
?>

</table>

<?php
}
	include_once ( 'layout/footer.inc.php' );
?>