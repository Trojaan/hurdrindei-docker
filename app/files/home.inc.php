<?php

	include_once ( 'layout/header.inc.php' );

$_GET['pageID'] = $_GET['pageID'] == 1 ? 6 : $_GET['pageID'];

if(isset($_GET['pageID'])) {

 $sql = "SELECT
             *
           FROM
            `[PREFIX]_content`
          WHERE
            `pageID` = ".$_GET['pageID']."
        ";
 $res = Xmysql_query($sql);

 $data = mysql_fetch_assoc($res);

}
?>
   <?=stripslashes($data['content'])?>

<?php
	include_once ( 'layout/footer.inc.php' );
?>