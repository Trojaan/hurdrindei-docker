<?php
//include_once ( dirname ( __FILE__ ) . '/../init.inc.php' );

// Site meuk
define ( 'SITE_NAME', 'Kubaarder Hurdrindei' );
define ( 'ADMIN_PATH', 'http://www.kubaarderhurdrindei.nl/beheer/' );
//define ( 'ADMIN_PATH', 'http://www.kubaarderhurdrindei.nl/beheer/' );
define ( 'URL_ROOT', 'http://www.kubaarderhurdrindei.nl/index.php' );
define ( 'URL', 'http://www.kubaarderhurdrindei.nl/' );
define ( 'URL_UPLOAD', 'http://www.kubaarderhurdrindei.nl/beheer/upload/' );

// Database meuk
define ( 'DB_HOST', 'localhost' );
define ( 'DB_USER', 'root' );
define ( 'DB_PASS', '' );
define ( 'DB_DB', 'kubaarderhurdri' );
define ( 'DB_PREFIX', 'hurd' );


// Sessie meuk
define ( 'SESS_NAME', 'CMS_SESSION' );
define ( 'SESS_TIMEOUT', 60 * 60 * 24 );

//Afbeeldingen
define ( 'THUMB_SIZE', '140' );
define ( 'IMG_SIZE', '540' );
define ( 'IMG_PATH', '/wwwroot/beheer/upload/' );

// session
session_set_cookie_params( time() + (3600 * 24 * 7 ) );
session_start();
?>