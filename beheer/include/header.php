<!doctype html public "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Beheer</title>
	<link rel="stylesheet" type="text/css" href="style.css"></link>
  <link type="text/css" rel="stylesheet" href="print.css" media="print"/>
  <script src="<?=URL?>mootools.js" type="text/javascript"></script>
  <script language="javascript" type="text/javascript" src="<?=URL?>beheer/include/script.js"></script>
  <script language="javascript" type="text/javascript" src="/tiny_mce/tiny_mce.js"></script>
  <script language="javascript" type="text/javascript">
  tinyMCE.init({
    // General options
    mode : "textareas",
    theme : "advanced",
    plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,ibrowser",


    // Theme options
    theme_advanced_buttons3_add : "ibrowser",
    theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen,smmultiupload",
    theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,ibrowser",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,

    // Example word content CSS (should be your site CSS) this one removes paragraph margins
    content_css : "css/word.css",

    // Drop lists for link/image/media/template dialogs
    template_external_list_url : "lists/template_list.js",
    external_link_list_url : "lists/link_list.js",
    external_image_list_url : "lists/image_list.js",
    media_external_list_url : "lists/media_list.js",

    // Replace values for the template plugin
    template_replace_values : {
      username : "Some User",
      staffid : "991234"
    }

  });
  </script>
<script type="text/javascript">
function validateMatchCheckBoxes() {
    var matchCheckboxes = document.getElementsByName("match[]");
    var personmMatchCheckboxes = document.getElementsByName("personMatch[]");
    var checked = false;
    for (var i = 0; i < matchCheckboxes.length; i++) {
        if(matchCheckboxes[i].checked){
            checked = true;
        }
        }
        if (!checked) {
            alert("Geef met de vinkjes (voor bv Voornaam + Achternaam) aan op welke velden er gemacht moet worden!");
            return false;
        }
    }


  function openWindow(url)
  {
    window.open(url,'popup','width=510,height=510,directories=0,location=0,menubar=0,status=0,titlebar=0,toolbar=0');
    return false;
  }
</script>
</head>

<body>

<div id="mainContainer">
	<div id="header">
	</div>
	<div id="left">
		<div id="logo">
			<img src="gfx/logo.gif" alt="" />
		</div>
		<div id="menu">
			<h3>Tekst beheer</h3>
				<!--<a href="index.php?page=text&action=new">Pagina toevoegen</a>-->
				<a href="index.php?page=text">Pagina overzicht</a>
			<h3>Aanmeldingen</h3>
				<a href="index.php?page=signups">Aanmeldingen overzicht</a>
        <a href="index.php?page=signups&amp;action=confirm">Verwerkte matches bevestigen</a>
      <h3>Personen</h3>
				<a href="index.php?page=persons">Personen overzicht</a>
				<a href="index.php?page=persons&amp;action=edit&amp;personID=0">Persoon toevoegen</a>
				<a href="index.php?page=persons&amp;action=export">Adreslijst exporteren</a>
      <h3>Resultaten</h3>
				<a href="index.php?page=signupList">Resultaat toevoegen</a>
				<a href="index.php?page=results">Resultaten overzicht</a>
      <!--<h3>Nieuws</h3>
				<a href="index.php?page=news">Nieuws overzicht</a><br/>
				<a href="index.php?page=logout">Logout</a>-->
		</div>
	</div>
	<div id="right">