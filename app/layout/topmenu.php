<?php

$sql = "SELECT
           *
          FROM
            `[PREFIX]_content`
         WHERE
            `active` = '1'
       ";
$res = Xmysql_query($sql);
?>
      <ul>
<?php
if(mysql_num_rows($res) > 0)
{
  while($menu = mysql_fetch_assoc($res))
  {
?>
        <li>
          <a <?= $_GET['pageID'] == $menu['pageID'] ? 'class="active"' : '' ?> href="index.php?pageID=<?=$menu['pageID']?>"><?=stripslashes($menu['title'])?></a>
        </li>
<?php
  }
}
?>
      </ul>