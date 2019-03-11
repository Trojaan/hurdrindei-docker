<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

echo '<h1>Resultaat verwijderen</h1>';

if($_GET['del'] == 'true') {      
  
  $sql = "DELETE FROM `[PREFIX]_results` WHERE `resultID` = " . (int)$_GET [ 'resultID' ] . ";";
	Xmysql_query($sql);

	header ( 'Location: ?page=signupList');
}
else {
	echo 'Weet u zeker dat u dit resultaat wilt verwijderen?<br /><a href="?page=signupList&action=del&resultID='.$_GET['resultID'].'&del=true">Ja verwijderen</a>, <a href="?page=signupList">nee terug</a>';	
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>