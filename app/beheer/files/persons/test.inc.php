<?php
/****************************************************************
* Script         : MySQL test examples for PhpSimpleXlsGen
* Project        : PHP SimpleXlsGen
* Author         : Erol Ozcan <eozcan@superonline.com>
* Version        : 0.2
* Copyright      : GNU LGPL
* URL            : http://psxlsgen.sourceforge.net
* Last modified  : 18 May 2001
******************************************************************/
include( "psxlsgen.php" );
include( "db_sxlsgen.php" );

$sql = "SELECT
            `firstName`,
            `middleName`,
            `lastName`,
            `postalcode`,
            `street`,
            `houseNr`,
            `place`
          FROM
            `hurd_results` AS R
          JOIN
            `hurd_persons` AS P
          ON
            R.`personID` = P.`personID`
          WHERE
            `year` >= 2006
          AND
            `year` <= 2008
          GROUP BY
            P.`postalcode`,
            P.`houseNr`,
            P.`place`
          ORDER BY 
            `lastName` ASC
          ";

// test 2: some variables changed
$myxls = new Db_SXlsGen;
$myxls->filename = "test2";
$myxls->get_type = 1;
$myxls->col_aliases = array( "firstName" => "Voornaam", "lastName" => "Achternaam" );
$myxls->GetXlsFromQuery( $sql );
?>
<HTML>
 <HEAD>
  <TITLE>PHP Simple Excel File Generator v<?=$myxls->db_class_ver;?> - MySQL Test Page </TITLE>
 </HEAD
 <BODY>
 <CENTER>
 <H1>PHP Simple Excel File Generator</H1>
 <H3>version <?=$myxls->db_class_ver;?></H3>
 <H3>Test 2: Generate xls from a sql query using Mysql Db</H3>
 </CENTER>
 <b><?=$myxls->fname;?></b> has been generated.<br><br>
</BODY>
</HTML>