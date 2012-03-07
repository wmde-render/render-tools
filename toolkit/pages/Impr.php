

<?php
$ts_pw = posix_getpwuid(posix_getuid());

include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");

include($ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/".$_SESSION["lang"]."/impr_txt.inc");

echo "<div id=\"Description\">";
 echo "<div id=\"Ueberschrift\"><h1>".$Headline."</h1>
 <p>".$Introduction."</p>
 </div>	
 <div id=\"info\">
 <p>".$Adress["Title"]."</p>
 <p>".$Adress["Postbox"]."</p>
 <p>".$Adress["Zip"]."</p>
 <p>&nbsp;</p>
 <p>".$Adress["Phone"]."</p>
 <p>".$Adress["Fax"]."</p>
 <p>".$Adress["Email"]."</p>
 </div>
 <div id=\"Ueberschrift\"><h2>".$Disclaimer["Head"]."</h2>
 <p>".$Disclaimer["Text1"]."</p>
 <p>".$Disclaimer["Text2"]."</p>
";
echo "</div>";



include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
?>


