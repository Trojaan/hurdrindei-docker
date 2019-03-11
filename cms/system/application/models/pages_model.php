<?php
class Pages_model extends Model {

  function Pages_model()
  {
    parent::Model();
  }

  function getPages()
  {
    $sql = "SELECT C.`pageID`,
                   C.`parentID`,
                   C.`moduleID`,
                   C.`title`,
                   C.`active`,
                   C.`default`,
                   C.`orderID`,
                   (IF(`parentID` > 0,
                      'yes',
                      'no')) AS `hasParent`,
                   (
                    SELECT LPAD(parent.orderID, 5, '0')
                    FROM `" . $this->db->dbprefix('_content') . "` AS parent
                    WHERE parent.pageID = C.pageID AND parent.parentID = 0

                    UNION

                    SELECT CONCAT(LPAD(parent.orderID, 5, '0'), '.', LPAD(child.orderID, 5, '0'))
                    FROM `" . $this->db->dbprefix('_content') . "` parent
                    INNER JOIN `" . $this->db->dbprefix('_content') . "` child ON (parent.pageID = child.parentID)
                    WHERE child.pageID = C.pageID AND parent.parentID = 0
                   ) AS level
              FROM `" . $this->db->dbprefix('_content') . "` AS C
             WHERE `deleted` = '0'
          ORDER BY `level`
           ";

    if($query = $this->db->query($sql)) {

      return $query->result_array();
    }

    return false;

    //$ctr = 0;
    //$output = '';

    /*if ($query->num_rows() > 0) {

      foreach ($query->result() as $page_info) {

        $ctr++;

        $output .= '<tr class="row' . ($ctr & 1) . '">
                      <td>
                        ' . $ctr . '
                      </td>
                      <td>
                        <input id="hid' . $ctr . '" name="hid[]" value="' . $page_info->pageID . '" type="hidden" />
                        <input id="cb' . $ctr . '" name="cid[]" value="' . $page_info->pageID . '" onclick="isChecked(this.checked);" type="checkbox">
                      </td>
                      <td nowrap="nowrap">
                        ' . ( $page_info->hasParent == 'yes' ? '<img src="' . getThemeGFXPath() .'child-arrow.png" class="childArrow" />'  : '' ) . '
                        <span class="editlinktip hasTip">
                          <a href="' . site_url('pages/edit') . '/' . $page_info->pageID . '' .  '/' . ( isset($page_info->parentID) ? $page_info->parentID : 0 ) . '/' . ( $page_info->moduleID > 0 ? $page_info->moduleID . '/' : '' ) . '">' . stripslashes($page_info->title) . '</a>
                        </span>
                      </td>
                      <td align="center">
                        ' . $this->getModuleIcon($page_info->pageID) . '
                      </td>
                      <td align="center">
                        ' . ( $page_info->default == '1' ? '<img src="' . getThemeGFXPath() .'icon-16-star.png" alt="Standaard">' : '' ) . '
                      </td>
                      <td align="center">
                      ' . ( $page_info->active == '1' ? '
                        <a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $ctr . '\',\'unpublishpage\')" title="Depubliceer item">
                          <img src="' . getThemeGFXPath() . 'icon-tick.png" alt="Gepubliceerd" border="0">
                        </a>
                      ' : '<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $ctr . '\',\'publishpage\')" title="Publiceer item">
                          <img src="' . getThemeGFXPath() . 'publish_x.png" alt="Gedepubliceerd" border="0">
                        </a>'
                       ) . '
                      </td>
                      <td class="order" nowrap="nowrap">
                        <span>&nbsp;</span>
                        <span>
                          ' . ( $ctr < 18 ? '
                          <a href="#reorder" onclick="return saveOrder(' . $ctr . ',\'orderdown\')" title="Naar beneden">
                            <img src="' . getThemeGFXPath() . 'downarrow.png" alt="Naar beneden" border="0" height="16" width="16">
                          </a>
                          ' : '&nbsp;' ) . '
                        </span>
                        <span>
                          ' . ( $ctr > 1 ? '
                          <a href="#reorder" onclick="return saveOrder(' . $ctr . ',\'orderup\')" title="Naar boven">
                            <img src="' . getThemeGFXPath() . 'uparrow.png" alt="Naar boven" border="0" height="16" width="16">
                          </a>
                          ' : '&nbsp;' ) .'
                        </span>
                        <input id="oid' . $ctr . '" name="order[]" size="5" value="' . $page_info->orderID . '" class="text_area" style="text-align: center;" type="text">
                      </td>
                    </tr>';

      }

      return $output;
    } else {
      return '<tr><td>Er zijn nog geen pagina\'s ingevoerd.</td></tr>';
    }*/
  }

  function getModuleID($pageID)
  {
    $sql = "SELECT `moduleID`
              FROM `" . $this->db->dbprefix('_content') . "`
             WHERE `pageID` = " . (int)$pageID . "
           ";

    $query = $this->db->query($sql);

    return $query->row()->moduleID;
  }

  function getModuleLabel($pageID)
  {
    $moduleID = $this->getModuleID($pageID);

    $sql = "SELECT `label`
              FROM `" . $this->db->dbprefix('_module') . "`
             WHERE `moduleID` = " . (int)$moduleID . "
           ";

    $query = $this->db->query($sql);

    return $query->row()->label;
  }

