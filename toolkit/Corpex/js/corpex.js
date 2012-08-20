$(document).ready(
		function() {

			var lastquery = '';
			var lastlang = '';

			var bigramlastquery = '';
			var bigramlastlang = '';

			var renderColorsOnce = [ "#9B0032", "#350262", "#680062",
					"#1337C5", "#861FA7", "#35303C" ];
			var renderColors = [].concat(renderColorsOnce, renderColorsOnce,
					renderColorsOnce);

			var pieargs = function(words) {
				var res = [];
				$.each(words, function(word, count) {
					res.push([ word, count ]);
				});
				return res;
			};
			var barargs = function(words) {
				var res = [];
				var n = 1;
				$.each(words, function(word, count) {
					res.push([ count, n ]);
					n += 1;
				});
				return res;
			};
			var barwords = function(words) {
				var res = [];
				$.each(words, function(word, count) {
					res.push(word);
				});
				return res;
			};

			var loadData = function(event) {
				var query = $('#q').val();
				var lang = $('#lang').val();

				if (lang == '') {
					lang = 'simple';
				}

				if ((lastquery == query) && (lastlang == lang))
					return;
				lastquery = query;
				lastlang = lang;

				// $('#spinner').show();
				$.getJSON('src/query.php', {
					'lang' : lang,
					'q' : query
				}, function(data, status, xhr) {
					$('#word').html(data.query);

					var formatnumber = function(n, all) {
						var s = (" " + n).trim();
						var len = s.length;
						var res = '<span title="absolute frequency">';
						$.each(s, function(i, l) {
							res += l;
							len -= 1;
							if ((len % 3 == 0) && (len > 0)) {
								res += ",";
							}
							;
						});
						res += '</span>';
						// res += ' / <span title="negative logarithmic
						// probability">' + (n/all) + '</span>';
						// TODO negativer 10er logarithmus
						return res;
					};

					$('#freq').html(formatnumber(data.freq, data.all));
					var list = function(words) {
						var res = '';
						var first = true;
						$.each(words, function(word, count) {
							if (first) {
								first = false;
							} else {
								res += ', ';
							}
							res += '"' + word + '" = ' + count;
						});
						return res;
					};
					$('#charlist').html(list(data.next));
					$('#wordlist').html(list(data.words));

					$('#wordbar').html('');
					$.jqplot('wordbar', [ barargs(data.words) ], {
						seriesDefaults : {
							renderer : $.jqplot.BarRenderer,
							rendererOptions : {
								barDirection : 'horizontal',
								barMargin : 2,
								varyBarColor : true
							},
							seriesColors : renderColors
						},
						axes : {
							xaxis : {
								min : 0,
								autoscale : true,
								tickOptions : {
									formatString : '%d'
								}
							},
							yaxis : {
								renderer : $.jqplot.CategoryAxisRenderer,
								ticks : barwords(data.words)
							}
						}
					});
					$('#wordpie').html('');
					$.jqplot('wordpie', [ pieargs(data.words) ], {
						seriesDefaults : {
							renderer : $.jqplot.PieRenderer,
							rendererOptions : {
								showDataLabels : true,
								dataLabels : 'label'
							},
							seriesColors : renderColors
						}
					});
					$('#charbar').html('');
					$.jqplot('charbar', [ barargs(data.next) ], {
						seriesDefaults : {
							renderer : $.jqplot.BarRenderer,
							rendererOptions : {
								barDirection : 'horizontal',
								barMargin : 2,
								varyBarColor : true
							},
							seriesColors : renderColors
						},
						axes : {
							xaxis : {
								min : 0,
								autoscale : true,
								tickOptions : {
									formatString : '%d'
								}
							},
							yaxis : {
								renderer : $.jqplot.CategoryAxisRenderer,
								ticks : barwords(data.next)
							}
						}
					});
					$('#charpie').html('');
					$.jqplot('charpie', [ pieargs(data.next) ], {
						seriesDefaults : {
							renderer : $.jqplot.PieRenderer,
							rendererOptions : {
								showDataLabels : true,
								dataLabels : 'label'
							},
							seriesColors : renderColors
						}
					});

					// $('#spinner').hide();
				});
				loadBigramData(event);
			};

			var loadBigramData = function(event) {
				var query = $('#q').val();
				var lang = $('#lang').val();
				if ((bigramlastquery == query) && (bigramlastlang == lang))
					return;
				if (bigramlastlang != lang) {
					$('#bigramrow').show();
				}
				bigramlastquery = query;
				bigramlastlang = lang;

				// $('#bigramspinner').show();
				$.getJSON('src/bigrams.php', {
					'lang' : lang,
					'q' : query
				}, function(data, status, xhr) {
					if (data.error) {
						$('#bigramspinner').hide();
						$('#bigramrow').hide();
						return;
					}
					if (!data.bigrams) {
						$('#bigramlist').html('');
						$('#bigrambar').html('');
						$('#bigrampie').html('');
						$('#bigramspinner').hide();
						return;
					}

					var list = function(words) {
						var res = '';
						var first = true;
						$.each(words, function(word, count) {
							if (first) {
								first = false;
							} else {
								res += ', ';
							}
							res += '"' + word + '" = ' + count;
						});
						return res;
					};
					$('#bigramlist').html(list(data.bigrams));

					$('#bigrambar').html('');
					$.jqplot('bigrambar', [ barargs(data.bigrams) ], {
						seriesDefaults : {
							renderer : $.jqplot.BarRenderer,
							rendererOptions : {
								barDirection : 'horizontal',
								barMargin : 2,
								varyBarColor : true
							},
							seriesColors : renderColors
						},
						axes : {
							xaxis : {
								min : 0,
								autoscale : true,
								tickOptions : {
									formatString : '%d'
								}
							},
							yaxis : {
								renderer : $.jqplot.CategoryAxisRenderer,
								ticks : barwords(data.bigrams)
							}
						}
					});
					$('#bigrampie').html('');
					$.jqplot('bigrampie', [ pieargs(data.bigrams) ], {
						seriesDefaults : {
							renderer : $.jqplot.PieRenderer,
							rendererOptions : {
								showDataLabels : true,
								dataLabels : 'label'
							},
							seriesColors : renderColors
						}
					});
					// $('#bigramspinner').hide();
				});
			};

			// $('#spinner').hide();
			$('#q').keyup(loadData);
			$('#lang').change(loadData);

			$.jqplot.config.enablePlugins = true;

			$('#wordlist').hide();
			$('#wordshow').click(function() {
				$('#wordlist').toggle();
			});

			$('#charlist').hide();
			$('#charshow').click(function() {
				$('#charlist').toggle();
			});

			// $('#bigramspinner').hide();
			$('#bigramlist').hide();
			$('#bigramshow').click(function() {
				$('#bigramlist').toggle();
			});
			
			$(document).ready(function() {
				loadData('');
			});
			// TODO set lang and q from the get parameters
		}

);
