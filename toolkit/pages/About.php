
<?php
$ts_pw = posix_getpwuid(posix_getuid());

include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");

include($ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/".$_SESSION["lang"]."/about_txt.inc");

echo "<div id=\"Description\">";
 echo "<div id=\"Ueberschrift\"><h1>".$Headline."</h1>
 <p>".$Introduction."</p>
 </div>	
";
echo "</div>";


include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
?>


