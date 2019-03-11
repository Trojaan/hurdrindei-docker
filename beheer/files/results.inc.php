<?php
include_once ( dirname ( __FILE__ ) . '/../init.inc.php' );

$text = Array (
	''         => 'files/results/overview.inc.php',
	'edit'     => 'files/results/editor.inc.php',
  'del'     => 'files/results/del.inc.php',
	'notFound' => 'notFound.inc.php'
	);

$_GET [ 'action' ] = isset ( $_GET [ 'action' ] ) ? $_GET [ 'action' ] : '';

if ( array_key_exists ( $_GET [ 'action' ], $text ) && file_exists ( $text [ $_GET [ 'action' ] ] ) )
	include ( $text [ $_GET [ 'action' ] ] );
else if ( file_exists ( $text [ 'notFound' ] ) )
	include ( $text [ 'notFound' ] );

?>