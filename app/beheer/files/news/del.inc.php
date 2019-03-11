<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

echo '<h1>Nieuwsbericht verwijderen</h1>';

if($_GET['del'] == 'true') {
    
  //Afbeeldingen verwijderen
  $sqlImg = "SELECT
                `img`
              FROM
                `[PREFIX]_news`
              WHERE
                `newsID` = '".(int)$_GET['newsID']."' ";

  $resImg = Xmysql_query($sqlImg);
  $rowImg = mysql_fetch_assoc($resImg);

  @unlink(IMG_PATH.$rowImg['img']);
      
  
  $sql = "DELETE FROM `[PREFIX]_news` WHERE newsID = " . (int)$_GET [ 'newsID' ] . ";";
	Xmysql_query($sql);

	header ( 'Location: ?page=news');
}
else {
	echo 'Weet u zeker dat u dit nieuwsbericht wilt verwijderen?<br /><a href="?page=news&action=del&newsID='.$_GET['newsID'].'&del=true">Ja verwijderen</a>, <a href="?page=news">nee terug</a>';	
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>