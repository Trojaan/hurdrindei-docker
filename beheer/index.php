<?php
if ( !headers_sent () )
{
	include_once ( 'init.inc.php' );
	ob_start ();

	$files = Array (
		''           => 'files/signups.inc.php',
		'login'      => 'files/login.inc.php',
		'logout'     => 'files/logout.inc.php',
		'signups'    => 'files/signups.inc.php',
		'signupList' => 'files/signupList.inc.php',
		'persons'    => 'files/persons.inc.php',
		'results'    => 'files/results.inc.php',
		'text'       => 'files/text.inc.php',
		'dateParser' => 'files/dateParser.inc.php',
        'news'       => 'files/news.inc.php',
		'notFound'   => 'notFound.inc.php',
		);

	$_GET [ 'page' ] = isset ( $_GET [ 'page' ] ) ? $_GET [ 'page' ] : '';

	if ( array_key_exists ( $_GET [ 'page' ], $files ) && file_exists ( $files [ $_GET [ 'page' ] ] ) )
		include ( $files [ $_GET [ 'page' ] ] );
	else if ( file_exists ( $files [ 'notFound' ] ) )
		include ( $files [ 'notFound' ] );


	mysql_close ();
	ob_flush ();
}
die ();
?>