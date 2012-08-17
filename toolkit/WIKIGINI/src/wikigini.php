<?php 
$ts_pw = posix_getpwuid(posix_getuid());

require($ts_pw['dir'] . "/public_html/toolkit/WIKIGINI/inc/src/db.inc.php");
?>

<div id="Ueberschrift" style="float: left; vertical-align: middle;">
	<div id="Introduction">
		<h2>
			<?php echo $Headline; ?>
		</h2>
		<p id="Description">
			<?php echo $Description; ?>
		</p>
<!-- 		<p>
			<a href="info.php"><?php echo $MoreInfo; ?> </a>
		</p> -->
	</div>

	<div>
		<font face="Arial" size="-1">

			<table border="1" width="400">

				<tr width="100%" id="show_graph">
					<td width="100%">
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
							<table width="100%">
								<tr>
									<td width="20%"><font size="2">Article:</font>
									</td>
									<td width="80%"><select name="article_id" style="width: 100%">
											<?php
											$table = "sa_article";
											// 										mysql_connect($hostname, $username, $password) OR die("Cannot connect the data base");
											// 										mysql_select_db($dbname) or die(mysql_error());

											$query = "SELECT * FROM sa_article ORDER BY article_name";
											$result = mysql_query($query) or die(mysql_error());
											?>

											<?php while($row = mysql_fetch_array($result)) { ?>
											<option value="<?php echo $row['id'] ?>"
											<?php if($_POST['article_id'] == $row['id']) { echo "selected"; } ?>>
												<?php echo $row['article_name']; ?>
											</option>
											<?php } ?>
									</select>
									</td>
								</tr>

								<tr>
									<td width="20%"><font size="2">hAxis:</font>
									</td>
									<td width="80%"><select name="haxis" style="width: 100%">
											<option value="revisionid"
											<?php if($_POST['haxis'] == 'revisionid') { echo "selected"; } ?>>Revision
												Id</option>
											<option value="datetime"
											<?php if($_POST['haxis'] == 'datetime') { echo "selected"; } ?>>Date</option>
									</select>
									</td>
								</tr>

								<tr>
									<td colspan="2" width="100%">
										<table width="100%">
											<tr>
												<td width="70%"><font size="2"> <?php
												if($_POST['showgraph'] == 'show graph') {
													$query = "SELECT * FROM `sa_html` WHERE `article_id` = '".$article_id."' AND `method_id` = '1'";
													$result = mysql_query($query) or die(mysql_error());

													$num_rows = mysql_num_rows($result);
													if($num_rows > 0) {
														?> <a href="javascript:void(0)"
														onclick="window.open('showhtml.php?aid=<?php echo $article_id; ?>','comment','resizable=yes,scrollbars=yes,dependent=yes')">Show
															last revision (ArticleTree)</a> <?php 	}
												}
												?>
												</font>
												</td>
												<td width="30%"><input type="submit" name="showgraph"
													value="show graph" style="width: 100%">
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table>

		</font>

		<!-- 	<img src="email.jpg" alt="feel free to mail me" /> -->

		<?php
		if($_POST['showgraph'] == 'show graph') {
// 			echo '<div id="container" style="width:1600px; height:400px"></div>';
			echo '<div id="container" style="width:100%; height:400px"></div>';
			#echo '<img src="email.jpg" />';
		}
		?>

		<font face="Arial" size="2">
			<table width="100%" border="0">
				<tr>
					<td width="50%" align="right"><font size="2">
							<form name="prevpage"
								action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
								<?php
								if($page && $page != 1) {
									?>
								<input type="hidden" name="page"
									value="<?php echo ($page - 1); ?>" /> <input type="hidden"
									name="article_id" value="<?php echo $_POST['article_id']; ?>" />
								<input type="hidden" name="haxis"
									value="<?php echo $_POST['haxis']; ?>" /> <input type="hidden"
									name="showgraph" value="show graph" /> <a
									href="javascript:document.prevpage.submit()"
									style="text-decoration: none"><?php echo ($page - 1)."&#8592;&nbsp;"; ?>
								</a>
								<?php
								}
								?>
							</form>
					</font></td>
					<td width="50%" align="left"><font size="2">
							<form name="nextpage"
								action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
								<?php
								if($page && $page != $endpage) {
									?>
								<input type="hidden" name="page"
									value="<?php echo ($page + 1); ?>" /> <input type="hidden"
									name="article_id" value="<?php echo $_POST['article_id']; ?>" />
								<input type="hidden" name="haxis"
									value="<?php echo $_POST['haxis']; ?>" /> <input type="hidden"
									name="showgraph" value="show graph" /> <a
									href="javascript:document.nextpage.submit()"
									style="text-decoration: none"><?php echo "&nbsp;&#8594;".($page + 1); ?>
								</a>
								<?php
								}
								?>
							</form>
					</font></td>
				</tr>
			</table>
		</font>
	</div>
</div>
