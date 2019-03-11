<?php
include_once ( dirname ( __FILE__ ) . '/include/config.inc.php' );
include_once ( dirname ( __FILE__ ) . '/include/functions.inc.php' );

// Sessie
session_name ( SESS_NAME );
session_cache_expire ( SESS_TIMEOUT );
session_start ();

// DBconnectie
@mysql_connect ( DB_HOST, DB_USER, DB_PASS ) or die (mysql_error());
@mysql_select_db ( DB_DB ) or die (mysql_error());

if ( !authorized () )
{
  if ( $_GET [ 'page' ] != 'login' )
		header ( 'Location: ' . ADMIN_PATH . 'index.php?page=login' );

	$_GET [ 'page' ] = 'login';
}
?>