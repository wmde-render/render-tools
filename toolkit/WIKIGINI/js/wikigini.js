$(function() {
	
	// START JSONP
	// "Cooler" JSONP, but not compatible with all browsers
	// $.getJSON('http://localhost/wikigini/gini.php?language_code=' +
	// language_code + '&page_id=' + page_id + '&callback=?',
	// function(wikigini_data) {
	// END JSONP
	

	
	// Graph mode: 'datetime' or 'revisions'
	if (mode != 'datetime' && mode != 'revisions') {
		mode = 'datetime';
	}
	
	// Default start of the graph
	var batch_offset = 0;
	
	// Maximum amount of revisions to load at once... Highchart seems to be aloooot more performant than Hichstocks...
	var batch_size = 4999;
	
	// Show buttons if amount of revisions is greater than the batch size
	if ((batch_offset + batch_size + 1) < wikigini_data[mode].length) {
		$('#batch_last').show();
		$('#batch_current').show();
		$('#batch_next').show();
	}
	
	// Show correct mode switch buttons
	if (mode == 'datetime') {
		$('#mode_switch').val('Switch mode to revisions');
	} else {
		$('#mode_switch').val('Switch mode to time');
	}
	
	
	// DEBUG: Test if data is available, output first and last entity
	//alert('wikigini_data[mode]=' + wikigini_data[mode]);
	//alert('wikigini_data[mode][0][0]=' + wikigini_data[mode][0][0] + ', wikigini_data[mode][0][1]=' + wikigini_data[mode][0][1]);
	//alert('wikigini_data[mode][wikigini_data[mode].length - 1][0]' + wikigini_data[mode][wikigini_data[mode].length - 1][0] + ', wikigini_data[mode][wikigini_data[mode].length - 1][1]=' + wikigini_data[mode][wikigini_data[mode].length - 1][1]);
	
	
	// Uncomment if graph should start with the newest revisions
	//if (wikigini_data[mode].length > batch_size) {
		// batch_offset = wikigini_data[mode].length - batch_size - 1;
	//}
	
	// The Chart
	var chart = new Highcharts.Chart({
		chart: {
			renderTo: 'wikigini_graph',
			defaultSeriesType: 'area',
			zoomType: 'x'
		},

		xAxis: {
			type: (mode == 'datetime' ? 'datetime' : 'linear')
		},

		yAxis: {
			title: {text: 'Gini-Index'},
			min: 0,
			max: 1.0
		},

		tooltip: {// ' + wikigini_data['datetime_info'][this.x][0] + '
			formatter: function() {
				if (mode == 'datetime') {
					return '<b>Revision: </b>' + wikigini_data['datetime_info'][this.x][0] + '<br/><b>Date</b>: ' + Highcharts.dateFormat('%A, %b %e, %Y', this.x) + '<br/><b>Gini-Index</b>: ' + this.y;
				} else {
					return '<b>Revision: </b>' + this.x + '<br/><b>Date</b>: ' + Highcharts.dateFormat('%A, %b %e, %Y', wikigini_data['revisions_info'][this.x][0]) + '<br/><b>Gini-Index</b>: ' + this.y;
				}
			},
			crosshairs: true
		},

		plotOptions: {
			area: {
				fillOpacity: 0.3,
				marker: {
					enabled: false,
					symbol: 'circle',
					radius: 3,
					states: {
						hover: {
							enabled: true
						}
					}
				}
			},
            series: {
                cursor: 'pointer',
                point: {
                    events: {
                        click: function() {
							//http://en.wikipedia.org/w/index.php?curid=3434750&diff=523774212
							if (mode == 'datetime') {
								window.open('http://' + language_code + '.wikipedia.org/w/index.php?curid=' + page_id + '&diff=' + wikigini_data['datetime_info'][this.x][0], '_blank');
							} else {
								window.open('http://' + language_code + '.wikipedia.org/w/index.php?curid=' + page_id + '&diff=' + this.x, '_blank');
							}
						}
                    }
                }
            }
		},

		title : {
			text : 'WIKIGINI - Article "' + page_title + '" in language "' + language_code + '"'
		},

		series : [{
			name : 'Gini Coefficient',
			step : true,
			tooltip: {
				valueDecimals: 2
			},

			data : wikigini_data[mode].slice(batch_offset, batch_offset + batch_size)

		}]
	});


	var updateButtons = function() {
		if (batch_offset - batch_size > 0) {
			$('#batch_last').val('<< ' + (batch_offset - batch_size) + '-' + (batch_offset));
		} else if(batch_offset > 0) {
			$('#batch_last').val('<< 1-' + (batch_size + 1));
		} else {
			$('#batch_last').val('|-');
		}

		if ((batch_offset + batch_size + 1) < wikigini_data[mode].length) {
			$('#batch_current').val((batch_offset + 1) + '-' + (batch_offset + batch_size + 1));
		} else {
			$('#batch_current').val((batch_offset + 1) + '-' + wikigini_data[mode].length);
		}

		if (batch_offset + 2 * batch_size + 2 < wikigini_data[mode].length) {
			$('#batch_next').val((batch_offset + batch_size + 2) + '-' + (batch_offset + 2 * batch_size + 2) + ' >>');
		} else if (batch_offset + batch_size + 1 < wikigini_data[mode].length) {
			$('#batch_next').val((wikigini_data[mode].length - batch_size) + '-' + wikigini_data[mode].length + ' >>');
		} else {
			$('#batch_next').val('-|');
		}
	}


	updateButtons();


	// alert(batch_offset + ': ' + wikigini_data[mode][batch_offset]);
	// alert(batch_offset + batch_size + ': ' + wikigini_data[mode][batch_offset +
	// batch_size]);

	$('#batch_next').click(function() {

		if (batch_offset + batch_size + 1 < wikigini_data[mode].length) {
			if (wikigini_data[mode].length > batch_offset + 2 * batch_size + 2) {
				batch_offset = batch_offset + 2 * batch_size + 2;
			} else {
				batch_offset = wikigini_data[mode].length - batch_size - 1;
			}
			
	updateButtons();


	// alert(batch_offset + ': ' + wikigini_data[mode][batch_offset]);
	// alert(batch_offset + batch_size + ': ' + wikigini_data[mode][batch_offset +
	// batch_size]);

			chart.series[0].setData(wikigini_data[mode].slice(batch_offset, batch_offset + batch_size));
			chart.redraw();



		}
	});




	$('#batch_last').click(function() {

		if (batch_offset > 0) {

			
			if (batch_offset - batch_size - 1 > 0) {
				batch_offset = batch_offset - batch_size - 1;
			} else {
				batch_offset = 0;
			}

	// alert(batch_offset + ': ' + wikigini_data[mode][batch_offset]);
	// alert(batch_offset + batch_size + ': ' + wikigini_data[mode][batch_offset +
	// batch_size]);


			updateButtons();

			chart.series[0].setData(wikigini_data[mode].slice(batch_offset, batch_offset + batch_size));
			chart.redraw();

		}
	});

	$('#mode_switch').click(function() {
		if (mode == 'datetime') {
			window.location.href = '?language_code=' + language_code + '&page_id=' + page_id + '&mode=revisions';
		} else {
			window.location.href  = '?language_code=' + language_code + '&page_id=' + page_id + '&mode=datetime';
		}
		/*
		mode = 'revisions';
		chart.series[0].setData(wikigini_data[mode].slice(batch_offset, batch_offset + batch_size));
		//chart.xAxis[0].setType = 'linear';
		alert(chart.xAxis[0]);
		alert(chart.xAxis[0].axisType);
		chart.xAxis[0].attr({
			type: 'linear'
		});
		alert(chart.xAxis[0].type);
		chart.redraw();
		*/
	});



	// START JSONP
	// "Cooler" JSONP, but not compatible with all browsers
	// });
	// END JSONP
});
