<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title><?= $title ?> - Website beheer</title>
  <link type="text/css" rel="stylesheet" href="/themes/default/css/general.css" media="screen"/>
  <link type="text/css" rel="stylesheet" href="/themes/default/css/style.css" media="screen"/>
  <script src="/system/application/js/jquery-1.3.2.min.js" type="text/javascript"></script>
  <script src="/system/application/js/jquery.form.js" type="text/javascript"></script>
  <script src="/system/application/js/script.js" type="text/javascript"></script>
  <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
</head>
<body>
  <div id="mainContainer">
    <div id="header">
      <div class="logo"></div>
    </div>
    <div id="menu">
      <ul id="nav">
        <li>
          <a href="#" class="pages"></a>
          <div>
            <ul class="subMenu">
              <li><a href="<?= site_url("pages"); ?>">Paginaoverzicht</a></li>
              <li><a href="<?= site_url("settings"); ?>">Instellingen</a></li>
            </ul>
          </div>
        </li>
        <li>
          <a href="#" class="modules"></a>
          <div>
            <ul class="subMenu">
              <li><a href="#">Nieuws</a></li>
              <li><a href="#">Gastenboek</a></li>
            </ul>
          </div>
        </li>
        <li>
          <a href="#" class="users"></a>
            <ul class="subMenu">
              <li><a href="<?= site_url("users"); ?>">Gebruikers</a></li>
              <li><a href="<?= site_url("usergroups"); ?>">Gebruikersgroepen</a></li>
            </ul>
        </li>
      </ul>
      <a href="<?=site_url('user/logout')?>" class="logout"></a>
    </div>
    <div id="content">
      <div id="contentTitle">
        <?= $title ?>
      </div>
      <div id="innerContent">
