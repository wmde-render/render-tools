<?php
session_start();

if (isset($_GET["lang"])){ $lang = $_GET["lang"];
			$_SESSION["lang"] = $_GET["lang"];
			
} else { 
	if (isset($_SESSION["lang"])) {
	
		$lang = $_SESSION["lang"];		
	} else {
		if (strncmp($_SERVER["HTTP_ACCEPT_LANGUAGE"],"de",2) == 0){
			$lang = "de";
			$_SESSION["lang"] = "de";
		} else {
			$lang = "en";
			$_SESSION["lang"] = "en";
		}
	}	
}

// parse toolserver account out of request uri 
// to make sure the toolkit can run under different accounts
$tsAccount = substr($_SERVER['REQUEST_URI'], 1, strpos($_SERVER['REQUEST_URI'], '/', 1) - 1);

$pathtoself = $_SERVER['PHP_SELF'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="description"     content="Hier beschreiben Sie mit ein, zwei Sätzen den Inhalt dieser Datei.">
<meta name="keywords"        content="RENDER, RenderToolkit, WikipediaToolkit">
<meta name="author"         content="render@wikimedia.de">
<meta name="DC.Publisher"   content="www.wikimedia.de">
<meta name="DC.Date"        content="2012-03-20T00:00:00+01:00">
<title>RENDER-Toolkit</title>
<link rel="stylesheet" href="/<?php echo $tsAccount; ?>/toolkit/formate.css" type="text/css">
<link rel="stylesheet" href="/<?php echo $tsAccount; ?>/toolkit/jquery-ui-1.8.19.custom.css"" type="text/css">
<script type="text/javascript" src="/<?php echo $tsAccount; ?>/toolkit/js/jquery.js"></script>
<script type="text/javascript" src="/<?php echo $tsAccount; ?>/toolkit/js/jquery.fixedtable.js"></script>
<script type="text/javascript" src="/<?php echo $tsAccount; ?>/toolkit/js/jquery-ui-1.8.19.custom.min.js"></script>
<script type="text/javascript">
<?php
if ($lang == "en") {
	$btnSend = "Send";
	$btnCancel = "Cancel";
} else {
	$btnSend = "Senden";
	$btnCancel = "Abbrechen";
}
?>
	function openFeedbackDialog() {
		$("#imgCaptcha").attr("src", "pages/inc/captcha.php?id=" + Math.floor(Math.random() * 10001));
		$("#dialog-feedback").dialog('open');
	}
	
	$(function() {
		$( "#dialog-feedback" ).dialog({
			height: 410,
			width: 780,
			modal: true,
			autoOpen: false,
			buttons: {
				"<?php echo $btnSend; ?>":
					function() { 
						var $form = $( '#feedbackForm' ),
							name    = $form.find( 'input[name="name"]' ).val(),
							email   = $form.find( 'input[name="email"]' ).val(),
							page    = $form.find( 'input[name="url"]' ).val(),
							lang    = $form.find( 'input[name="lang"]' ).val(),
							captcha = $form.find( 'input[name="captcha"]' ).val(),
							comment = $form.find( 'textarea[name="comment"]' ).val(),
							url     = $form.attr( 'action' );
							
						$.post( url, { name: name, email: email, page: page, lang: lang, comment: comment, captcha: captcha },
							function(response) {
								if (response.indexOf("(ERR)") == -1) {
									$form.find('input[name="name"]').val("");
									$form.find('input[name="email"]').val("");
									$form.find('input[name="captcha"]').val("");
									$form.find('textarea[name="comment"]').val("");
								
									$('#dialog-feedback').dialog('close');
								} else {
									response = response.replace("(ERR)", "");
								}
								
								$('#dialog-feedback-submit').empty().append(response);
								$('#dialog-feedback-submit').dialog('open');
							}
						);
					},
				"<?php echo $btnCancel; ?>": 
					function() {
						var $form = $( '#feedbackForm' );
						$form.find('input[name="name"]').val("");
						$form.find('input[name="email"]').val("");
						$form.find('input[name="captcha"]').val("");
						$form.find('textarea[name="comment"]').val("");
						$(this).dialog("close");
					}
			}
 		});
		
		$( "#dialog-feedback-submit" ).dialog({
			height: 220,
			width: 370,
			modal: true,
			autoOpen: false
		});
		
		$('input[name=url]').val(document.location.href);
	});
</script>
</head>

<body >
<?php




if ($lang == "en"){

?>

<div id="Language"><a href="<?php echo $pathtoself; ?>?lang=de"><small>Deutsch: </small><img 
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

    <li id="First"><a href="/<?php echo $tsAccount; ?>/toolkit/LEA/">LEA</a></li>

    <li><a href="/<?php echo $tsAccount; ?>/toolkit/ChangeDetector/">Change Detector</a></li>
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
  <!--li ><a href="/<?php echo $tsAccount; ?>/toolkit/pages/downloads.php">Downloads</a></li-->
  <li ><a style="cursor: pointer;" onclick="javascript:openFeedbackDialog();">Feedback</a></li>
  </ul>
  </div>
	 </td>
	 <td id="Inhalt">
		<div id="dialog-feedback" title="Feedback">
			<form name="feedbackForm" id="feedbackForm" method="post" action="/<?php echo $tsAccount; ?>/toolkit/pages/feedback.php">
				<div style="float: left;">
					<label for="name">Name</label>
					<input type="text" name="name" value="" />
					<label for="email">E-Mail</label>
					<input type="text" name="email" value="" /><br />
					<img src="" id="imgCaptcha" alt="Visual CAPTCHA" />
					<label for="captcha">Captcha</label>
					<input type="text" name="captcha" />
				</div>
				<div style="float: left;">
					<label for="comment">Comment</label>
					<textarea name="comment"></textarea>
				</div>
				<input type="hidden" name="url" value="" />
				<input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
			</form>
		</div>

		<div id="dialog-feedback-submit" title="Feedback"></div>


<?php
}
if($lang == "de"){
?>

<div id="Language"><a href="<?php echo $pathtoself; ?>?lang=en"><small>Englisch: </small><img src="/<?php echo $tsAccount; ?>/toolkit/img/128px-Flag_of_the_United_Kingdom.svg.png" border="0" width="24px"  alt=""></a></div>
	
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

    <li id="First"><a href="/<?php echo $tsAccount; ?>/toolkit/LEA/">LEA</a></li>

    <li><a href="/<?php echo $tsAccount; ?>/toolkit/ChangeDetector/">Change Detector</a></li>
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
  <!--li ><a href="/<?php echo $tsAccount; ?>/toolkit/pages/downloads.php">Downloads</a></li-->
  <li ><a style="cursor: pointer;" onclick="javascript:openFeedbackDialog();">Feedback</a></li>
  </ul>
  </div>
	 
	 
	 </td>
	 <td id="Inhalt">
		<div id="dialog-feedback" title="Feedback">
			<form name="feedbackForm" id="feedbackForm" method="post" action="/<?php echo $tsAccount; ?>/toolkit/pages/feedback.php">
				<div style="width: 50%; float: left;">
					<label for="name">Name</label>
					<input type="text" name="name" value="" />
					<label for="email">E-Mail</label>
					<input type="text" name="email" value="" /><br />
					<img src="" id="imgCaptcha" alt="Visual CAPTCHA" />
					<label for="captcha">Captcha</label>
					<input type="text" name="captcha" />
				</div>
				<div style="width: 50%; float: left;">
					<label for="comment">Kommentar</label>
					<textarea name="comment"></textarea>
				</div>
				<input type="hidden" name="url" value="" />
				<input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
			</form>
		</div>

		<div id="dialog-feedback-submit" title="Feedback"></div>

<?php
}



?>
