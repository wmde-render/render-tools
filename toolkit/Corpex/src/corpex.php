
<div id="Ueberschrift"
	style="float: left; vertical-align: middle;">
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
		<div>
			<form action="" autocomplete="off">
				<select name="lang" id="lang">
					<option>simple</option>
					<option>brown</option>
					<option>bg</option>
					<option>bs</option>
					<option>cs</option>
					<option>de</option>
					<option>en</option>
					<option>es</option>
					<option>fr</option>
					<option>hr</option>
					<option>it</option>
					<option>ro</option>
					<option>sh</option>
					<option>sq</option>
					<option>sr</option>
					<option>sv</option>
				</select> <input type="text" name="q" id="q"></input> (<span
					id="freq">?</span>)
			</form>
		</div>
	</div>
	<div id="words">
		<div id="wordbar"></div>
		<div id="wordshow">[data]</div>
		<div id="wordlist"></div>
	</div>
	<div id="wordpie"></div>
	<div class="span-6 prepend-top" id="chars">
		<div id="charbar"></div>
		<div id="charshow">[data]</div>
		<div id="charlist"></div>
	</div>
	<div id="charpie"></div>
	<!-- <img
	src="/<?php echo $tsAccount; ?>/toolkit/Corpex/res/spinner.gif"
	id="spinner" /> -->

	<div id="bigramrow">
		<div id="bigrams">
			<div id="bigrambar"></div>
			<div id="bigramshow">[data]</div>
			<div id="bigramlist"></div>
		</div>
		<div class="span-6 prepend-top last" id="bigrampie"></div>
		<!-- 	<img src="/<?php echo $tsAccount; ?>/toolkit/Corpex/res/spinner.gif"
		id="bigramspinner" /> -->
	</div>
</div>
