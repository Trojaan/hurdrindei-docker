<?php
@mysql_connect ( DB_HOST, DB_USER, DB_PASS ) or die ();
@mysql_select_db ( DB_DB ) or die ();

function setPrefix ( $sql )
{
  return str_replace ( '[PREFIX]', DB_PREFIX, $sql );
}

function Xmysql_query ( $sql )
{
  $res = mysql_query ( setPrefix ( $sql ) ) or die ( mysql_error () . '<br />' . "\r\n" . $sql );
  return $res;
}

function getMenuItems ( $parentID )
{
	$sql = "SELECT
					C.`pageID`,
					C.`parentID`,
					C.`title`,
					IF (
						COUNT(C1.`pageID`) > 0,
						1,
						0
					) AS `hasSubs`
				FROM
					`[PREFIX]_content` AS C
				LEFT JOIN
					`[PREFIX]_content` AS C1
				ON
					C.`pageID` = C1.`parentID`
				WHERE
					C.`parentID` = " . (int)$parentID . "
				GROUP BY
					C.`pageID`;";
	return Xmysql_query ( $sql );
}

function createSitemap ( $parentID )
{
	$sql = "SELECT
					C.`pageID`,
					C.`parentID`,
					C.`title`,
					IF (
						COUNT(C1.`pageID`) > 0,
						1,
						0
					) AS `hasSubs`
				FROM
					`[PREFIX]_content` AS C
				LEFT JOIN
					`[PREFIX]_content` AS C1
				ON
					C.`pageID` = C1.`parentID`
				WHERE
					C.`parentID` = " . (int)$parentID . "
				GROUP BY
					C.`pageID`;";
	return Xmysql_query ( $sql );
}

function getContent ( $pageID )
{
	$sql = "SELECT 
					`content`,
					`title`
				FROM
					`[PREFIX]_content`
				WHERE
					`pageID` = " . (int)$pageID . "";

	$query =  Xmysql_query ( $sql );

	if( mysql_num_rows($query) > 0 )
	{
		$row = mysql_fetch_assoc($query);
		
		return "<h2>" . stripslashes($row['title']) . "</h2>
				<p>" . stripslashes($row['content']) . "</p>";
	}
}

function menuIsActive ( $pageID )
{
	$parentID = $_GET [ 'pageID' ];
	
	if ( $_GET [ 'pageID' ] == $pageID )
		return true;

	else while ( $parentID > 0 )
	{
		$sql = "SELECT
						C.`parentID`
					FROM
						`[PREFIX]_content` AS C
					WHERE
						C.`pageID` = " . (int)$parentID . ";";
		$parentID = mysql_result ( Xmysql_query ( $sql ), 0, 0  );
		if ( $parentID == $pageID ) {
			return true;
		}
		
	}

	return false;
}

function newsLayout () {

	$sql2 = "SELECT
				`newsID`,
				`title`,
				`intro`
			FROM
				`[PREFIX]_news`
			ORDER BY
				`date` DESC,
				`newsID` DESC";

	if ( $_GET['page'] == 'news' && $_GET['newsID'] < 1 )
	{
		$newsDetail = false;
	}

	else if ((int)$_GET['newsID'] > 0)
	{
		$sql = "SELECT
					`newsID`,
					`title`,
					`intro`
				FROM
					`[PREFIX]_news` 
				ORDER BY
					`date` DESC,
					`newsID` DESC
				LIMIT
					0, 10";
		
		$res = Xmysql_query ( $sql );

		$news = '<h1> Nieuwsoverzicht </h1>';

		while ( $row = mysql_fetch_assoc ( $res ) )
		{
			$news  .= '<a href="?page=news&amp;newsID='.$row [ 'newsID' ].'">'.$row [ 'title' ].'</a><br />';
		}

		$newsarchiveLink = true;
	}
	else {
		
		$sql = "SELECT
					`newsID`,
					`title`,
					`intro`
				FROM
					`[PREFIX]_news` 
				ORDER BY
					`date` DESC,
					`newsID` DESC
				LIMIT 2";
		
		$res = Xmysql_query ( $sql );

		if ( mysql_num_rows ( $res ) > 0)
		{
			$news = '<h1> Nieuws </h1>';
		}

		while ( $row = mysql_fetch_assoc ( $res ) )
		{
			$news  .= '<h3>'. stripslashes ( $row [ 'title' ] ).'</h3>'. stripslashes ( $row [ 'intro' ] );
			$news  .= '<a href="?page=news&amp;newsID='.$row [ 'newsID' ].'"> >> Lees verder</a>';
			$news  .= '<hr class="horz_line"></hr>';
		}
	}

	$res2 = Xmysql_query ( $sql2 );

	if ( mysql_num_rows ( $res2 ) > 2  &&  $newsarchiveLink == true)
	{
		$news .= '<br /><a href="?page=news"> >>> Nieuwsarchief </a>';
	}
	return $news;
}

function checkmail($mail) 
{ 
    if ($email_host && eregi("^[0-9a-z]([-_.~]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$",$mail)) 
        $valid = 1; 

    return $valid; 
}
?>