<div class="infoBox">
	<?
	// Set graph colors
	$color = array(
				"#A91F0B",
				"#C0872D",
				"#6E1207",
				"#000000",
				"#FDC726",
				"#FF0000",
				"#FF0033",
				"#FF3333",
				"#FF6633",
				"#FF9933",
				"#FFCC33",
				"#FFFF33"
			);
	?>
	<BR><BR>

	<h2>Statistics by Category</h2> <BR><BR>
	<?
	$chart->setChartAttrs( array(
			'type' => 'pie',
			'color' => $color,
			'data' => $dataByCategory,
			'size' => array(600, 260)
			));
		// Print chart
		echo "<img src=".$chart->display().">"; 
	?>
</div>