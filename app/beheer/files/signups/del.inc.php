<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

echo '<h1>Aanmelding verwijderen</h1>';

if($_GET['del'] == 'true') {    
  
  $sql = "DELETE FROM `[PREFIX]_signups` WHERE signUpID = " . (int)$_GET [ 'signUpID' ] . ";";
  //myPrint($sql);
	Xmysql_query($sql);

	header ( 'Location: ?page=signups');
}
else {
	echo 'Weet u zeker dat u deze aanmelding wilt verwijderen?<br /><a href="?page=signups&action=del&signUpID='.$_GET['signUpID'].'&del=true">Ja verwijderen</a>, <a href="?page=signups">nee terug</a>';	
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>