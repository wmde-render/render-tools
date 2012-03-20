
<?php
$ts_pw = posix_getpwuid(posix_getuid());

include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");

include($ts_pw['dir'] . "/public_html/toolkit/pages/inc/lang/".$_SESSION["lang"]."/about_txt.inc");

echo "<div id=\"Description\">";
 echo "<div id=\"Ueberschrift\"><h1>".$Headline."</h1>
 <p>".$Introduction."</p>
 </div>	
  <div id=\"info\">	
 <p>".$Text1."</p>
 <p>".$Text2."</p>
 <p>".$Text3."</p>
</div>
";
echo "</div>";


include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
?>


