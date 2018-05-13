// Load the Visualization API and the controls package.
jQuery(document).ready(function($){

		// внутри этой функции $ будет работать как jQuery


	google.charts.load('current', {
		'packages': ['corechart', 'controls']
	});
	google.charts.setOnLoadCallback(onGoogleChartsLoaded);

	function onGoogleChartsLoaded() {

		$('.google-chart-entity').each(function () {
			var entity = $(this);

			var opt = {
				tableId: entity.data('table-id'),
				visualisationType: entity.data('visualization-type'),
				title: entity.data('title'),
				hTitle: entity.data('h-title'),
				vTitle: entity.data('v-title'),
				filters: entity.data('filters-cols').split(',')
			}

			if (opt.filters.length > 0) {
				entity.prepend('<div id="select-container-' + entity.attr("id") + '" class="chart-selects-container"></div>');
				opt.selectContainer = "select-container-" + entity.attr("id");
			};

			console.log(opt);
			var tableURL = 'https://docs.google.com/spreadsheets/d/' + opt.tableId + '/gviz/tq?sheet=Sheet1&headers=1';

			drawSheetName('SELECT *', fillFilter);

			function drawSheetName(queryString, callbackName) {
				var queryString = encodeURIComponent(queryString);
				var query = new google.visualization.Query(tableURL + '&tq=' + queryString);
				query.send(callbackName);
			}

			function handleSampleDataQueryResponse(response) {
				if (response.isError()) {
					console.error('Error in qsuery: ' + response.getMessage() + ' ' + response.getDetailedMessage());
					return;
				}

				var data = response.getDataTable();

				//var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
				var chart = new google.visualization[opt.visualisationType](entity.find('.chart')[0]);
				//var chart = new google.visualization.PieChart(document.getElementById('chart'));

				var options = {
					height: 400,
					title: opt.title,
					//colors: ['#9575cd', '#33ac71'],
					hAxis: {
						title: opt.hTitle
					},
					vAxis: {
						title: opt.vTitle
					}
				};
				chart.draw(data, options);
			}

			function fillFilter(response) {
				console.log(response);
				$(function () {
					var sortColArr = opt.filters;
					var sortColObjArr = [];
					var sortColId = '';
					var colPos = '';
					var drownCols = [];

					$.each(response.J.ng, function (key, val) {
						if (sortColArr.indexOf(val.label) >= 0) {
							sortColObjArr.push({
								col_pos: key * 1,
								col_id: val.id,
								col_label: val.label
							});
						} else {
							drownCols.push(val.id);
						}
					});

					function getColIdFromSortColObjArrByLabel(label) {
						var res = '';
						$.each(sortColObjArr, function (key, value) {
							if (opt.selectContainer + '-' + value.col_label === label) {
								res = value.col_id;
							};
						});

						return res;
					}

					var selectsContainer = $('#' + opt.selectContainer);

					$.each(sortColObjArr, function (key, value) {
						selectsContainer.append('<select id="' + opt.selectContainer + '-' + value.col_label + '" ></select>');
						value.optionsList = [];
						$.each(response.J.og, function (key, val) {
							value.optionsList.push(val.c[value.col_pos]['v']);
						});
						value.optionsList = value.optionsList.getUnique();
						$('#' + opt.selectContainer + '-' + value.col_label).append('<option value="0" >' + value.col_label + ' (Все)</option>');
						$.each(value.optionsList, function (k, v) {
							$('#' + opt.selectContainer + '-' + value.col_label).append('<option value="' + v + '" >' + v + '</option>');
						});
					});

					function drawChart(query) {
						if (query) {
							var query = "select " + drownCols.join(", ") + " " + query;
						} else {
							var query = "select " + drownCols.join(", ");
						}
						drawSheetName(query, handleSampleDataQueryResponse);
					}


					var selectsVals = [];

					selectsContainer.find('select').off().change(function () {
						var currentSelect = $(this);
						var query = false;
						var isExist = false;
						$.each(selectsVals, function (key, value) {
							if (value.sort_col == currentSelect.attr('id') && getColIdFromSortColObjArrByLabel(currentSelect.attr('id')) == value.sort_col_id) {
								isExist = true;
							}
						});

						if (!isExist) { //Еще нет
							selectsVals.push({
								value: currentSelect.val(),
								sort_col: $(this).attr('id'),
								sort_col_id: getColIdFromSortColObjArrByLabel(currentSelect.attr('id')),

							});
						} else { // уже есть
							selectsVals.forEach(function (value, key) {
								if (value.sort_col == currentSelect.attr('id') && getColIdFromSortColObjArrByLabel(currentSelect.attr('id')) == value.sort_col_id) {
									if (currentSelect.val() == 0) {
										selectsVals.splice(key, 1);
										return;
									} else {
										selectsVals[key]['value'] = currentSelect.val();
									}
								}
							});
						}

						var selectsValsLength = selectsVals.length;
						if (selectsValsLength > 0) {
							if (selectsValsLength > 1) {
								query = query = " where ";
								$.each(selectsVals, function (key, value) {
									if (key < selectsValsLength - 1) {
										query += value.sort_col_id + " = '" + value.value + "' AND ";
									} else {
										query += value.sort_col_id + " = '" + value.value + "'";
									}
								});
							} else {
								query = " where " + selectsVals[0]['sort_col_id'] + " = " + "'" + selectsVals[0]['value'] + "'";
							}
						}
						drawChart(query);
					});

					drawChart();
				});
			}


		});
		Array.prototype.getUnique = function () {
			var o = {},
				a = [],
				i, e;
			for (i = 0; e = this[i]; i++) {
				o[e] = 1
			};
			for (e in o) {
				a.push(e)
			};
			return a;
		}
	}
});
