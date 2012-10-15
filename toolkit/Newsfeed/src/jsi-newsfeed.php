<?php
function getNewsArticles( $title ) {
	$title = urlencode( $title );
	$url = "http://newsfeed.ijs.si/query/news-search?cu=http://en.wikipedia.org/wiki/" . $title;
	$result = file_get_contents( $url );
	$news = json_decode( $result );

	if ( isset( $news->error ) ) {
		return -1;
	}
	return $news->articles;
}

function shortenUrl( $url ) {
	$parts = parse_url($url);
	if (array_key_exists("host", $parts) ) {
		return $parts["host"];
	}
	
	return $url;
}
?>
<div id="Ueberschrift">
	<div id="Introduction">
		<h2><?php echo $txtNewsfeed["headline"]; ?></h2>
		<p><?php echo $txtNewsfeed["description"]; ?></p>
		<div class="formDiv">
			<?php echo $txtNewsfeed["descTitle"]; ?>
			<form action="" name="newsfeedForm" method="post">
				<label for="articleTitle"><?php echo $txtNewsfeed["labelTitle"]; ?></label>
				<input type="text" id="articleTitle" name="title" />
				<button type="submit" name="btnSubmit"><?php echo $txtNewsfeed["btnSearch"]; ?></button>
			</form>
		</div>
	</div>
</div>
<?php if( isset( $_REQUEST['btnSubmit'] ) ): ?>
	<?php if( !empty( $_REQUEST['title'] ) ): ?>
		<?php $result = getNewsArticles( $_REQUEST['title'] ); ?>
		<?php if ( is_array( $result ) ): ?>
			<div id="Ergebnis">
				<table class="nfResult">
					<tbody>
						<tr style="background: #0047AB; color:white;">
							<th style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;"><?php echo $txtNewsfeed["resultColTitle"]; ?></th>
							<th style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;"><?php echo $txtNewsfeed["resultColLink"]; ?></th>
						</tr>
			<?php foreach( $result as $item ): ?>
						<tr>
							<td><?php echo $item->title; ?></td>
							<td style="vertical-align: top;">
								<a href="<?php echo $item->uri; ?>" target="_blank">
									<?php echo shortenUrl( $item->uri ); ?>
								</a>
							</td>
						</tr>
			<?php endforeach; # item loop ?>
					</tbody>
				</table>
			</div>
		<?php else: # no news data found ?>
			<div id="Errormessage" style="clear: both;">
				<span><?php echo $txtNewsfeed['errNoNews']; ?></span>
			</div>
		<?php endif; ?>
	<?php else: # no article title given ?>
			<div id="Errormessage" style="clear: both;">
				<span><?php echo $txtNewsfeed['errNoTitle']; ?></span>
			</div>
	<?php endif; ?>
<?php endif; ?>
