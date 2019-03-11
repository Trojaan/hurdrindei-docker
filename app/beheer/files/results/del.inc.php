<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

echo '<h1>Persoon verwijderen</h1>';

if($_GET['del'] == 'true') {      
  
  $sql = "DELETE FROM `[PREFIX]_persons` WHERE `personID` = " . (int)$_GET [ 'personID' ] . ";";
	Xmysql_query($sql);

	header ( 'Location: ?page=persons');
}
else {
	echo 'Weet u zeker dat u dit persoon wilt verwijderen?<br /><a href="?page=persons&action=del&personID='.$_GET['personID'].'&del=true">Ja verwijderen</a>, <a href="?page=persons">nee terug</a>';	
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>