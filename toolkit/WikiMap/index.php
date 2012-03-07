<?php
// WIKIMAP index.php
$ts_pw = posix_getpwuid(posix_getuid());

include($ts_pw['dir'] . "/public_html/toolkit/pages/Tablehead.php");

?>
<iframe src="http://km.aifb.kit.edu/sites/wikipediamap/" width=98% height="700px"  name="WikipediaMap_in_a_box">
  <p>Your browser does not support embedded frames:
    You can call the embedded page via this link: 
    <a href="http://km.aifb.kit.edu/sites/wikipediamap/">Wikipedia Map</a></p>
</iframe>	
<?php

include($ts_pw['dir'] ."/public_html/toolkit/pages/Tablefoot.php");
?>
