<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if($_POST['submit']) {	
	
	if($_POST['newsID'] > 0) {
		$sql = "UPDATE 
					`[PREFIX]_news` 
				SET 
					`title` = '".addslashes($_POST['title'])."',
					`intro` = '".addslashes($_POST['intro'])."',
					`message` = '".addslashes($_POST['message'])."',
					`date` = '".addslashes($_POST['date'])."'
				WHERE
					`newsID` = '".(int)$_POST['newsID']."' ";
	} 
	else {
		$sql = "INSERT INTO 
					`[PREFIX]_news` 
				SET 
					`title` = '".addslashes($_POST['title'])."',
					`intro` = '".addslashes($_POST['intro'])."',
					`message` = '".addslashes($_POST['message'])."',
					`date` = NOW() ";
	}
  $res = Xmysql_query($sql);
  
  //Afbeeldingen verwijderen
  if($_POST['del_img'] > 0) {
    $sqlImg = "SELECT
                  `img`
                FROM
                  `[PREFIX]_news`
                WHERE
                  `newsID` = '".(int)$_POST['newsID']."' ";

    $resImg = Xmysql_query($sqlImg);
    $rowImg = mysql_fetch_assoc($resImg);

    @unlink(IMG_PATH.$rowImg['img']);
    
    $sqlDel = "UPDATE
                `[PREFIX]_news`
               SET
                `img` = ''
               WHERE
                 `newsID` = '".(int)$_POST['newsID']."' ";
    
    Xmysql_query($sqlDel);
  }

  //Afbeeldingen toevoegen  
  if(is_uploaded_file($_FILES['img']['tmp_name'])) {
          
      $extensie = substr($_FILES['img']['name'], strrpos($_FILES['img']['name'], '.') +1);
      $t = getdate();
      $newFilename = 'news'.$newsID.date('dhs',$t[0]).'.'.$extensie;
      
      uploadFile($_FILES['img']['tmp_name'], $newFilename, THUMB_SIZE, 1);
      
      $sql = "UPDATE 
            `[PREFIX]_news` 
          SET 
            `img` = '".$newFilename."'
          WHERE
            `newsID` = '".$newsID."' ";
      
      Xmysql_query($sql);
  }

	if ($res > 0)
		header ('location: ?page=news'); 
	else
		echo 'Er ging iets fout';

}
else {

	if($_GET['newsID']) {
		$sql = "SELECT	
					*
				FROM
					`[PREFIX]_news`
				WHERE
					`newsID` = '".(int)$_GET['newsID']."' ;";

		$res = Xmysql_query($sql);
		$row = mysql_fetch_assoc($res);
	}
	?>

	<h2>Nieuwsbericht <?= $_GET['newsID'] > 0 ? 'bewerken' : 'toevoegen'; ?></h2>

	<form name="content" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data">
		<table class="formTable"> 
			<tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
				<td style="width: 140px;">Nieuws titel:</td>
				<td><input type="text" class="text" name="title" value="<?= stripslashes($row['title']) ?>" /></td>
			<tr>
			<tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
				<td>Thumbnail:</td>
				<td>
          <input type="file" name="img" />
 <?php    if($row['img']) { ?>
             <a href="<?= URL_UPLOAD.$row['img']; ?>" onclick="javascript:openWindow('<?= URL_UPLOAD.$row['img']; ?>');return false;"><img src="gfx/tb_image.gif" alt="" /></a>
             <input type="checkbox" name="del_img" value="1" class="check" /> verwijder 
 <?php    } else { ?>
              <img src="gfx/tb_image_grey.gif" alt="" />
              <input type="checkbox" name="del_img" value="1" class="check" disabled="disabled" /> verwijder 
 <?php    } ?>
        </td>
			</tr>
		</table>
		<h2>Inleiding (max 2 regels)</h2>
    <textarea id="intro" name="intro" cols="50" rows="15"><?=stripslashes($row['intro']);?></textarea>

		<h2>Nieuwsbericht</h2>
    <textarea id="content" name="message" cols="50" rows="15"><?=stripslashes($row['message']);?></textarea>

		<input type="hidden" name="newsID" value="<?= $row['newsID'] ?>" />
		<input type="hidden" name="date" value="<?= $row['date'] ?>" />
		<input type="submit" name="submit" value="opslaan" class="button" />
	</form>

<?php 
}

include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>