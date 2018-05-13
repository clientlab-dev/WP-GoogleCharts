<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<title>Google charts</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<link rel="stylesheet" href="admin_style.css">

		<script>
		var args = top.tinymce.activeEditor.windowManager.getParams();
			function insertCaart(){

				$(function(){
						var $inputs = $('.chart-fields input, .chart-fields select');
						var values = {};
						$inputs.each(function() {
							values[this.name] = $(this).val();
						});
						console.log(values);
						args.tinymce.activeEditor.execCommand('mceInsertContent', false, '[googleChart title="'+values.title+'" url="'+values.url+'" chart-type="'+values.chart_type+'" filters="'+values.filters+'" vertical-title="'+values.vertical_title+'" horizontal-title="'+values.horizontal_title+'" ]');
						top.tinymce.activeEditor.windowManager.close();
						//alert('Шорткод графика добавлен!');
				});

			}
		</script>
	</head>
	<body>
		<?php
		 if ($_GET['url']!='') {
			$title = "Редактировать график";
		}else{
			$title = "Вставить новый график";
		}

		?>
		<h1><?php echo $title; ?></h1>
		<form id="chart-fields" class="chart-fields" action="#">
			<table>
				<tr>
					<td>
						<label>
							Заголовок графика
							<input name="title" type="text" value="<?php echo $_GET['title']; ?>" />
						</label>
					</td>
				</tr>
				<tr>
					<td>
						<label>
							Уникальный ключ документа <i>*</i>
							<input placeholder="1bAc8sLogPQODxwWJtOLkTrdXKcGofWiv5w921eUfDZQ" name="url" type="text" value="<?php echo $_GET['url']; ?>" />
						</label>
						<p class="descr">
							Документ должен быть опублиуован в интернете. <br />
							Уникальный ключ документа это часть URL адреса документа которую можно скопировать из адресной строки браузера<br />
							Пример: https://docs.google.com/spreadsheets/d/<span class="url">1bAc8sLogPQODxwWJtOLkTrdXKcGofWiv5w921eUfDZQ</span>/edit#gid=0
						</p><!-- /.descr -->
					</td>
				</tr>
				<tr>
					<td>
						<label>
							Тип графика
							<?php
								$arChartTypes = array(
									"ColumnChart" =>"Column Chart",
									"Histogram" =>"Histogram",
									"BarChart" =>"Bar Chart",
									"AreaChart" =>"Area Chart",
									"SteppedAreaChart" =>"Stepped Area Chart",
									"LineChart" =>"Line Chart",
									"PieChart" =>"Pie Chart",
									"DonutChart" =>"Donut Chart"
								);
							?>
							<select  name="chart_type">
								<?php foreach ($arChartTypes as $key => $value): ?>
									<?php
									$selected = '';
									 if ($_GET['chart-type']==$key){
										 $selected = 'selected';
										}
									?>
									<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
								<?php endforeach; ?>

							</select>
						</label>
						<p class="descr"><a target="_blank" href="https://developers.google.com/chart/interactive/docs/gallery">Перейти к примерам графиков</a></p>
					</td>
				</tr>
				<tr>
					<td>
						<label>
							Список столбцов фильтров
							<input name="filters" placeholder="тип,пол,партия" type="text" value="<?php echo $_GET['filters']; ?>" />
						</label>
						<p class="descr">Заголовки столбцов через запятую без пробелов</p><!-- /.descr -->
					</td>
				</tr>
				<tr>
					<td>
						<label>
							Подпись по вертикали
							<input name="vertical_title" type="text" value="<?php echo $_GET['vertical-title']; ?>" />
						</label>
					</td>
				</tr>
				<tr>
					<td>
						<label>
							Подпись по горизонтали
							<input name="horizontal_title" type="text" value="<?php echo $_GET['horizontal-title']; ?>" />
						</label>
					</td>
				</tr>
				<tr>
					<td>
						<button onclick="insertCaart();">Вставить график</button>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>
