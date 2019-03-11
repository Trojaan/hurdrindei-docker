<?php

function myPrint($value, $title = NULL) {
   print '<pre style="background-color:#c4c5ff;border:1px solid #FF0000">';
   if(isset($title)) {
     print ( '<div style="background-color:#9898fc; padding-left: 5px;">' . $title . '</div>');
   }
   print_r( $value );
   print '</pre>';
}


$myFile = "harry.potter.and.the.chamber.of.secets.2002.m-HD.x264-uSk.srt";
$fh = fopen($myFile, 'r');
$theData = fread($fh, filesize($myFile));
fclose($fh);

myprint( $theData );

?>