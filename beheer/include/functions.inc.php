<?php
include_once ( dirname ( __FILE__ ) . '/../init.inc.php' );


function myPrint($value, $title = NULL) {
   print '<pre style="background-color:#c4c5ff;border:1px solid #FF0000">';
   if(isset($title)) {
     print ( '<div style="background-color:#9898fc; padding-left: 5px;">' . $title . '</div>');
   }
   print_r( setPrefix( $value ) );
   print '</pre>';
}

function breadCrumb ( $pageID, $return = Array () )
{
	if ( $pageID > 0 )
	{
		$sql = "SELECT
						`pageID`,
						`parentID`,
						`title`
					FROM
						`[PREFIX]_content`
					WHERE
						`pageID` = " . (int)$pageID . ";";
		if ( ( $res = Xmysql_query ( $sql ) ) && ( $row = mysql_fetch_assoc ( $res ) ) )
		{
			if ( $row [ 'parentID' ] > 0 )
				$return = breadCrumb ( $row [ 'parentID' ], $return );

			unset ( $row [ 'parentID' ] );

			$return [ ] = $row;
		}
	}
	return $return;
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

function authorized ()
{
  if( false && $_SERVER['REMOTE_ADDR'] == '81.204.170.205')
    return true;

	if ( in_array ( basename ( $_SERVER [ 'PHP_SELF' ] ), Array ( 'index.php', 'ibrowser.php' ) ) )
	{
		$sql = "SELECT 
						`userID`
					FROM
						`[PREFIX]_users`
					WHERE
						`session` = '" . addslashes ( session_id () ) . "'
					AND
						`sessionTimeout` >= NOW() ;";
		if ( mysql_num_rows ( $res = Xmysql_query ( $sql ) ) == 1 )
		{
			define ( 'USERID', (int)mysql_result ( $res, 0, 0 ) );
			$sql = "UPDATE
							`[PREFIX]_users`
						SET
							`sessionTimeout` = '" . date ( 'Y-m-d H:i:s', mktime () + SESS_TIMEOUT ) . "'
						WHERE
							`userID` = " . USERID . ";";
			Xmysql_query ( $sql );
			return true;
		}
	}
	else
	{
		session_destroy ();
		header ( 'Location: ' . ADMIN_PATH . 'index.php' );
		die ();
	}
	return false;
}

function uploadFile($source, $filename, $resize, $org)
{
	
	ini_set ( 'memory_limit', '32M' );

	$size = getimagesize($source);
	$dest_x = $resize;
	if($size[0] < $resize)
	  $dest_x = $size[0];
	
	// Als breedte > hoogte
	if($size[0] > $size[1]) {
	  $scale = $size[0]/$size[1];
	  $dest_y = $dest_x/$scale;
	}
	
	// Als breedte > hoogte
	if($size[1] > $size[0]) {
	  $scale = $size[1]/$size[0];
	  $dest_y = $dest_x; 
	  $dest_x = $dest_y/$scale;
	}
	
	$type = $size[2];
	if($type == 2)
	  $source_img = @imagecreatefromjpeg ($source);
	else if($type == 3)
	  $source_img = @imagecreatefrompng ($source);
	else if($type == 1)
	  $source_img = @imagecreatefromgif ($source);
	else
	  return false;
	
	$dest_img = imagecreatetruecolor($dest_x,$dest_y);
	imagecopyresampled($dest_img,$source_img,0,0,0,0,($dest_x+1),($dest_y+1),$size[0],$size[1]);
	imagejpeg($dest_img, IMG_PATH.$filename);
	@unlink ( IMG_PATH.'org_'.$filename );
	
	//if($org == 1)
	//	@copy ( $source, IMG_PATH.'org_'.$filename);

	ini_set ( 'memory_limit', '8M' );
}

function getCats ( $parentID, $cats = null )
{
	if ( is_null ( $cats ) )
		$cats = Array ($parentID);

	$sql = "SELECT
					`catID`
				FROM
					`[PREFIX]_cat`
				WHERE
					`parentID` = " . $parentID . ";";
	$res = Xmysql_query ( $sql );
	while ( $row = mysql_fetch_assoc ( $res ) )
	{
		$cats [] = $row [ 'catID' ];
		getCats ( $row [ 'catID' ], $cats ); //&$cats 
	}
	return $cats;
}

function getSignups()
{
  $sql = "SELECT
              *
            FROM
              `[PREFIX]_signups`
         ";
  $res = Xmysql_query($sql);

  $singups = array();

  while($row = mysql_fetch_assoc($res))
  {
    $singups [] = $row;
  }

  return $singups;
}

function getPersonInfo($personID)
{
  $sql = "SELECT
              *,
              CONCAT(`street`, ' ', `houseNr`) AS `address`
            FROM
              `[PREFIX]_persons`
           WHERE
              `personID` = ".(int)$personID."
         ";
         //myPrint($sql);
  $res = Xmysql_query($sql);

  return mysql_fetch_assoc($res);
}

function getEmail($signupID)
{
  $sql = "SELECT
              `email`
            FROM
              `[PREFIX]_signups`
           WHERE
              `signUpID` = ".(int)$signupID."
         ";
  $res = Xmysql_query($sql);

  return mysql_result($res, 0);
}

function getBirthday($signupID)
{
  $sql = "SELECT
              `datebirth`
            FROM
              `[PREFIX]_signups`
           WHERE
              `signUpID` = ".(int)$signupID."
         ";
  $res = Xmysql_query($sql);

  return mysql_result($res, 0);
}

function getSingupInfo($signupID)
{
  $sql = "SELECT
              *,
              DATE_FORMAT(`signupDate`, '%Y') AS `year`
            FROM
              `[PREFIX]_signups`
           WHERE
              `signUpID` = ".(int)$signupID."
         ";
  $res = Xmysql_query($sql);

  return mysql_fetch_assoc($res);
}

function deleteSignup($signupID) {
  $sql = "DELETE FROM
                   `[PREFIX]_signups`
                 WHERE
                   `signUpID` = ".(int)$signupID."
         ";
  if(Xmysql_query($sql))
    return true;
  return false;
}
