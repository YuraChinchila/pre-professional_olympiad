<?php
	session_start();
	$id = $_SESSION['id'];
	$db = new PDO('sqlite:database.sqlite3');
	$res = $db->query("SELECT day, weight FROM information WHERE user_id=$id ORDER BY sort");
	$elem = [];
	while ($cur = $res->fetchObject()) {
		$elem[] = $cur;
	}
	$weight = array();
	$date = array();
	for ($i = 0; $i < sizeof($elem); $i++) {
		array_push($weight, $elem[$i]->weight);
		array_push($date, $elem[$i]->day);
	}
	$json_w = json_encode($weight);
	$json_d = json_encode($date);
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	let weight = new Array(), idx, mm, dd;
	let w = JSON.parse('<?php echo $json_w; ?>');
	let d = JSON.parse('<?php echo $json_d; ?>');
	for (idx = 0; idx < d.length; idx++) {
		mm = d[idx].split('.');
		dd = new Date(mm[2], mm[1] - 1, mm[0]);
		weight.push(new Array(dd, w[idx]));
	}
	google.charts.load('current', {packages: ['corechart', 'line']});
	let select, other_date;
	window.onload=function(){
		today();
		drawTypes( changeDate(new Date(), 0, 0, -7), new Date());
		select = document.getElementById("sel");
		other_date = document.getElementById("other_date");
	}
	function drawTypes(begin, end) {
		var data = new google.visualization.DataTable();
		data.addColumn('date', 'Дата');
		data.addColumn('number', 'кг');

		for(let i=0; i<weight.length; i++){
			if(weight[i][0]>=begin) 
				{
					if(weight[i][0]>end) break;
					data.addRow(new Array(weight[i][0], Number(weight[i][1])));
				}
		}
		var options = {
			series: {
				0: {targetAxisIndex: 0,},
				1: {targetAxisIndex: 1,}
			},
			hAxis: {
				format: 'YYYY.MM.dd',
			},   
		};

		var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
	function today(){
			let d = new Date();
			let day = d.getDate();
			let month = d.getMonth() + 1;
			let year = d.getFullYear();
			let today =  dateToString(getToday());
			let name_input = document.getElementById('finish');
			name_input.max = today;
			name_input.value = today;
			name_input = document.getElementById('start');
			name_input.max = today;
			name_input.value = dateToString(changeDate(today, -1, 0, 0));
	}
	function showStatistic(){
		let now = new Date();
		switch(select.value){
		case "week":
			drawTypes(changeDate(now, 0, 0, -7) , now);
			break;
		case "two_weeks":
			drawTypes(changeDate(now, 0, 0, -14) , now);
			break;
		
		case "month":
			drawTypes(changeDate(now, 0, -1, 0) , now);
			break;
		
		case "half_year":
			drawTypes(changeDate(now, 0, -6, 0) , now);
			break;
		
		case "year":
			drawTypes(changeDate(now, -1, 0, 0) , now);
			break;
		
		case "other":
			drawTypes(stringToDate(start.value) , stringToDate(finish.value));
			break;
		}
	}
	function changeStart(){
		finish.min=start.value;
	}
	function changeFinish(){
		start.max=finish.value;
	}
	function hiddenOther(){
		if(select.value=="other"){
			other_date.hidden=false;
		}
		else{
			other_date.hidden=true;
		}
	}
	//==================================================================================================================================================================================
	//========================================================================== Служебные функции =====================================================================================
	//==================================================================================================================================================================================
	function getToday(){
		let date = new Date;
		date.setMonth(date.getMonth()+1);
		return date;
	}
	function changeDate(date, year, month, day){
		if(typeof date == "object"){
			date=dateToString(date);
		}
		let mas_date=date.split("-");
		mas_date[0]=parseInt(mas_date[0])+parseInt(year);
		mas_date[1]=parseInt(mas_date[1])+parseInt(month);
		mas_date[2]=parseInt(mas_date[2])+parseInt(day);
		date = new Date(mas_date[0], mas_date[1], mas_date[2]);
		return date;
	}
	function dateToString(date){
		let day = date.getDate();
		if(day<10){
			day="0"+day;
		}
		let month = date.getMonth();
		if(month<10){
			month="0"+month;
		}
		let year = date.getFullYear();
		let today = year + "-" + month + "-" + day;
			
		return today; 
	}
	function stringToDate(date){

		let mas_date=date.split("-");
		date = new Date();
		date.setYear(mas_date[0]);
		date.setMonth(mas_date[1]);
		date.setDate(mas_date[2]);
		return date;
	}			
</script>

<!DOCTYPE HTML>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="../assets/css/Chart.css" rel="stylesheet">
	</head>
	<body>
		<div class="accaunt"><a style="color: #817C7B;" href="../html/add_weight.html"><<< Добавить вес </a></div>
		<h1 class="h">Мой вес</h1>
		<div class="check_period">
			<div class="check">
				<div class="choose" onclick="showStatistic()">Выбрать временной промежуток
				<select id="sel" onchange="hiddenOther()">
					<option value="week" selected>Одна неделя</option>
					<option value="two_weeks">Две недели</option>
					<option value="month">Один месяц</option>
					<option value="half_year">Полгода</option>
					<option value="year">Год</option>
					<option value="other">Свой период</option>
				</select></div>
				<div id="middle" onload="today()">
					<div id="other_date" hidden="true">
					От: <input type="date" id="start" onchange="changeStart();">
					&#160; До: <input type="date" id="finish" onchange="changeFinish();"></div>
					</div>
			</div>
			<div id="chart_div"></div>
			<div class="phrase">“Успех - это возможность терпеть поражение без потери энтузиазма”</div>
		</div>
	</body>
</html>