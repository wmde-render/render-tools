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

function getEnglishTitle( $title, $lang ) {
	$post = array(
		'action' => 'query',
		'format' => 'php',
		'titles' => $title,
		'prop' => 'langlinks',
		'lllimit' => '500'
	);
	$post = http_build_query($post);
	
	$defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => 'wmde-render-newsfinder/1.00',
        CURLOPT_POSTFIELDS => $post
    );

	$url = 'http://' . $lang . '.wikipedia.org/w/api.php?';
	$ch = curl_init($url);
    curl_setopt_array($ch, $defaults);
    if( !$result = curl_exec( $ch ) ) { 
		trigger_error( curl_error( $ch ) );
    }
    curl_close($ch);

	if( preg_match( '@"missing"@', $result ) ) {
		return null;
	}

	$resultArray = unserialize( $result );
	
	if( isset( $resultArray ) ) {
		foreach( $resultArray['query']['pages'] as $page ) {
			foreach( $page['langlinks'] as $langlink ) {
				if ( $langlink['lang'] == 'en' ) {
					return str_replace( " ", "_", $langlink['*'] );
				}
			}
		}
	}
	
	return $title;
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
				<input type="text" id="articleTitle" name="title" value="<?php echo isset($_REQUEST['title']) ? htmlspecialchars($_REQUEST['title']) : ""; ?>" />
				<label for="language"><?php echo $txtNewsfeed["labelLanguage"]; ?></label>
				<input type="text" id="language" name="language" value="<?php echo isset($_REQUEST['language']) ? htmlspecialchars($_REQUEST['language']) : ""; ?>" />
				<button type="submit" name="btnSubmit"><?php echo $txtNewsfeed["btnSearch"]; ?></button>
			</form>
		</div>
	</div>
</div>
<?php if( isset( $_REQUEST['btnSubmit'] ) ): ?>
	<?php if( !empty( $_REQUEST['title'] ) ): ?>
	<?php $engTitle = ($_REQUEST['language'] == "en") ? $_REQUEST['title'] : getEnglishTitle( $_REQUEST['title'], $_REQUEST['language'] ); ?>
		<?php $result = getNewsArticles( $engTitle ); ?>
		<?php if ( is_array( $result ) ): ?>
			<div id="Ergebnis">
				<table class="nfResult">
					<tbody>
						<tr style="background: #0047AB; color:white;">
							<th style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;"><?php echo $txtNewsfeed["resultColDate"]; ?></th>
							<th style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;"><?php echo $txtNewsfeed["resultColTitle"]; ?></th>
							<th style="height: 50px; padding: 3px; padding-left: 6px; padding-right: 6px;"><?php echo $txtNewsfeed["resultColLink"]; ?></th>
						</tr>
			<?php foreach( $result as $item ): ?>
			<?php $date = date( $txtNewsfeed["dateFormat"], strtotime( $item->date ) ); ?>
						<tr>
							<td><?php echo $date; ?></td>
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
