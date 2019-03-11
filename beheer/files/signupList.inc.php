<?php
include_once ( dirname ( __FILE__ ) . '/../init.inc.php' );

$text = Array (
	''         => 'files/signupList/overview.inc.php',
	'edit'     => 'files/signupList/editor.inc.php',
  'resultList'   => 'files/signupList/resultList.inc.php',
  'del'      => 'files/signupList/del.inc.php',
	'notFound' => 'notFound.inc.php'
	);

$_GET [ 'action' ] = isset ( $_GET [ 'action' ] ) ? $_GET [ 'action' ] : '';

if ( array_key_exists ( $_GET [ 'action' ], $text ) && file_exists ( $text [ $_GET [ 'action' ] ] ) )
	include ( $text [ $_GET [ 'action' ] ] );
else if ( file_exists ( $text [ 'notFound' ] ) )
	include ( $text [ 'notFound' ] );

?>