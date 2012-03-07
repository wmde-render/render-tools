<?php
session_start();

if (isset($_GET["lang"])){ $lang = $_GET["lang"];
			$_SESSION["lang"] = $_GET["lang"];
			
} else { 
	if (isset($_SESSION["lang"])) {
	
		$lang = $_SESSION["lang"];		
	} else {
		$lang = "en";
		$_SESSION["lang"] = "en";
		}
}

// parse toolserver account out of request uri 
// to make sure the toolkit can run under different accounts
$tsAccount = substr($_SERVER['REQUEST_URI'], 1, strpos($_SERVER['REQUEST_URI'], '/', 1) - 1);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>RENDER-Toolkit</title>
<link rel="stylesheet" href="/<?php echo $tsAccount; ?>/toolkit/formate.css" type="text/css">
</head>

<body >
<?php




if ($lang == "en"){

?>

<div id="Language"><a href="/<?php echo $tsAccount; ?>/toolkit/index.php?lang=de"><small>Deutsch: </small><img 
src="/<?php echo $tsAccount;?>/toolkit/img/128px-Flag_of_Germany_(3-2_aspect_ratio).svg.png" border="0" width="24px"  alt=""></a></div>
	
  <div id="Headline">	
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
	<td width="80"><a href="http://wikimedia.de/wiki/Hauptseite" target="_blank"><img src="/<?php echo $tsAccount; ?>/toolkit/img/180px-Wikimedia_Deutschland_Logo.png" width="60px" border="0" alt=""></a></td>
	<td width="95%"><h1>RENDER-Toolkit</h1></td>
	<td width="80"><a href="http://render-project.eu/" target="_blank"><img src="/<?php echo $tsAccount; ?>/toolkit/img/Logo_trans.PNG" width="60px" border="0" alt=""></a></td>
	</tr>
	</table>
	</div>	
			
	
 
 <div id="Overline">&nbsp;</div>
 	
  <div id="Rahmen"><ul id="Navigation">

    <li id="First"><a href="/<?php echo $tsAccount; ?>/toolkit/LEA/">LEA</a>
      <ul>
        <li><a href="/<?php echo $tsAccount; ?>/toolkit/LEA/info.php">Description</a></li>
      </ul>
    </li>

    <li><a href="/<?php echo $tsAccount; ?>/toolkit/ChangeDetector/">Change Detector</a>
	<ul>
        <li><a href="/<?php echo $tsAccount; ?>/toolkit/ChangeDetector/info.php">Description</a></li>
      </ul>
     </li> 
    <li><a href="/<?php echo $tsAccount; ?>/toolkit/WikiMap/">Wikipedia Map</a>
      <ul>

        <li><a href="/<?php echo $tsAccount; ?>/toolkit/WikiMap/info.php">Description</a></li>
      </ul>
    </li>

    <li><a href="/<?php echo $tsAccount; ?>/toolkit/Corpex/">Corpex</a>
    <ul>
        <li><a href="/<?php echo $tsAccount; ?>/toolkit/Corpex/info.php">Description</a></li>
      </ul>
     </li> 
  </ul><div></div></div>
  
  <div id="Text">
	 <table cellspacing="0">
	 <tr>
	 <td id="links">&nbsp;
	 <div id="Seite">
  <ul id="Navigation">

   <li><a href="/<?php echo $tsAccount; ?>/toolkit/index.php">Home</a></li>
  <li ><a href="/<?php echo $tsAccount; ?>/toolkit/pages/About.php">About us</a></li>
  <li ><a href="/<?php echo $tsAccount; ?>/toolkit/pages/Contact.php">Contact</a></li>
  </ul>
  </div>
	 </td>
	 <td id="Inhalt">


<?php
}
if($lang == "de"){
?>

<div id="Language"><a href="/<?php echo $tsAccount; ?>/toolkit/index.php?lang=en"><small>Englisch: </small><img src="/<?php echo $tsAccount; ?>/toolkit/img/128px-Flag_of_the_United_Kingdom.svg.png" border="0" width="24px"  alt=""></a></div>
	
  <div id="Headline">	
	<table border="0" cellspacing="0" cellpadding="0">
	
		<tr>
	<td width="80"><a href="http://wikimedia.de/wiki/Hauptseite" target="_blank"><img src="/<?php echo $tsAccount; ?>/toolkit/img/180px-Wikimedia_Deutschland_Logo.png" width="60px" border="0" alt=""></a></td>
	<td width="95%"><h1>RENDER-Toolkit</h1></td>
	<td width="80"><a href="http://render-project.eu/" target="_blank"><img src="/<?php echo $tsAccount; ?>/toolkit/img/Logo_trans.PNG" width="60px" border="0" alt=""></a></td>	</tr>
	</table>
	</div>	
			
	
 
 <div id="Overline">&nbsp;</div>
 	
  <div id="Rahmen"><ul id="Navigation">

    <li id="First"><a href="/<?php echo $tsAccount; ?>/toolkit/LEA/">LEA</a>
      <ul>
        <li><a href="/<?php echo $tsAccount; ?>/toolkit/LEA/info.php">Erklärung</a></li>
      </ul>
    </li>

    <li><a href="/<?php echo $tsAccount; ?>/toolkit/ChangeDetector/">Change Detector</a>
	<ul>
        <li><a href="/<?php echo $tsAccount; ?>/toolkit/ChangeDetector/info.php">Erklärung</a></li>
      </ul>
     </li> 
    <li><a href="/<?php echo $tsAccount; ?>/toolkit/WikiMap/">Wikipedia Map</a>
      <ul>
        <li><a href="/<?php echo $tsAccount; ?>/toolkit/WikiMap/info.php">Erklärung</a></li>
      </ul>
    </li>

    <li><a href="/<?php echo $tsAccount; ?>/toolkit/Corpex/">Corpex</a>
    <ul>
        <li><a href="/<?php echo $tsAccount; ?>/toolkit/Corpex/info.php">Erklärung</a></li>
      </ul>
     </li> 
  </ul><div></div></div>
  
  <div id="Text" >
	 <table cellspacing="0">
	 <tr>
	 <td id="links">&nbsp;
	 <div id="Seite">
  <ul id="Navigation">

   <li ><a href="/<?php echo $tsAccount; ?>/toolkit/index.php">Start</a></li>
  <li ><a href="/<?php echo $tsAccount; ?>/toolkit/pages/About.php">Über uns</a></li>
  <li ><a href="/<?php echo $tsAccount; ?>/toolkit/pages/Contact.php">Kontakt</a></li>
  </ul>
  </div>
	 
	 
	 </td>
	 <td id="Inhalt">

<?php
}



?>
