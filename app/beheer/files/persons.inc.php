<?php
include_once ( dirname ( __FILE__ ) . '/../init.inc.php' );

$text = Array (
	''         => 'files/persons/overview.inc.php',
  'del'      => 'files/persons/del.inc.php',
  'addResult'=> 'files/persons/addResult.inc.php',
  'edit'     => 'files/persons/editor.inc.php',
  'export'   => 'files/persons/export.inc.php',
  'test'     => 'files/persons/test.inc.php',
	'notFound' => 'notFound.inc.php'
	);

$_GET [ 'action' ] = isset ( $_GET [ 'action' ] ) ? $_GET [ 'action' ] : '';

//print($_GET [ 'action' ]);

if ( array_key_exists ( $_GET [ 'action' ], $text ) && file_exists ( $text [ $_GET [ 'action' ] ] ) )
	include ( $text [ $_GET [ 'action' ] ] );
else if ( file_exists ( $text [ 'notFound' ] ) )
	include ( $text [ 'notFound' ] );

?>