<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if($_POST['pageID'] > 0) {

	$sql = "UPDATE 
						`[PREFIX]_content` 
					SET 
						`title` = '".addslashes($_POST['title'])."',
						`content` = '".addslashes($_POST['content'])."'
					WHERE
						`pageID` = '".(int)$_POST['pageID']."' ";
	
	if ($res = Xmysql_query($sql) > 0)
		header ('location: ?page=text'); 
	else
		echo 'Er ging iets fout';

}
else {

	$sql = "SELECT	
				*
			FROM
				`[PREFIX]_content`
			WHERE
				`pageID` = '".(int)$_GET['pageID']."' ;";

	$res = Xmysql_query($sql);
	$row = mysql_fetch_assoc($res);
	?>

	<h1>Pagina bewerken</h1>

	<form name="content" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
		<table id="topInput">
			<tr>
				<td>Pagina titel:</td>
				<td><input type="text" name="title" value="<?= stripslashes($row['title']) ?>" /></td>
			<tr>
		</table>
		<textarea name="content"><?= stripslashes($row['content']) ?></textarea>
		<input type="hidden" name="pageID" value="<?= $row['pageID'] ?>" />
		<input type="submit" name="submit" value="opslaan" class="button" />
	</form>

<?php 
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>