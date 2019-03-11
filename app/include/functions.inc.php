<?php
function ip() {
 $result = false;

 $ip[] = '213.197.214.101';
 if ((in_array($_SERVER['REMOTE_ADDR'],$ip) ) ) {
   $result = true;
 }
 return $result;
}

function myPrint($value, $title = NULL) {
   print '<pre style="background-color:#c4c5ff;border:1px solid #FF0000">';
   if(isset($title)) {
     print ( '<div style="background-color:#9898fc; padding-left: 5px;">' . $title . '</div>');
   }
   print_r( setPrefix( $value ) );
   print '</pre>';
}


function setPrefix ( $sql )
{
	return str_replace ( '[PREFIX]', DB_PREFIX, $sql );
}

function Xmysql_query ( $sql )
{
	$res = mysql_query ( setPrefix ( $sql ) ) or die ( mysql_error () . '<br />' . "\r\n" . $sql );
	return $res;
}

function isValidEmail($email){
    return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

function checkDateFormat($date)
{
  //match the format of the date
  if (preg_match ("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $date, $parts))
  {
     return true;
  }
  else
    return false;
}

function buildMenu() {
  $sql = "SELECT
             *
            FROM
              `[PREFIX]_content`
           WHERE
              `active` = '1'
         ";
  $res = Xmysql_query($sql);
  ob_start();
?>
      <ul>
<?php
if(mysql_num_rows($res) > 0)
{

  while($menu = mysql_fetch_assoc($res))
  {
?>
        <li>
          <a <?= $_GET['pageID'] == $menu['pageID'] && $_GET['page'] != 'signup' ? 'class="active"' : '' ?> href="/<?=$menu['pageID']?>/<?=stripslashes($menu['title'])?>.html"><?=stripslashes($menu['title'])?></a>
        </li>
<?php
  }
}
?>
      </ul>
<?php
	return ob_get_clean();
}

function setPage($pageID)
{
  $sql = "SELECT
              `is_text`,
              `fileLabel`
            FROM
              `[PREFIX]_content`
           WHERE
              `pageID` = ".(int)$pageID."
         ";
  $res = Xmysql_query($sql);

  if(mysql_num_rows($res) > 0) {
    $row = mysql_fetch_assoc($res);
    if($row['is_text'] == '0')
      return $_GET [ 'page' ] = $row['fileLabel'];
  }

  return false;
}

?>