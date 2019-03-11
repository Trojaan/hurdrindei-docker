<?php
include_once ( dirname ( __FILE__ ) . '/../init.inc.php' );

$text = Array (
	''         => 'files/news/overview.inc.php',
	'edit'     => 'files/news/editor.inc.php',
	'del'     => 'files/news/del.inc.php',
	'notFound' => 'notFound.inc.php'
	);

$_GET [ 'action' ] = isset ( $_GET [ 'action' ] ) ? $_GET [ 'action' ] : '';

if ( array_key_exists ( $_GET [ 'action' ], $text ) && file_exists ( $text [ $_GET [ 'action' ] ] ) )
	include ( $text [ $_GET [ 'action' ] ] );
else if ( file_exists ( $text [ 'notFound' ] ) )
	include ( $text [ 'notFound' ] );

?>