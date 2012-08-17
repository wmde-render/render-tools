<div id="Ueberschrift" style="float: left; vertical-align: middle;">
	<div id="Introduction">
		<h2>
			<?php echo $Headline; ?>
		</h2>
		<p id="Description">
			<?php echo $Description; ?>
		</p>
		<p>
			<a href="info.php"><?php echo $MoreInfo; ?> </a>
		</p>
	</div>
	<div>
		<p align="center">
			<span id="en" onmouseover="setmap('en');">English</span> &nbsp; <span
				id="ca" onmouseover="setmap('ca');">Catalan</span> &nbsp; <span
				id="es" onmouseover="setmap('es');">Spanish</span> &nbsp; <span
				id="pt" onmouseover="setmap('pt');">Portuguese</span> &nbsp; <span
				id="fr" onmouseover="setmap('fr');">French</span> &nbsp; <span
				id="it" onmouseover="setmap('it');">Italian</span> &nbsp; <span
				id="el" onmouseover="setmap('el');">Greek</span> &nbsp; <span
				id="he" onmouseover="setmap('he');">Hebrew</span> &nbsp; <span
				id="hr" onmouseover="setmap('hr');">Croatian</span> &nbsp; <span
				id="tr" onmouseover="setmap('tr');">Turkish</span> &nbsp; <span
				id="ru" onmouseover="setmap('ru');">Russian</span> &nbsp; <span
				id="zh" onmouseover="setmap('zh');">Chinese</span>
		</p>
	</div>

	<div>
		<img id="map" src="maps/map_en_small.png" width="1000" height="500" />
	</div>

	<div>
		<h2>
			<?php echo $DynamicMap["Headline"]; ?>
		</h2>
		<p>
			<?php echo $DynamicMap["Text"]; ?>
		</p>
		<p>
			<a href="src/map.html" target="_blank">Start dynamic map</a>
		</p>
	</div>
</div>
