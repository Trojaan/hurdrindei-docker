<?php

$_GET['pageID'] = isset($_GET['pageID']) ?  $_GET['pageID'] : 6 ;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
  <title><?= SITE_NAME ?></title>
  <meta http-equiv="Content-Type" content="text/html"/>
  <meta name="verify-v1" content="Fz4GV6TbUCZSQ3PfWUItc58rO7C1xA4vCYnyzK/0D1g=" />  
  <link type="text/css" rel="stylesheet" href="<?= URL ?>layout/style1.css" media="screen"/>
  <link type="text/css" rel="stylesheet" href="<?= URL ?>layout/style.css" media="screen"/>
  <link type="text/css" rel="stylesheet" href="<?= URL ?>layout/print.css" media="print"/>
  <script src="<?=URL?>mootools.js" type="text/javascript"></script>
  <script src="<?=URL?>script.js" type="text/javascript"></script>
  <script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
  </script>
  <script type="text/javascript">
    var pageTracker = _gat._getTracker("UA-2057664-2");
    pageTracker._trackPageview();
  </script>
</head>
<body>
  <div id="mainContainer">
    <div id="header">
      <div id="topText">
      </div>
      <div id="infoField">
      </div>
      <div id="logo">
        <img src="/layout/images/hurd_logo.gif"/>
      </div>
    </div>
    <div id="topMenu">
      <?=buildMenu();?>
    </div>
    <div id="outerContent">
      <div id="innerContent">
        <div id="contentHeader">
<?php
  $sql = "SELECT
             `title`
            FROM
             `[PREFIX]_content`
           WHERE
             `pageID` = ".(int)$_GET['pageID']."
         ";
 $res = Xmysql_query($sql);

 $title = stripslashes(@mysql_result($res, 0));

 if($_GET['page'] == 'signup')
   $title = 'Aanmelden';
 else if($_GET['page'] == 'contact')
   $title = 'Contact';
 else if($_GET['page'] == 'results')
   $title = 'Uitslagen';

?>
        <h1><?=$title?></h1>
        </div>
        <div id="content">