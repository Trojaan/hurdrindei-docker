<?

function getRows ($sql)
{
	$res = mysql_query($sql) or die(mysql_error());
	if (mysql_num_rows($res) > 0)
	{
		while ($row = mysql_fetch_assoc($res))
		{
			$data[] = $row;
		}
	}
 
	return $data;
}

function buildMenu($parentID = 0) {

	$menu = getRows("SELECT * FROM `[PREFIX]_content` WHERE `active` = '1' AND`parentID` = ".(int)$parentID);
	if (!is_array($menu))
		return false;

  ob_start();
?>
	<div id="menu">
<?php
	foreach ($menu as $item)
	{
?>
		<div <?= $item['parentID'] == 0 ? 'id="parent"'?>>
		  <a href="index.php?pageID=<?=$item['pageID']?>"><?=$item['title']?>'</a>
		    <?= buildMenu($item['pageID']); ?>
		</li>
<?php
	}
?>
	</div>
<?php
	return ob_end_clean();
}


/* 
	foreach ($menu as $item)
	{
		$html .= '<li>';
		$html .= '<a href="index.php?pageID='.$item['pageID'].'">'.$item['title'].'</a>';
		$html .= buildMenu($item['pageID']);
		$html .= '</li>';
	}
 
	$html .= '</ul>';

*/

?>
