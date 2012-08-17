<?php
$ts_pw = posix_getpwuid(posix_getuid());

include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");

include($ts_pw['dir'] . "/public_html/toolkit/WIKIGINI/inc/lang/".$_SESSION["lang"]."/info_txt.inc");

echo "<div id=\"Description\">";
 echo "<div id=\"Ueberschrift\">
 <h1>".$Headline."</h1>
 <p>".$Introduction."</p>
 <h2>".$Settings["Headline"]."</h2>
 <p>".$Settings["Text"]."</p>	
  <h2>".$Results["Headline"]."</h2>
 <p>".$Results["Text"]."</p>
  <h2>".$Uses["Headline"]."</h2>
 <p>".$Uses["Text"]."</p>	
<h2>".$Languages["Headline"]."</h2>
<p>".$Languages["Text"]."</p>
</div>
";
echo "</div>";



include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
?>
