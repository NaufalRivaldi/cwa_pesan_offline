<h2>Application Statistic</h2>

<div id="chartContainer" style="height: 300px; width: 100%;">
</div>
<script>

window.onload = function () {
	var chart = new CanvasJS.Chart("chartContainer", {
		title:{
			text: "Statistik Penggunaan 30 hari terakhir"              
		},
		data: [              
		{
			// Change type to "doughnut", "line", "splineArea", etc.
			type: "column",
			dataPoints: [
			<?php
			$chart = $this->mdbackdoor->get_statistic(null, 30);
			$out = "";
			foreach($chart as $row){
				$lbl = date("d F",strtotime($row['tgl']));
				$out .= "{label : \"$lbl\", y : $row[hit]},";
			}
			$out = substr($out,0,-1);
			echo $out;
			?>
			]
		}
		]
	});
	chart.render();
}

</script>