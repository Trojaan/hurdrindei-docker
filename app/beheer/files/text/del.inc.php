<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if($_GET['del'] == 'true') {

  $sql = "SELECT
          `img1`,
          `img2`,
          `img3`,
          `img4`
        FROM 
          `[PREFIX]_content` 
        WHERE
          `pageID` = '".(int)$_GET['pageID']."' ";

	$res = Xmysql_query($sql);
  $row = mysql_fetch_assoc($res);

  //Afbeeldingen verwijderen
  for($i=1; $i<5 ; $i++) {
    @unlink(IMG_PATH.$row['img'.$i]);
    @unlink(IMG_PATH.'large_'.$row['img'.$i]);
  }
  
  // Pagina verwijderen
  $sql = "DELETE FROM 
						`[PREFIX]_content` 
					WHERE
						`pageID` = '".(int)$_GET['pageID']."' ";
	
	if ($res = Xmysql_query($sql) > 0)
		header ('location: '.ADMIN_PATH.'?page=text&parentID='.$_GET['parentID']); 
	else
		echo 'Er ging iets fout';
}
else {
	$sql = "SELECT
            `title`
          FROM 
						`[PREFIX]_content` 
					WHERE
						`pageID` = '".(int)$_GET['pageID']."' ";
	
	$res = Xmysql_query($sql);
  $row = mysql_fetch_assoc($res);
?>

  <h1>Pagina verwijderen</h1>
  <h2><?= $row['title']; ?></h2>
  <div id="messageText">
    Weet u zeker dat u deze pagina wilt verwijderen?<br />
    <a href="<?= ADMIN_PATH; ?>?page=text&amp;action=del&amp;pageID=<?= $_GET['pageID']; ?>&amp;parentID=<?= $_GET['parentID']; ?>&amp;del=true">Ja verwijder</a>, 
    <a href="<?= ADMIN_PATH; ?>?page=text&amp;parentID=<?= $_GET['parentID']; ?>">nee terug</a>
  </div>

<?php
}
include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>