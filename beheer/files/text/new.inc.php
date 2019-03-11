<?php
include_once ( dirname ( __FILE__ ) . '/../../init.inc.php' );
include_once ('include/header.php');

if($_POST['submit']) {
   
  if($_GET['pageID'] > 0) {
      
      $sql = "UPDATE 
						  `[PREFIX]_content` 
            SET 
              `parentID` = '".(int)$_POST['parentID']."',
              `title` = '".addslashes($_POST['title'])."',
              `title2` = '".addslashes($_POST['title2'])."',
              `content` = '".addslashes($_POST['content'])."',
              `content2` = '".addslashes($_POST['content2'])."',
              `template` = '".(int)$_POST['template']."' 
            WHERE
              `pageID` = '".(int)$_GET['pageID']."' ";
  }
  else {
	
    $sql = "INSERT 
              `[PREFIX]_content` 
            SET 
              `parentID` = '".(int)$_POST['parentID']."',
              `title` = '".addslashes($_POST['title'])."',
              `title2` = '".addslashes($_POST['title2'])."',
              `content` = '".addslashes($_POST['content'])."',
              `content2` = '".addslashes($_POST['content2'])."',
              `template` = '".(int)$_POST['template']."' ";
  } 
  
  $res = Xmysql_query($sql);
  
  if($_GET['pageID'] > 0)
    $pageID = (int)$_GET['pageID'];
  else
    $pageID = mysql_insert_id();

  //Afbeeldingen verwijderen
  $sqlImg = "SELECT
                `img1`,
                `img2`,
                `img3`,
                `img4`
              FROM
                `[PREFIX]_content`
              WHERE
                `pageID` = '".(int)$_GET['pageID']."' ";

  $resImg = Xmysql_query($sqlImg);
  $rowImg = mysql_fetch_assoc($resImg);


  if(is_array($_POST['del_img'])) {

    foreach ( $_POST['del_img'] as $k => $v ) {
      @unlink(IMG_PATH.$rowImg['img'.$k]);
      @unlink(IMG_PATH.'large_'.$rowImg['img'.$k]);
      
      $sqlDel = "UPDATE
                  `[PREFIX]_content`
                 SET
                  `img".(int)$k."` = ''
                 WHERE
                   `pageID` = '".(int)$_GET['pageID']."' ";
      
      Xmysql_query($sqlDel);
    }

  }
  
  //Afbeeldingen toevoegen
  foreach ( $_FILES as $k => $v ) {
    
      if(is_uploaded_file($_FILES[$k]['tmp_name'])) {

        $extensie = substr($_FILES[$k]['name'], strrpos($_FILES[$k]['name'], '.') +1);
        $t = getdate();
        $newFilename = $pageID.'-'.$k.'-'.date('dhs',$t[0]).'.'.$extensie;
        
        uploadFile($_FILES[$k]['tmp_name'], $newFilename, THUMB_SIZE, 1);
        uploadFile($_FILES[$k]['tmp_name'], 'large_'.$newFilename, IMG_SIZE, 0);
        
        $sqlImg = "UPDATE 
              `[PREFIX]_content` 
            SET 
              `".$k."` = '".$newFilename."'
            WHERE
              `pageID` = '".$pageID."' ";
        
        Xmysql_query($sqlImg);
      }
  }
  
  if ($res > 0)
		header ('location: ?page=text&parentID='.(int)$_POST['parentID']); 
	else
		echo 'Er ging iets fout';

}
else {

  if($_GET['pageID'] > 0) {
    
    $sql = "SELECT
              *
            FROM
              `[PREFIX]_content`
            WHERE
              `pageID` = '".(int)$_GET['pageID']."' ";

    $res = Xmysql_query($sql);
    $row = mysql_fetch_assoc($res);
  }
  if($_GET['template'] > 0) 
    $template = $_GET['template'];
  else
    $template = $row['template'];
?>

<h1>Pagina <?= $_GET['pageID'] > 0 ? 'bewerken' : 'toevoegen'; ?></h1>

<?php
  if($template < 3) {
?>
  <h2>Templates</h2>
  <table class="formTable">
    <tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
      <td style="width: 140px;">Template keuze</td>
      <td>
        <select onChange="javascript:document.location='<?=ADMIN_PATH; ?>?page=text&amp;action=new&amp;parentID=<?= $_GET['parentID']; ?>&amp;pageID=<?= $_GET['pageID']; ?>&amp;template=' + this.value" name="template">
          <option value="1"<?= $template == '1' ? ' selected="selected"' : ''; ?>>Template 1</option>
          <option value="2"<?= $template == '2' ? ' selected="selected"' : ''; ?>>Template 2</option>
        </select>
      </td>
    </tr>
  </table>
<?php
}
if($template == '1') {
?>
	<h2>Afbeeldingen en tekst</h2>
  <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data" name="template1">
    <table class="formTable">
			<tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
				<td>Afbeelding 1:</td>
				<td>
          <input type="file" name="img1" />
 <?php    if($row['img1']) { ?>
             <a href="<?= URL_UPLOAD.'large'.$row['img1']; ?>" onclick="javascript:openWindow('<?= URL_UPLOAD.'large_'.$row['img1']; ?>');return false;"><img src="gfx/tb_image.gif" alt="" /></a>
             <input type="checkbox" name="del_img[1]" value="1" class="check" /> verwijder 
 <?php    } else { ?>
              <img src="gfx/tb_image_grey.gif" alt="" />
              <input type="checkbox" name="del_img[1]" value="1" class="check" disabled="disabled" /> verwijder 
 <?php    } ?>
        </td>
			</tr>
			<tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
				<td>Afbeelding 2:</td>
				<td>
          <input type="file" name="img2" />
 <?php    if($row['img2']) { ?>
             <a href="<?= URL_UPLOAD.'large'.$row['img2']; ?>" onclick="javascript:openWindow('<?= URL_UPLOAD.'large_'.$row['img2']; ?>');return false;"><img src="gfx/tb_image.gif" alt="" /></a>
             <input type="checkbox" name="del_img[2]" value="2" class="check" /> verwijder 
 <?php    } else { ?>
              <img src="gfx/tb_image_grey.gif" alt="" />
              <input type="checkbox" name="del_img[2]" value="2" class="check" disabled="disabled" /> verwijder 
 <?php    } ?>
        </td>
			</tr>
			<tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
				<td>Afbeelding 3:</td>
				<td>
          <input type="file" name="img3" />
 <?php    if($row['img3']) { ?>
             <a href="<?= URL_UPLOAD.'large'.$row['img3']; ?>" onclick="javascript:openWindow('<?= URL_UPLOAD.'large_'.$row['img3']; ?>');return false;"><img src="gfx/tb_image.gif" alt="" /></a>
             <input type="checkbox" name="del_img[3]" value="3" class="check" /> verwijder 
 <?php    } else { ?>
              <img src="gfx/tb_image_grey.gif" alt="" />
              <input type="checkbox" name="del_img[3]" value="3" class="check" disabled="disabled" /> verwijder 
 <?php    } ?>
        </td>
			</tr>
			<tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
				<td>Afbeelding 4:</td>
				<td>
          <input type="file" name="img4" />
 <?php    if($row['img4']) { ?>
             <a href="<?= URL_UPLOAD.'large'.$row['img4']; ?>" onclick="javascript:openWindow('<?= URL_UPLOAD.'large_'.$row['img4']; ?>');return false;"><img src="gfx/tb_image.gif" alt="" /></a>
             <input type="checkbox" name="del_img[4]" value="4" class="check" /> verwijder 
 <?php    } else { ?>
              <img src="gfx/tb_image_grey.gif" alt="" />
              <input type="checkbox" name="del_img[4]" value="4" class="check" disabled="disabled" /> verwijder 
 <?php    } ?>        
        </td>
			</tr>
      <tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
				<td style="width: 140px;">Titel:</td>
				<td><input type="text" class="text" name="title" value="<?= stripslashes($row['title']) ?>" /></td>
			</tr>
      <tr class="subHead">
        <td colspan="2">Omschrijving</td>
      </tr>
		</table>
    <textarea id="content" name="content" cols="50" rows="15"><?=stripslashes($row['content']);?></textarea>
		<input type="hidden" name="parentID" value="<?= $_GET['parentID']; ?>" />
    <input type="hidden" name="template" value="1" />
    <input type="submit" name="submit" value="opslaan" class="button" />
	</form>
<?php
}
elseif($template == '2') {
?>
  <h2>Linkerkant</h2>
  <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data" name="template2">
    <table class="formTable">
      <tr class="<?= $class = 'row0'; ?>">
				<td style="width: 140px;">Titel:</td>
				<td><input type="text" class="text" name="title" value="<?= stripslashes($row['title']); ?>" /></td>
			</tr>
			<tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
				<td>Afbeelding:</td>
				<td>
          <input type="file" name="img1" />
 <?php    if($row['img1']) { ?>
             <a href="<?= URL_UPLOAD.'large'.$row['img1']; ?>" onclick="javascript:openWindow('<?= URL_UPLOAD.'large_'.$row['img1']; ?>');return false;">
              <img src="gfx/tb_image.gif" alt="" />
             </a>
             <input type="checkbox" name="del_img[1]" value="1" class="check" /> verwijder 
 <?php    } else { ?>
              <img src="gfx/tb_image_grey.gif" alt="" />
              <input type="checkbox" name="del_img[1]" value="1" class="check" disabled="disabled" /> verwijder 
 <?php    } ?>
        </td>
			</tr>
      <tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
        <td colspan="2">Omschrijving</td>
      </tr>
		</table>
    <textarea id="content" name="content" cols="50" rows="15"><?=stripslashes($row['content']);?></textarea>
      <br />
      <h2>Rechterkant</h2>
      <table class="formTable">
        <tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
          <td style="width: 140px;">Titel:</td>
          <td><input type="text" class="text" name="title2" value="<?= stripslashes($row['title2']); ?>" /></td>
        </tr>
      </table>
    <textarea id="content2" name="content" cols="50" rows="15"><?=stripslashes($row['content2']);?></textarea>

		<input type="hidden" name="parentID" value="<?= $_GET['parentID']; ?>" />
    <input type="hidden" name="template" value="2" />
    <input type="submit" name="submit" value="opslaan" class="button" />
	</form>

<?php
  }
  else {
?>
  <h2>Content home</h2>
  <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data" name="template2">
    <table class="formTable">
      <tr class="<?= $class = 'row0'; ?>">
				<td style="width: 140px;">Titel:</td>
				<td><input type="text" class="text" name="title" value="<?= stripslashes($row['title']); ?>" /></td>
			</tr>
			<tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
        <td colspan="2">Content</td>
      </tr>
		</table>
    <textarea id="content" name="content" cols="50" rows="15"><?=stripslashes($row['content']);?></textarea>

		<input type="hidden" name="parentID" value="<?= $_GET['parentID']; ?>" />
    <input type="hidden" name="template" value="3" />
    <input type="submit" name="submit" value="opslaan" class="button" />
	</form>

<?php  
  }
}
include_once ( dirname ( __FILE__ ) . '/../../include/footer.php' ); 
?>