  function getModuleIcon($pageID)
  {
    $img = $this->getModuleID($pageID) > 0 ? '<img src="' . getThemeGFXPath() . 'accept.png" alt="Module" title="' . $this->getModuleLabel($pageID) . '" />' : '';

    return $img;
  }

  function getModules()
  {
    $sql = "SELECT `moduleID`,
                   `label`
              FROM `" . $this->db->dbprefix('_module') . "`
             WHERE `active` = '1'
           ";

    $query = $this->db->query($sql);

    foreach ($query->result_array() as $row)
    {
       $data[$row['moduleID']] = stripslashes($row['label']);
    }

    return $data;
  }

  function getPageInfo($pageID)
  {
    $sql = "SELECT `pageID`,
                   `title`,
                   `active`,
                   `content`
              FROM `" . $this->db->dbprefix('_content') . "`
             WHERE `pageID` = " . (int)$pageID . "
           ";

    $query = $this->db->query($sql);

    return $query->row();
  }

  function countPages()
  {
    $sql = "SELECT COUNT(*) AS `totalPages`
              FROM `" . $this->db->dbprefix('_content') . "`
             WHERE `deleted` = '0'
           ";

    $query = $this->db->query($sql);

    return $query->row()->totalPages;
  }

  function setDefault($cid)
  {
    $sql = "UPDATE `" . $this->db->dbprefix('_content') . "`
               SET `default` = '0'
             WHERE `default` = '1'
           ";

    if($this->db->query($sql)) {

      $sql = "UPDATE `" . $this->db->dbprefix('_content') . "`
                 SET `default` = '1'
               WHERE `pageID` = " . (int)current($cid) . "
             ";

      if($this->db->query($sql)) {
        redirect(current_url());
      }

    }

  }

  function publishPage($cid)
  {
    foreach ($cid as $row) {

      $sql = "UPDATE `" . $this->db->dbprefix('_content') . "`
                 SET `active` = '1'
               WHERE `pageID` = " . (int)$row . "
             ";

      $this->db->query($sql);

    }

    redirect(current_url());
  }

  function unpublishPage($cid)
  {
    foreach ($cid as $row) {

      $sql = "UPDATE `" . $this->db->dbprefix('_content') . "`
                 SET `active` = '0'
               WHERE `pageID` = " . (int)$row . "
             ";

      $this->db->query($sql);

    }

    redirect(current_url());
  }

  function getParentPages()
  {
    $sql = "SELECT `pageID`,
                   `title`
              FROM `" . $this->db->dbprefix('_content') . "`
             WHERE `parentID` = 0
          ORDER BY `orderID`
           ";

    $query = $this->db->query($sql);

    $data [''] = '';

    foreach ($query->result_array() as $row)
    {
       $data[$row['pageID']] = stripslashes($row['title']);
    }

    return $data;
  }

  function orderPage($hid, $orderid)
  {
    foreach($hid as $key => $pageID)
    {
      $sql = "UPDATE `" . $this->db->dbprefix('_content') . "`
                 SET `orderID` = " . (int)$orderid[$key] . "
               WHERE `pageID` = " . (int)$pageID . "
             ";
      $this->db->query($sql);
    }
    redirect(current_url());
  }

  function getOrderID($cid)
  {
    $sql = "SELECT `orderID`
              FROM `" . $this->db->dbprefix('_content') . "`
             WHERE `pageID` = " . (int)$cid . "
           ";
    $query = $this->db->query($sql);

    return $query->row()->orderID;
  }

  function getOrderPage($cid, $dir)
  {
    $orderID = ( $dir == 'up' ? $this->getOrderID($cid) + 1 : $this->getOrderID($cid) - 1 );

    $sql = "SELECT `pageID`
              FROM `" . $this->db->dbprefix('_content') . "`
             WHERE `orderID` = " . (int)$orderID . "
           ";

    $query = $this->db->query($sql);

    return $query->row()->pageID;
  }

  function showUpArrow($row_nr, $cid)
  {
    if($this->isChild($cid)) {

      if($this->getOrderID($cid) == $this->getHighestOrderID($cid)) {

        return false;
      }
    }

    if($row_nr == $this->countPages()) {

      return false;
    }

    return true;
  }

  function getHighestOrderID($cid)
  {
    $sql = "SELECT `orderID`
              FROM `" . $this->db->dbprefix('_content') . "`
             WHERE `parentID` = " . (int)$this->getParentID($cid) . "
          ORDER BY `orderID` DESC
             LIMIT 1
           ";

    $query = $this->db->query($sql);

    if($query = $this->db->query($sql)) {

      return $query->row()->orderID;
    }

    return false;
  }

  function getParentID($cid)
  {
    $sql = "SELECT `parentID`
              FROM `" . $this->db->dbprefix('_content') . "`
             WHERE `pageID` = " . (int)$cid . "
           ";

    if($query = $this->db->query($sql)) {

      return $query->row()->parentID;
    }

    return false;
  }

  function isChild($cid)
  {
    $sql = "SELECT `parentID`
              FROM `" . $this->db->dbprefix('_content') . "`
             WHERE `pageID` = " . (int)$cid . "
           ";

    $query = $this->db->query($sql);

    if($query->row()->parentID == 0) {

      return false;
    }

    return true;
  }

}
?>