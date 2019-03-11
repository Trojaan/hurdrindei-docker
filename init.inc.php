<?php
include_once ( dirname ( __FILE__ ) . '/beheer/include/config.inc.php' );
include_once ( dirname ( __FILE__ ) . '/include/functions.inc.php' );
require( dirname ( __FILE__ ) . '/include/class.phpmailer.php' );

// require ( dirname ( __FILE__ ) . '/include/phpmailer/Exception.php');
// require ( dirname ( __FILE__ ) . '/include/phpmailer/PHPMailer.php');
// require ( dirname ( __FILE__ ) . '/include/phpmailer/SMTP.php');
// require ( dirname ( __FILE__ ) . '/include/phpmailer/OAuth.php');

// Sessie
//session_name ( SESS_NAME );
//session_cache_expire ( SESS_TIMEOUT );
//session_start ();

// DBconnectie
@mysql_connect ( DB_HOST, DB_USER, DB_PASS ) or die ();
@mysql_select_db ( DB_DB ) or die ();

?>