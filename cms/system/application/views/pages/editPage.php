  <div id="navigationBar">
  </div>
  <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post">
    <div class="formBlock">
      <table class="formTable">
        <tr class="<?= $class = 'row0'; ?>">
          <td style="width: 140px;">Titel:</td>
          <td><input type="text" class="text" name="pagetitle" value="<?= isset($page->title) ? $page->title : '' ?>" /></td>
        </tr>
        <tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
          <td style="width: 140px;">Hoofdniveau:</td>
          <td><?php echo form_dropdown('parentID', $pages, $parentID); ?></td>
        </tr>
        <tr class="<?= $class = $class == 'row0' ? 'row1' : 'row0'; ?>">
          <td style="width: 140px;">Actief:</td>
          <td><input type="checkbox" name="active" value="1" <?php echo set_checkbox('active', '1', (isset($page->active) ? $page->active : '0')) ?> /></td>
        </tr>
        <tr class="<?= $class = 'row0'; ?>">
          <td style="width: 140px;">Module:</td>
          <td>
            <input type="radio" name="is_module" value="0" id="text" onchange="showContent('hide')" <?= $moduleID == 0 ? 'checked="checked"' : '' ?>/><label for="text">Nee</label>
            <input type="radio" name="is_module" value="1" id="module" onchange="showContent('show')" <?= $moduleID > 0 ? 'checked="checked"' : '' ?>/><label for="module">Ja</label>
          </td>
        </tr>
        <tr id="moduleRegion" <?= $moduleID == 0 ? ' class="defaultHidden"' : '' ?>>
          <td style="width: 140px;">Selecteer module:</td>
          <td><?php echo form_dropdown('moduleID', $modules, $moduleID); ?></td>
        </tr>
      </table>
    </div>
    <div id="contentRegion" <?= $moduleID > 0 ? 'style="display: none"' : '' ?>>
      <textarea id="pagecontent" name="content" cols="50" rows="15"><?= isset($page->content) ? $page->content : '' ?></textarea>
      <script type="text/javascript">
        CKEDITOR.replace( 'pagecontent',
            {
              filebrowserBrowseUrl : '/ckfinder/ckfinder.html',
              filebrowserImageBrowseUrl : '/ckfinder/ckfinder.html?Type=Images',
              filebrowserFlashBrowseUrl : '/ckfinder/ckfinder.html?Type=Flash',
              filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
              filebrowserImageUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
              filebrowserFlashUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            }
        );
      </script>
    </div>
    <input type="submit" name="submit" value="" class="savebutton" style="opacity: 1;" />
    <input type="button" value="" class="cancelbutton" style="opacity: 1;" onclick="window.location='<?=site_url('pages')?>'" />
    <div class="spacer" />
	</form>